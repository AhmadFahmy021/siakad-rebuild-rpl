<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasUuids;
    protected $guarded = ['id'];
    protected $table = 'mata_pelajaran';
}
