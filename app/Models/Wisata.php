<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    use HasFactory;

    protected $table = 'wisata';

    protected $fillable = [
        'nama',
        'deskripsi',
        'kategori',
        'alamat',
        'kecamatan',
        'latitude',
        'longitude',
        'harga',
        'fasilitas',
        'gambar',
        'visits',
        'last_visited_at',
    ];

    protected $casts = [
        'fasilitas' => 'array',
        'last_visited_at' => 'datetime',
    ];

    /**
     * Relasi dengan WisataVisit
     */
    public function visitHistory()
    {
        return $this->hasMany(WisataVisit::class);
    }

    /**
     * Increment jumlah visits
     */
    public function incrementVisit()
    {
        $this->increment('visits');
        $this->update(['last_visited_at' => now()]);
    }
}
