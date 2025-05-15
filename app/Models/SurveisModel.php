<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SurveisModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'surveis';

    protected $primaryKey = 'surveis_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['surveis_id', 'surveis_survei', 'surveis_sisp', 'surveis_ucreate', 'surveis_uupdate'];
}
