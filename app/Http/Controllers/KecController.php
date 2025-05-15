<?php

namespace App\Http\Controllers;

use App\Models\AIModel;
use Illuminate\Http\Request;
use App\Models\KecModel;

class KecController extends Controller
{
    protected $data;
    
    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOKec',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'KECAMATAN',
            'PageTitle' => 'Kecamatan',
            'BasePage' => 'kec',
        ];
    }

    public function getDataJson($kec_kab)
    {
        $this->data['Kec'] = KecModel::where('kec_kab', $kec_kab)->select('id', 'nama')->orderBy('nama', 'asc')->get();
        $this->data['Kec'] = $this->setData($this->data['Kec']);

        $this->data['KecN'] = [];
        for ($i=0; $i < count($this->data['Kec']); $i++) { 
            $this->data['KecN'][$i]['optValue'] = '';
            $this->data['KecN'][$i]['optValue'] = $this->data['Kec'][$i]['id'];

            $this->data['KecN'][$i]['optText'] = '';
            $this->data['KecN'][$i]['optText'] = $this->data['Kec'][$i]['namaAlt'];
        }
        return $this->data['KecN'];
    }

    static function getData()
    {
        $Kec = KecModel::where('kec_kab', 'FGU01')->select('id', 'nama')->orderBy('nama', 'asc')->get();
        $Kec = KecController::setDataSt($Kec);

        return $Kec;
    }

    static function getDataByKab($kec_kab)
    {
        $Kec = KecModel::where('kec_kab', $kec_kab)->select('id', 'nama')->orderBy('nama', 'asc')->get();
        $Kec = KecController::setDataSt($Kec);

        return $Kec;
    }

    static function setDataSt($data)
    {
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['namaAlt'] = ucwords(strtolower($data[$i]['nama']));
            $data[$i]['namaAltJns'] = "Kecamatan ".ucwords(strtolower($data[$i]['nama']));
        }
        return $data;
    }

    public function setData($data)
    {
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['namaAlt'] = ucwords(strtolower($data[$i]['nama']));
            $data[$i]['namaAltJns'] = "Kecamatan ".ucwords(strtolower($data[$i]['nama']));
        }
        return $data;
    }
}
