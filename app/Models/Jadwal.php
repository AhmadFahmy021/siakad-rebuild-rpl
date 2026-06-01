<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    use HasUuids;
    protected $guarded = ['id'];
    protected $table = 'jadwal';

    /**
     * Get the kelas that owns the Jadwal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    /**
     * Get the matapelajaran that owns the Jadwal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function matapelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id', 'id');
    }

    /**
     * Get the guru that owns the Jadwal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id');
    }
}
