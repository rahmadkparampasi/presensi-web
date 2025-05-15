<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AbseniModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'abseni';

    protected $primaryKey = 'abseni_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['abseni_id', 'abseni_absen', 'abseni_sispi', 'abseni_ucreate', 'abseni_uupdate'];
}
