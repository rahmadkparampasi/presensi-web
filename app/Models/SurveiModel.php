<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SurveiModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'survei';

    protected $primaryKey = 'survei_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['survei_id', 'survei_thn', 'survei_kuis', 'survei_act', 'survei_ucreate', 'survei_uupdate'];
}
