<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SetkatpesModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'setkatpes';

    protected $primaryKey = 'setkatpes_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['setkatpes_id', 'setkatpes_nm', 'setkatpes_ps', 'setkatpes_pg', 'setkatpes_pu', 'setkatpes_sh', 'setkatpes_act', 'setkatpes_ucreate', 'setkatpes_uupdate'];
}
