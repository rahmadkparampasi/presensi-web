<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AgModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'setag';

    protected $primaryKey = 'setag_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['setag_id', 'setag_nm', 'setag_act', 'setag_ucreate', 'setag_uupdate'];
}
