<?php

namespace App\Http\Controllers;

use App\Models\AIModel;
use Illuminate\Http\Request;
use App\Models\DesaModel;
use App\Models\KabModel;
use App\Models\KecModel;
use App\Models\ProvModel;
use Illuminate\Support\Facades\DB;

class DesaController extends Controller
{
    protected $data;
    
    public function __construct()
    {
        $this->data = [
            'mOp' => 'mODesa',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'DESA / KELURAHAN',
            'PageTitle' => 'Desa / Kelurahan',
            'BasePage' => 'desa',
        ];
    }

    public function getDataJson($desa_kec)
    {
        $this->data['Desa'] = DesaModel::where('desa_kec', $desa_kec)->select('id', 'nama', 'jenis')->get();
        $this->data['Desa'] = $this->setData($this->data['Desa']);

        $this->data['DesaN'] = [];
        for ($i=0; $i < count($this->data['Desa']); $i++) { 
            $this->data['DesaN'][$i]['optValue'] = '';
            $this->data['DesaN'][$i]['optValue'] = $this->data['Desa'][$i]['id'];

            $this->data['DesaN'][$i]['optText'] = '';
            $this->data['DesaN'][$i]['optText'] = $this->data['Desa'][$i]['namaAlt'];
        }
        return $this->data['DesaN'];
    }

    static function getDataByKec($desa_kec)
    {
        $Desa = DesaModel::where('desa_kec', $desa_kec)->select('id', 'nama')->orderBy('nama', 'asc')->get();
        $Desa = DesaController::setDataSt($Desa);

        return $Desa;
    }

    static function setDataSt($data)
    {
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['jenisAlt'] = 'Desa';
            if ($data[$i]['jenis']=="K") {
                $data[$i]['jenisAlt'] = 'Kelurahan';
            }

            $data[$i]['namaAlt'] = ucwords(strtolower($data[$i]['nama']));
            $data[$i]['namaAltJns'] = $data[$i]['jenisAlt']." ".ucwords(strtolower($data[$i]['nama']));

            
        }
        return $data;
    }
    public function setData($data)
    {
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['jenisAlt'] = 'Desa';
            if ($data[$i]['jenis']=="K") {
                $data[$i]['jenisAlt'] = 'Kelurahan';
            }

            $data[$i]['namaAlt'] = ucwords(strtolower($data[$i]['nama']));
            $data[$i]['namaAltJns'] = $data[$i]['jenisAlt']." ".ucwords(strtolower($data[$i]['nama']));

            
        }
        return $data;
    }

    /**
     * @param  string  $id
     * @param  string  $tkt D = Desa/Kel K1 = Kecamatan K2 = Kabupaten P = Provinsi
     * @return string
     */
    static function setAlamat($id, $tkt = 'D')
    {
        if ($tkt==''||($tkt!='D'&&$tkt!='K1'&&$tkt!='K2'&&$tkt!='P')) {
            return 'Tidak Ada Daerah';
        }

        $alt = ' Desa ';
        if ($tkt == 'D'||$tkt == 'K1'||$tkt == 'K2'||$tkt == 'P') {
            $Desa= DesaModel::where('desa.id', $id)->select('nama', 'jenis', 'desa_kec')->get()->first();
            if ($Desa->jenis=="K") {
                $alt = ' Kel. ';
            }
            $alt .= $Desa->nama;
        }
        
        if ($tkt == 'K1'||$tkt == 'K2'||$tkt == 'P') {
            $Kec= KecModel::where('kec.id', $Desa->desa_kec)->select('nama', 'kec_kab')->get()->first();
            $alt .= ', Kec. '.$Kec->nama;
        }

        if ($tkt == 'K2'||$tkt == 'P') {
            $Kab= KabModel::where('kab.id', $Kec->kec_kab)->select('nama', 'jenis', 'kab_prov')->get()->first();
            if ($Kab->jenis=="KAB") {
                $alt .= ', Kabupaten ';
            }elseif ($Kab->jenis=="KOTAADM") {
                $alt .= ', Kota Administrasi ';
            }elseif ($Kab->jenis=="KOTA") {
                $alt .= ', Kota . ';
            }
            $alt .= ucwords(strtolower($Kab->nama));
        }

        if ($tkt == 'P') {
            $Prov= ProvModel::where('prov.id', $Kab->kab_prov)->select('nama')->get()->first();
            $alt .= ', Prov. '.ucwords(strtolower($Prov->nama));
        }


        return $alt;

    }
}
