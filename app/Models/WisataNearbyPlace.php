<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WisataNearbyPlace extends Model
{
    use HasFactory;

    protected $table = 'wisata_nearby_places';

    protected $fillable = [
        'wisata_id',
        'nama',
        'kategori',
        'alamat',
        'latitude',
        'longitude',
    ];

    protected $appends = ['kategori_label'];

    public function wisata()
    {
        return $this->belongsTo(Wisata::class);
    }

    public function getKategoriLabelAttribute()
    {
        return match ($this->kategori) {
            'hotel' => 'Hotel',
            'restaurant' => 'Tempat Makan',
            'service' => 'Layanan',
            default => 'Lainnya',
        };
    }
}
