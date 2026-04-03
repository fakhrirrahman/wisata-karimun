<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WisataReview extends Model
{
    use HasFactory;

    protected $table = 'wisata_reviews';

    protected $fillable = [
        'wisata_id',
        'nama_pengulas',
        'rating',
        'ulasan',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function wisata()
    {
        return $this->belongsTo(Wisata::class);
    }
}