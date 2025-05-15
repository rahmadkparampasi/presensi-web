<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SetstspegModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'setstspeg';

    protected $primaryKey = 'setstspeg_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['setstspeg_id', 'setstspeg_nm', 'setstspeg_act', 'setstspeg_ucreate', 'setstspeg_uupdate'];
}
