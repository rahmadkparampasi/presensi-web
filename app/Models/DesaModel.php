<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class DesaModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'desa';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['id', 'desa_kec', 'nama', 'jenis'];
}
