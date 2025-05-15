<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SispdpModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'sispdp';

    protected $primaryKey = 'sispdp_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['sispdp_id', 'sispdp_sisp', 'sispdp_setstspeg', 'sispdp_ucreate', 'sispdp_uupdate'];
}
