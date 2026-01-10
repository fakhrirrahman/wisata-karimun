<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WisataVisit extends Model
{
    use HasFactory;

    protected $table = 'wisata_visits';
    
    // Disable timestamps karena tabel tidak punya created_at & updated_at
    public $timestamps = false;

    protected $fillable = [
        'wisata_id',
        'user_id',
        'ip_address',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function wisata()
    {
        return $this->belongsTo(Wisata::class);
    }
}
