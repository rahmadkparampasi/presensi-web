<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SurveiqModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'surveiq';

    protected $primaryKey = 'surveiq_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['surveiq_id', 'surveiq_survei', 'surveiq_lbl', 'surveiq_desk', 'surveiq_ucreate', 'surveiq_uupdate'];
}
