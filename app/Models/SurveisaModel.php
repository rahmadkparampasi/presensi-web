<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SurveisaModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'surveisa';

    protected $primaryKey = 'surveisa_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['surveisa_id', 'surveisa_sisp', 'surveisa_surveiqa', 'surveisa_ucreate', 'surveisa_uupdate'];
}
