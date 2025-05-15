<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AbsenModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'absen';

    protected $primaryKey = 'absen_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['absen_id', 'absen_sisp', 'absen_setkatpesj', 'absen_bag', 'absen_tgl', 'absen_masukk', 'absen_masuk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp', 'absen_sts', 'absen_ucreate', 'absen_uupdate'];
}
