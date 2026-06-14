<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Guru extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];
    protected $table = 'guru';

    // ── BARU: Daftarkan kolom profil agar bisa mass-assign ───────────
    // Kolom ini akan ditambah via migration baru
    protected $fillable = [
        'user_id', 'nip', 'no_telepon', 'alamat', 'foto', 'jenis_kelamin'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ── BARU: Accessor foto_url ───────────────────────────────────────
    // Panggil $guru->foto_url di Blade — otomatis fallback ke avatar default
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return Storage::url($this->foto);
        }
        return asset('images/default-avatar.png');
    }

    // ── BARU: Accessor warna badge absensi ───────────────────────────
    public function getWarnaBadgeAttribute(): string
    {
        return match($this->status ?? '') {
            'hadir' => 'success',
            'izin'  => 'warning',
            'sakit' => 'info',
            'alpha' => 'danger',
            default => 'secondary',
        };
    }
}
