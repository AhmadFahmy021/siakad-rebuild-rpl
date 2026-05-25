<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaKelas extends Model
{
    use HasUuids;
    protected $guarded = ['id'];
    protected $table = 'siswa_kelas';

    /**
     * Get the kelas that owns the SiswaKelas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    /**
     * Get the siswa that owns the SiswaKelas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }
}
