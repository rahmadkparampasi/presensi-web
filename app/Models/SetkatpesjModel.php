<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SetkatpesjModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'setkatpesj';

    protected $primaryKey = 'setkatpesj_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['setkatpesj_id', 'setkatpesj_setkatpes', 'setkatpesj_masuk', 'setkatpesj_keluar', 'setkatpesj_bts', 'setkatpesj_btsj', 'setkatpesj_tol', 'setkatpesj_tolj', 'setkatpesj_ucreate', 'setkatpesj_uupdate', 'setkatpesj_hr'];
}
