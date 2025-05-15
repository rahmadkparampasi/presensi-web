<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SurveiqaModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'surveiqa';

    protected $primaryKey = 'surveiqa_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['surveiqa_id', 'surveiqa_surveiq', 'surveiqa_a', 'surveiqa_v', 'surveiqa_ucreate', 'surveiqa_uupdate'];
}
