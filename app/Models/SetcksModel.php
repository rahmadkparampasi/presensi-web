<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SetcksModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'setcks';

    protected $primaryKey = 'setcks_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['setcks_id', 'setcks_nm', 'setcks_act', 'setcks_ucreate', 'setcks_uupdate'];
}
