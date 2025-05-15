<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BagKModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'bagk';

    protected $primaryKey = 'bagk_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['bagk_id', 'bagk_sisp', 'bagk_bag', 'bagk_satker', 'bagk_ucreate', 'bagk_uupdate'];
}
