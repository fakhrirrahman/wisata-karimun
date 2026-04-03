<?php

namespace Database\Seeders;

use App\Models\Wisata;
use Illuminate\Database\Seeder;
use ZipArchive;

class wisataKunjunganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sourcePath = $this->resolveSourcePath();

        if ($sourcePath === null) {
            $this->command?->warn('File data wisata tidak ditemukan. Simpan file Excel/CSV di public/wisata atau database/seeders/data.');

            return;
        }

        $rows = $this->readTabularRows($sourcePath);

        if (count($rows) <= 1) {
            $this->command?->warn('File data wisata kosong atau header tidak terbaca.');

            return;
        }

        $headerRow = $rows[0];
        $dataStartIndex = 1;

        if (isset($rows[1]) && $this->looksLikeHeaderContinuation($rows[1])) {
            $headerRow = $this->mergeHeaderRows($rows[0], $rows[1]);
            $dataStartIndex = 2;
        }

        $headers = array_map(fn ($header) => $this->normalizeHeader((string) $header), $headerRow);
        $statusColumnIndex = array_search('status object', $headers, true);

        Wisata::query()->delete();

        $inserted = 0;

        foreach (array_slice($rows, $dataStartIndex) as $row) {
            if ($statusColumnIndex !== false) {
                unset($row[$statusColumnIndex]);
            }

            $data = $this->mapRowToWisataPayload($headers, $row);

            if (($data['nama'] ?? '') === '') {
                continue;
            }

            Wisata::create($data);
            $inserted++;
        }

        $this->command?->info("Seeder wisata berhasil. Total data masuk: {$inserted}");
    }

    private function resolveSourcePath(): ?string
    {
        $candidates = [
            public_path('wisata/Data Wisata Karimun _wisata.xlsx'),
            public_path('wisata/data_wisata_karimun_wisata.xlsx'),
            public_path('wisata/wisata.xlsx'),
            public_path('wisata/wisata.csv'),
      
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function readTabularRows(string $path): array
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($extension === 'csv') {
            return $this->readCsvRows($path);
        }

        if ($extension === 'xlsx') {
            return $this->readXlsxRows($path);
        }

        return [];
    }

    private function readCsvRows(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return $rows;
        }

        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }

    private function readXlsxRows(string $path): array
    {
        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            return [];
        }

        $sharedStrings = $this->readSharedStrings($zip);
        $sheetXml = $this->readFirstSheetXml($zip);

        $zip->close();

        if ($sheetXml === null) {
            return [];
        }

        $xml = simplexml_load_string($sheetXml);

        if ($xml === false) {
            return [];
        }

        $rows = [];

        foreach ($xml->sheetData->row as $rowNode) {
            $rowData = [];

            foreach ($rowNode->c as $cell) {
                $ref = (string) $cell['r'];
                $column = preg_replace('/\d+/', '', $ref) ?: '';

                if ($column === '') {
                    continue;
                }

                $index = $this->columnToIndex($column);
                $type = (string) $cell['t'];

                if ($type === 's') {
                    $sharedIndex = (int) ($cell->v ?? 0);
                    $value = $sharedStrings[$sharedIndex] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = (string) ($cell->is->t ?? '');
                } else {
                    $value = (string) ($cell->v ?? '');
                }

                $rowData[$index] = trim($value);
            }

            if (!empty($rowData)) {
                ksort($rowData);
                $rows[] = $rowData;
            }
        }

        return $rows;
    }

    private function readSharedStrings(ZipArchive $zip): array
    {
        $xmlString = $zip->getFromName('xl/sharedStrings.xml');

        if ($xmlString === false) {
            return [];
        }

        $xml = simplexml_load_string($xmlString);

        if ($xml === false) {
            return [];
        }

        $result = [];

        foreach ($xml->si as $si) {
            if (isset($si->t)) {
                $result[] = (string) $si->t;
                continue;
            }

            $text = '';

            foreach ($si->r as $run) {
                $text .= (string) ($run->t ?? '');
            }

            $result[] = $text;
        }

        return $result;
    }

    private function readFirstSheetXml(ZipArchive $zip): ?string
    {
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');

        if ($sheetXml !== false) {
            return $sheetXml;
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);

            if (str_starts_with($name, 'xl/worksheets/sheet') && str_ends_with($name, '.xml')) {
                $content = $zip->getFromName($name);

                if ($content !== false) {
                    return $content;
                }
            }
        }

        return null;
    }

    private function columnToIndex(string $column): int
    {
        $column = strtoupper($column);
        $index = 0;

        for ($i = 0; $i < strlen($column); $i++) {
            $index = ($index * 26) + (ord($column[$i]) - 64);
        }

        return max(0, $index - 1);
    }

    private function normalizeHeader(string $header): string
    {
        $header = strtolower(trim($header));
        $header = preg_replace('/[^a-z0-9]+/', ' ', $header) ?: '';

        return trim($header);
    }

    private function looksLikeHeaderContinuation(array $row): bool
    {
        $normalized = array_map(fn ($value) => $this->normalizeHeader((string) $value), $row);
        $nonEmpty = array_values(array_filter($normalized, fn ($value) => $value !== ''));

        if (count($nonEmpty) === 0) {
            return false;
        }

        if (in_array('kecamatan', $nonEmpty, true)) {
            return true;
        }

        $containsTypicalData = false;
        foreach ($nonEmpty as $value) {
            if (is_numeric($value) || strlen($value) > 25) {
                $containsTypicalData = true;
                break;
            }
        }

        return !$containsTypicalData;
    }

    private function mergeHeaderRows(array $first, array $second): array
    {
        $merged = $first;
        $allIndexes = array_unique(array_merge(array_keys($first), array_keys($second)));

        foreach ($allIndexes as $index) {
            $top = trim((string) ($first[$index] ?? ''));
            $bottom = trim((string) ($second[$index] ?? ''));

            if ($top !== '' && $bottom !== '') {
                $merged[$index] = $top . ' ' . $bottom;
                continue;
            }

            $merged[$index] = $top !== '' ? $top : $bottom;
        }

        return $merged;
    }

    private function mapRowToWisataPayload(array $headers, array $row): array
    {
        $record = [];

        foreach ($headers as $index => $header) {
            if ($header === '' || $header === 'status object') {
                continue;
            }

            $record[$header] = trim((string) ($row[$index] ?? ''));
        }

        $nama = $this->pickValue($record, ['nama', 'nama wisata', 'nama objek', 'objek wisata', 'nama lokasi']);
        $kategori = $this->pickValue($record, ['kategori', 'jenis wisata', 'jenis']);
        $kecamatan = strtoupper($this->pickValue($record, ['kecamatan']));
        $alamat = $this->pickValue($record, ['alamat', 'lokasi', 'desa', 'kelurahan']);
        $deskripsi = $this->pickValue($record, ['deskripsi', 'keterangan', 'uraian']);
        $latitude = $this->pickValue($record, ['latitude', 'lat']);
        $longitude = $this->pickValue($record, ['longitude', 'long', 'lng']);
        $hargaRaw = $this->pickValue($record, ['harga', 'tarif', 'tiket']);
        $fasilitasRaw = $this->pickValue($record, ['fasilitas', 'fasilitas pendukung']);
        $visitsRaw = $this->pickValue($record, ['rerata kunjungan bulan', 'rerata kunjungan per bulan']);

        return [
            'nama' => $nama,
            'deskripsi' => $deskripsi !== '' ? $deskripsi : 'Deskripsi belum tersedia.',
            'kategori' => $kategori !== '' ? $kategori : 'Wisata Umum',
            'kecamatan' => $kecamatan !== '' ? $kecamatan : 'KARIMUN',
            'alamat' => $alamat !== '' ? $alamat : 'Kabupaten Karimun',
            'latitude' => $latitude !== '' ? $latitude : '0',
            'longitude' => $longitude !== '' ? $longitude : '0',
            'harga' => $this->toInteger($hargaRaw),
            'fasilitas' => $this->parseFasilitas($fasilitasRaw),
            'gambar' => null,
            'visits' => $this->toInteger($visitsRaw) ?? 0,
            'last_visited_at' => null,
        ];
    }

    private function pickValue(array $record, array $aliases): string
    {
        foreach ($aliases as $alias) {
            $normalized = $this->normalizeHeader($alias);

            if (isset($record[$normalized]) && $record[$normalized] !== '') {
                return $record[$normalized];
            }
        }

        return '';
    }

    private function toInteger(string $value): ?int
    {
        if ($value === '') {
            return null;
        }

        $clean = preg_replace('/[^0-9]/', '', $value) ?: '';

        return $clean === '' ? null : (int) $clean;
    }

    private function parseFasilitas(string $value): array
    {
        if ($value === '') {
            return [];
        }

        $parts = preg_split('/[\r\n,;]+/', $value) ?: [];

        return array_values(array_filter(array_map('trim', $parts), fn ($item) => $item !== ''));
    }
}
