<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SetkrjModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'setkrj';

    protected $primaryKey = 'setkrj_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['setkrj_id', 'setkrj_nm', 'setkrj_act', 'setkrj_ucreate', 'setkrj_uupdate'];
}
