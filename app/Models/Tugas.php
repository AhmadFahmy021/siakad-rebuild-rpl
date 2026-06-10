<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasUuids;
    protected $guarded = ['id'];
    protected $table = 'tugas';

    /**
     * Get the class this assignment belongs to.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Get all submissions for this assignment.
     */
    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }

    /**
     * Get the teacher who created this assignment.
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Get the subject for this assignment.
     */
    public function matapelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'matapelajaran_id');
    }
}
