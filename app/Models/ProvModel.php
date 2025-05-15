<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ProvModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'prov';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['id', 'nama'];
}
