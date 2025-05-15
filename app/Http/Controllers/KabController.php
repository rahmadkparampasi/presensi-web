<?php

namespace App\Http\Controllers;

use App\Models\AIModel;
use Illuminate\Http\Request;
use App\Models\KabModel;

class KabController extends Controller
{
    protected $data;
    
    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOKab',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'KABUPATEN / KOTA',
            'PageTitle' => 'Kabupaten / Kota',
            'BasePage' => 'kab',
        ];
    }

    static function getData()
    {
        $Kab = KabModel::where('kab_prov', '0111')->select('id', 'nama')->orderBy('nama', 'asc')->get();
        // $Kec = KabController::setDataSt($Kec);

        return $Kab;
    }

    static function getDataByProv($kab_prov)
    {
        $Kab = KabModel::where('kab_prov', $kab_prov)->select('id', 'nama')->orderBy('nama', 'asc')->get();
        // $Kec = KabController::setDataSt($Kec);

        return $Kab;
    }

    // static function setDataSt($data)
    // {
    //     for ($i=0; $i < count($data); $i++) { 
    //         $data[$i]['namaAlt'] = ucwords(strtolower($data[$i]['nama']));
    //         $data[$i]['namaAltJns'] = "Kecamatan ".ucwords(strtolower($data[$i]['nama']));
    //     }
    //     return $data;
    // }

    public function getDataJson($kab_prov)
    {
        $this->data['Kab'] = KabModel::where('kab_prov', $kab_prov)->select('id', 'nama', 'jenis')->get();
        // $this->data['Kab'] = $this->setData($this->data['Kab']);

        $this->data['KabN'] = [];
        for ($i=0; $i < count($this->data['Kab']); $i++) { 
            $this->data['KabN'][$i]['optValue'] = '';
            $this->data['KabN'][$i]['optValue'] = $this->data['Kab'][$i]['id'];

            $this->data['KabN'][$i]['optText'] = '';
            $this->data['KabN'][$i]['optText'] = $this->data['Kab'][$i]['nama'];
        }
        return $this->data['KabN'];
    }

    public function setData($data)
    {
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['jenisAlt'] = 'Kabupaten';
            if ($data[$i]['jenis']=="KOTA") {
                $data[$i]['jenisAlt'] = 'Kota';
            }elseif ($data[$i]['jenis']=="KOTAADM") {
                $data[$i]['jenisAlt'] = 'Kota Administrasi';
            }

            $data[$i]['namaAlt'] = ucwords(strtolower($data[$i]['nama']));
            $data[$i]['namaAltJns'] = $data[$i]['jenisAlt']." ".ucwords(strtolower($data[$i]['nama']));

            
        }
        return $data;
    }

}
