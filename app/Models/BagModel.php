<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BagModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'bag';

    protected $primaryKey = 'bag_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['bag_id', 'bag_nm', 'bag_prnt', 'bag_act', 'bag_str', 'bag_thn', 'bag_ucreate', 'bag_uupdate'];
}
