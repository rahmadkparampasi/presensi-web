<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SetlokModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'setlok';

    protected $primaryKey = 'setlok_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['setlok_id', 'setlok_long', 'setlok_lat', 'setlok_ucreate', 'setlok_uupdate'];
}
