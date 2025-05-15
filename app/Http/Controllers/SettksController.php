<?php

namespace App\Http\Controllers;

use App\Models\SettksModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettksController extends Controller
{
    protected $data;
    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOSetrmr',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'PENGATURAN TRANSPORTASI KESEKOLAH',
            'PageTitle' => 'Pengaturan Transportasi Kesekolah',
            'BasePage' => 'settks',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'settksAddData';
        $this->data['UrlForm'] = 'settks';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        
        if ($request->ajax()) {
            return SettksController::loadData($this->data['Pgn'], $this->data);
        }

        return view('settks.index', $this->data);
    }

    public function load(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'settksAddData';
        $this->data['UrlForm'] = 'settks';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('settks.data', $this->data);
    }

    static function loadData($Pgn, $formData)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $Settks = SettksModel::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'settks_id', 'settks_nm', 'settks_act'])->orderBy('settks_ord', 'desc')->get();
        $Settks = SettksController::setData($Settks, $formData);
        return datatables()->of($Settks)->addColumn('aksiStatus', function ($Settks) use ($Pgn) {
            $button = '';
            $button .= $Settks->settks_actAltBu;
            return $button;
        })->addColumn('aksiEdit', function ($Settks) use ($Pgn, $formData) {
            $button = '';
            $button .= '<button type="button" class="btn btn-warning mx-1" onclick="showForm(\''.$formData['IdForm'].'card\', \'block\'); cActForm(\''.$formData['IdForm'].'\', \''.route('settks.update').'\'); addFill(\'settks_id\', \''.$Settks->settks_id.'\'); addFill(\'settks_nm\', \''.$Settks->settks_nm.'\');"><i class="fas fa-pen"></i></button>';
                
            $button .= '<button type="button" class="btn btn-danger mx-1" onclick="callOtherTWLoad(\'Menghapus Data Transportasi Kesekolah\',\''.url('settks/delete/'.$Settks->settks_id).'\', \''.url('settks/load').'\', \''.$formData['IdForm'].'\', \''.$formData['IdForm'].'data\', \''.$formData['IdForm'].'card\')"><i class="fas fa-trash"></i></button>';
            
            return $button;
        })
        ->rawColumns(['aksiStatus', 'aksiEdit'])->make(true);
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'settks_nm' => 'required',
        ];
        $attributes = [
            'settks_nm' => 'Cara Ke Sekolah',
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
            $SettksModel = new SettksModel();
            
            $SettksModel->settks_nm = addslashes($request->settks_nm);
            $save = $SettksModel->save();
            if ($save) {
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Cara Ke Sekolah Berhasil Disimpan"
                ];
            }else{
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Cara Ke Sekolah Tidak Dapat Disimpan'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'settks_nm' => 'required',
        ];
        $attributes = [
            'settks_nm' => 'Cara Ke Sekolah',
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
                $update = DB::table('settks')->where('settks_id', $request->settks_id)->update([
                    'settks_nm' => addslashes($request->settks_nm),
                    'settks_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Cara Ke Sekolah Berhasil Diubah"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Cara Ke Sekolah Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setAct($settks_act, $settks_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SettksModel = new SettksModel();

        $message = "Dinonaktifkan";
        if ($settks_act=="1") {
            $message = "Diaktifkan";
        }

        $update = $SettksModel::where('settks_id', $settks_id)->update([
            'settks_act' => $settks_act
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Cara Ke Sekolah Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Cara Ke Sekolah Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($settks_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SettksModel = new SettksModel();

        $delete = $SettksModel::where('settks_id', $settks_id)->delete([]);
        if ($delete) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Cara Ke Sekolah Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Cara Ke Sekolah Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }

    static function getNama($settks_id)
    {
        $Settks = SettksModel::where('settks_id', $settks_id)->select(['settks_id', 'settks_nm',])->orderBy('settks_ord', 'asc')->get()->first();
        return $Settks->settks_nm;
    }

    static function getDataActStat()
    {
        return SettksModel::where('settks_act', '1')->select(['settks_id', 'settks_nm',])->orderBy('settks_ord', 'asc')->get();
    }

    static function setData($data, $formData)
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                
                $data[$i]['settks_actAltT'] = "Aktif";
                $data[$i]['settks_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
                $data[$i]['settks_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Transportasi Kesekolah\", \"".url('settks/setAct/0/'.$data[$i]['settks_id'])."\", \"".url('settks/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
                if ($data[$i]['settks_act']=="0") {
                    $data[$i]['settks_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['settks_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Transportasi Kesekolah\", \"".url('settks/setAct/1/'.$data[$i]['settks_id'])."\", \"".url('settks/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['settks_actAltT'] = "Tidak Aktif";                
                }
            }
        }else{
            
            $data['settks_actAltT'] = "Aktif";
            $data['settks_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
            $data['settks_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Transportasi Kesekolah\", \"".url('settks/setAct/0/'.$data['settks_id'])."\", \"".url('settks/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
            if ($data['settks_act']=="0") {
                $data['settks_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['settks_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Transportasi Kesekolah\", \"".url('settks/setAct/1/'.$data['settks_id'])."\", \"".url('settks/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['settks_actAltT'] = "Tidak Aktif";                
            }

        }
        return $data;
    }
}
