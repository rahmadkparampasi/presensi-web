<?php

namespace App\Http\Controllers;

use App\Models\AgModel;
use App\Models\AIModel;
use Illuminate\Http\Request;

class AgController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOAg',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'AGAMA',
            'PageTitle' => 'Agama',
            'BasePage' => 'setag',
        ];
    }

    public function index()
    {
        $this->data['Pgn'] = $this->getUser();
        
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'agAddData';
        $this->data['UrlForm'] = 'ag';
 
        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        $this->data['Ag'] = AgModel::select(['ag_id', 'ag_nm', 'ag_act'])->orderBy('ag_ord', 'desc')->get();
        $this->data['Ag'] = $this->setData($this->data['Ag']);

        return view('ag.index', $this->data);
    }

    public function load()
    {
        $this->data['Pgn'] = $this->getUser();
        
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'agAddData';
        $this->data['UrlForm'] = 'ag';
 
        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        $this->data['Ag'] = AgModel::select(['ag_id', 'ag_nm', 'ag_act'])->orderBy('ag_ord', 'desc')->get();
        $this->data['Ag'] = $this->setData($this->data['Ag']);

        return view('ag.data', $this->data);
    }

    static function getDataActStat()
    {
        return AgModel::where('setag_act', '1')->select(['setag_id', 'setag_nm',])->orderBy('setag_ord', 'asc')->get();
    }

    public function insertData(Request $request)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");         

        $AgModel = new AgModel();

        $this->data['Pgn'] = $this->getUser();

        $AgModel->ag_nm = $request->ag_nm;
        $AgModel->ag_ucreate = $this->data['Pgn']->users_id;
        $AgModel->ag_uupdate = $this->data['Pgn']->users_id;
        $AgModel->ag_act = "1";

        $save = $AgModel->save();
        if ($save) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Tingkat Pendidikan Berhasil Disimpan"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Tingkat Pendidikan Tidak Dapat Disimpan'];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $AgModel = new AgModel();

        $this->data['Pgn'] = $this->getUser();

        $ag_id = $request->ag_id;

        $update = $AgModel::where('ag_id', $ag_id)->update([
            'ag_nm' => $request->ag_nm,
            'ag_uupdate' => $this->data['Pgn']->users_id
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Tingkat Pendidikan Berhasil Diubah"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Tingkat Pendidikan Tidak Dapat Diubah'];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($ag_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $AgModel = new AgModel;

        $delete = $AgModel::where('ag_id', $ag_id)->delete([]);
        if ($delete) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Tingkat Pendidikan Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Tingkat Pendidikan Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setAct($ag_act, $ag_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $AgModel = new AgModel();

        $message = "Dinonaktifkan";
        if ($ag_act=="1") {
            $message = "Diaktifkan";
        }

        $update = $AgModel::where('ag_id', $ag_id)->update([
            'ag_act' => $ag_act
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Tingkat Pendidikan Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Tingkat Pendidikan Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setData($data)
    {
        for ($i=0; $i < count($data); $i++) { 
            
            $data[$i]['ag_actAltT'] = "Aktif";
            $data[$i]['ag_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
            $data[$i]['ag_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Agama\", \"".url('ag/setAct/0/'.$data[$i]['ag_id'])."\", \"".url('ag/load')."\", \"".$this->data['IdForm']."\", \"".$this->data['IdForm']."data\", \"".$this->data['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
            if ($data[$i]['ag_act']=="0") {
                $data[$i]['ag_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data[$i]['ag_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Agama\", \"".url('ag/setAct/1/'.$data[$i]['ag_id'])."\", \"".url('ag/load')."\", \"".$this->data['IdForm']."\", \"".$this->data['IdForm']."data\", \"".$this->data['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data[$i]['ag_actAltT'] = "Tidak Aktif";                
            }
        }
        return $data;
    }
}
