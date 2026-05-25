<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelas extends Model
{
    use HasUuids;
    protected $guarded = ['id'];
    protected $table = 'kelas';

    /**
     * Get the guru that owns the Kelas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id');
    }

    /**
     * Get the siswa that owns the Kelas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }
}
