<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class LapModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'lap';

    protected $primaryKey = 'lap_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['lap_id', 'lap_sisp', 'lap_fl', 'lap_nl', 'lap_ket', 'lap_bln', 'lap_thn', 'lap_c', 'lap_ucreate', 'lap_uupdate'];
}
