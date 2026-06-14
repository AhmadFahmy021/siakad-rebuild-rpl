// app/Models/Guru.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Guru extends Model
{
    protected $fillable = [
        'user_id', 'name', 'nip', 'no_telepon',
        'alamat', 'foto', 'jenis_kelamin'
    ];

    // Relasi ke User (untuk email, password)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor: Jika tidak ada foto, tampilkan default
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return Storage::url($this->foto);
        }
        return asset('images/default-avatar.png');
    }
}
