<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasUuids;
    protected $guarded = ['id'];
    protected $table = 'jadwal';
}
