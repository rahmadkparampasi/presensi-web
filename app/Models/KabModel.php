<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class KabModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'kab';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['id', 'kode', 'nama', 'kab_prov', 'jenis'];
}
