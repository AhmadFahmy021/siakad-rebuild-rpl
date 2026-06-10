<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasUuids;
    protected $guarded = ['id'];
    protected $table = 'nilai';

    /**
     * Get the subject that this grade belongs to.
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'matapelajaran_id');
    }

    /**
     * Get the class that this grade belongs to.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Get the student that this grade belongs to.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
