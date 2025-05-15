<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SettksModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'settks';

    protected $primaryKey = 'settks_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['settks_id', 'settks_nm', 'settks_act', 'settks_ucreate', 'settks_uupdate'];
}
