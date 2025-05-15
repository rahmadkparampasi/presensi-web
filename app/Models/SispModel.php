<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SispModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'sisp';

    protected $primaryKey = 'sisp_id';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['sisp_id', 'sisp_nmd', 'sisp_nm', 'sisp_nmb', 'sisp_tmptlhr', 'sisp_tgllhr', 'sisp_idsp', 'sisp_jk', 'sisp_alt',  'sisp_telp', 'sisp_tglkntrk', 'sisp_bag', 'sisp_kntrk', 'sisp_wa', 
    'sisp_wak','sisp_pic', 'sisp_bc', 'sisp_conf', 'sisp_ucreate', 'sisp_uupdate'];
}
