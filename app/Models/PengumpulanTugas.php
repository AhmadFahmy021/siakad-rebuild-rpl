<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    use HasUuids;

    protected $guarded = ['id'];
    protected $table = 'pengumpulan_tugas';

    /**
     * Get the student that submitted the assignment.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Get the assignment that was submitted.
     */
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }
}
