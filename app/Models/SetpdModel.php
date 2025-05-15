<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SetpdModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'setpd';

    protected $primaryKey = 'setpd_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['setpd_id', 'setpd_nm', 'setpd_act', 'setpd_ucreate', 'setpd_uupdate'];
}
