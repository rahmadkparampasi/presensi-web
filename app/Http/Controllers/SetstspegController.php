<?php

namespace App\Http\Controllers;

use App\Models\SetstspegModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetstspegController extends Controller
{
    protected $data;
    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOSetstspeg',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'PENGATURAN STATUS PEGAWAI',
            'PageTitle' => 'Pengaturan Status Pegawai',
            'BasePage' => 'setstspeg',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setstspegAddData';
        $this->data['UrlForm'] = 'setstspeg';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        
        if ($request->ajax()) {
            return SetstspegController::loadData($this->data['Pgn'], $this->data);
        }

        return view('setstspeg.index', $this->data);
    }

    public function load(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setstspegAddData';
        $this->data['UrlForm'] = 'setstspeg';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('setstspeg.data', $this->data);
    }

    static function loadData($Pgn, $formData)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $Setstspeg = SetstspegModel::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'setstspeg_id', 'setstspeg_nm', 'setstspeg_act'])->orderBy('setstspeg_ord', 'desc')->get();
        $Setstspeg = SetstspegController::setData($Setstspeg, $formData);
        return datatables()->of($Setstspeg)->addColumn('aksiStatus', function ($Setstspeg) use ($Pgn) {
            $button = '';
            $button .= $Setstspeg->setstspeg_actAltBu;
            return $button;
        })->addColumn('aksiEdit', function ($Setstspeg) use ($Pgn, $formData) {
            $button = '';
            $button .= '<button type="button" class="btn btn-warning mx-1" onclick="showForm(\''.$formData['IdForm'].'card\', \'block\'); cActForm(\''.$formData['IdForm'].'\', \''.route('setstspeg.update').'\'); addFill(\'setstspeg_id\', \''.$Setstspeg->setstspeg_id.'\'); addFill(\'setstspeg_nm\', \''.$Setstspeg->setstspeg_nm.'\');"><i class="fas fa-pen"></i></button>';
                
            $button .= '<button type="button" class="btn btn-danger mx-1" onclick="callOtherTWLoad(\'Menghapus Data Status Pegawai\',\''.url('setstspeg/delete/'.$Setstspeg->setstspeg_id).'\', \''.url('setstspeg/load').'\', \''.$formData['IdForm'].'\', \''.$formData['IdForm'].'data\', \''.$formData['IdForm'].'card\')"><i class="fas fa-trash"></i></button>';
            
            return $button;
        })
        ->rawColumns(['aksiStatus', 'aksiEdit'])->make(true);
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setstspeg_nm' => 'required',
        ];
        $attributes = [
            'setstspeg_nm' => 'Status Pegawai',
        ];
        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            $errorString = implode(",",$validator->getMessageBag()->all());
            $data['response'] = [
                'status' =>  Response::HTTP_BAD_REQUEST,
                'response' => "danger",
                'type' => "danger",
                'message' => $errorString
            ];
        }else{
            $SetstspegModel = new SetstspegModel();
            
            $SetstspegModel->setstspeg_nm = addslashes($request->setstspeg_nm);
            $save = $SetstspegModel->save();
            if ($save) {
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Status Pegawai Berhasil Disimpan"
                ];
            }else{
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Status Pegawai Tidak Dapat Disimpan'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setstspeg_nm' => 'required',
        ];
        $attributes = [
            'setstspeg_nm' => 'Status Pegawai',
        ];
        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            $errorString = implode(",",$validator->getMessageBag()->all());
            $data['response'] = [
                'status' =>  Response::HTTP_BAD_REQUEST,
                'response' => "danger",
                'type' => "danger",
                'message' => $errorString
            ];
        }else{
            try {
                $update = DB::table('setstspeg')->where('setstspeg_id', $request->setstspeg_id)->update([
                    'setstspeg_nm' => addslashes($request->setstspeg_nm),
                    'setstspeg_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Status Pegawai Berhasil Diubah"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Status Pegawai Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setAct($setstspeg_act, $setstspeg_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetstspegModel = new SetstspegModel();

        $message = "Dinonaktifkan";
        if ($setstspeg_act=="1") {
            $message = "Diaktifkan";
        }

        $update = $SetstspegModel::where('setstspeg_id', $setstspeg_id)->update([
            'setstspeg_act' => $setstspeg_act
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Status Pegawai Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Status Pegawai Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($setstspeg_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetstspegModel = new SetstspegModel();

        $delete = $SetstspegModel::where('setstspeg_id', $setstspeg_id)->delete([]);
        if ($delete) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Status Pegawai Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Status Pegawai Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }

    static function getNama($setstspeg_id)
    {
        $Setstspeg = SetstspegModel::where('setstspeg_id', $setstspeg_id)->select(['setstspeg_id', 'setstspeg_nm',])->orderBy('setstspeg_ord', 'asc')->get()->first();
        return $Setstspeg->setstspeg_nm;
    }

    static function getDataActStat()
    {
        return SetstspegModel::where('setstspeg_act', '1')->select(['setstspeg_id', 'setstspeg_nm',])->orderBy('setstspeg_ord', 'asc')->get();
    }

    static function setData($data, $formData)
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                
                $data[$i]['setstspeg_actAltT'] = "Aktif";
                $data[$i]['setstspeg_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
                $data[$i]['setstspeg_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Status Pegawai\", \"".url('setstspeg/setAct/0/'.$data[$i]['setstspeg_id'])."\", \"".url('setstspeg/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
                if ($data[$i]['setstspeg_act']=="0") {
                    $data[$i]['setstspeg_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setstspeg_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Status Pegawai\", \"".url('setstspeg/setAct/1/'.$data[$i]['setstspeg_id'])."\", \"".url('setstspeg/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setstspeg_actAltT'] = "Tidak Aktif";                
                }
            }
        }else{
            
            $data['setstspeg_actAltT'] = "Aktif";
            $data['setstspeg_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
            $data['setstspeg_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Status Pegawai\", \"".url('setstspeg/setAct/0/'.$data['setstspeg_id'])."\", \"".url('setstspeg/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
            if ($data['setstspeg_act']=="0") {
                $data['setstspeg_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['setstspeg_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Status Pegawai\", \"".url('setstspeg/setAct/1/'.$data['setstspeg_id'])."\", \"".url('setstspeg/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['setstspeg_actAltT'] = "Tidak Aktif";                
            }

        }
        return $data;
    }
}
