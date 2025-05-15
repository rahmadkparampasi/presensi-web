<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SispiModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'sispi';

    protected $primaryKey = 'sispi_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['sispi_id', 'sispi_sisp', 'sispi_tiket', 'sispi_setkati', 'sispi_tglm', 'sispi_tgls', 'sispi_ket', 'sispi_stj', 'sispi_ketstj', 'sispi_fl', 'sispi_fle', 'sispi_ucreate', 'sispi_uupdate'];
}
