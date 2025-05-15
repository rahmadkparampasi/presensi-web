<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SetkatiModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'setkati';

    protected $primaryKey = 'setkati_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['setkati_id', 'setkati_nm', 'setkati_kd', 'setkati_act', 'setkati_ucreate', 'setkati_uupdate'];
}
