<?php

namespace App\Http\Controllers;

use App\Models\SetcksModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetcksController extends Controller
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

            'WebTitle' => 'PENGATURAN CARA KESEKOLAH',
            'PageTitle' => 'Pengaturan Cara Kesekolah',
            'BasePage' => 'setcks',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setcksAddData';
        $this->data['UrlForm'] = 'setcks';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        
        if ($request->ajax()) {
            return SetcksController::loadData($this->data['Pgn'], $this->data);
        }

        return view('setcks.index', $this->data);
    }

    public function load(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setcksAddData';
        $this->data['UrlForm'] = 'setcks';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('setcks.data', $this->data);
    }

    static function loadData($Pgn, $formData)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $Setcks = SetcksModel::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'setcks_id', 'setcks_nm', 'setcks_act'])->orderBy('setcks_ord', 'desc')->get();
        $Setcks = SetcksController::setData($Setcks, $formData);
        return datatables()->of($Setcks)->addColumn('aksiStatus', function ($Setcks) use ($Pgn) {
            $button = '';
            $button .= $Setcks->setcks_actAltBu;
            return $button;
        })->addColumn('aksiEdit', function ($Setcks) use ($Pgn, $formData) {
            $button = '';
            $button .= '<button type="button" class="btn btn-warning mx-1" onclick="showForm(\''.$formData['IdForm'].'card\', \'block\'); cActForm(\''.$formData['IdForm'].'\', \''.route('setcks.update').'\'); addFill(\'setcks_id\', \''.$Setcks->setcks_id.'\'); addFill(\'setcks_nm\', \''.$Setcks->setcks_nm.'\');"><i class="fas fa-pen"></i></button>';
                
            $button .= '<button type="button" class="btn btn-danger mx-1" onclick="callOtherTWLoad(\'Menghapus Data Cara Ke Sekolah\',\''.url('setcks/delete/'.$Setcks->setcks_id).'\', \''.url('setcks/load').'\', \''.$formData['IdForm'].'\', \''.$formData['IdForm'].'data\', \''.$formData['IdForm'].'card\')"><i class="fas fa-trash"></i></button>';
            
            return $button;
        })
        ->rawColumns(['aksiStatus', 'aksiEdit'])->make(true);
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setcks_nm' => 'required',
        ];
        $attributes = [
            'setcks_nm' => 'Cara Ke Sekolah',
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
            $SetcksModel = new SetcksModel();
            
            $SetcksModel->setcks_nm = addslashes($request->setcks_nm);
            $save = $SetcksModel->save();
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
            'setcks_nm' => 'required',
        ];
        $attributes = [
            'setcks_nm' => 'Cara Ke Sekolah',
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
                $update = DB::table('setcks')->where('setcks_id', $request->setcks_id)->update([
                    'setcks_nm' => addslashes($request->setcks_nm),
                    'setcks_uupdate' => $this->data['Pgn']->users_id
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

    public function setAct($setcks_act, $setcks_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetcksModel = new SetcksModel();

        $message = "Dinonaktifkan";
        if ($setcks_act=="1") {
            $message = "Diaktifkan";
        }

        $update = $SetcksModel::where('setcks_id', $setcks_id)->update([
            'setcks_act' => $setcks_act
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

    public function deleteData($setcks_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetcksModel = new SetcksModel();

        $delete = $SetcksModel::where('setcks_id', $setcks_id)->delete([]);
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

    static function getNama($setcks_id)
    {
        $Setcks = SetcksModel::where('setcks_id', $setcks_id)->select(['setcks_id', 'setcks_nm'])->orderBy('setcks_ord', 'asc')->get()->first();
        return $Setcks->setcks_nm;
    }

    static function getDataActStat()
    {
        return SetcksModel::where('setcks_act', '1')->select(['setcks_id', 'setcks_nm',])->orderBy('setcks_ord', 'asc')->get();
    }

    static function setData($data, $formData)
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                
                $data[$i]['setcks_actAltT'] = "Aktif";
                $data[$i]['setcks_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
                $data[$i]['setcks_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Cara Ke Sekolah\", \"".url('setcks/setAct/0/'.$data[$i]['setcks_id'])."\", \"".url('setcks/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
                if ($data[$i]['setcks_act']=="0") {
                    $data[$i]['setcks_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setcks_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Cara Ke Sekolah\", \"".url('setcks/setAct/1/'.$data[$i]['setcks_id'])."\", \"".url('setcks/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setcks_actAltT'] = "Tidak Aktif";                
                }
            }
        }else{
            
            $data['setcks_actAltT'] = "Aktif";
            $data['setcks_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
            $data['setcks_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Cara Ke Sekolah\", \"".url('setcks/setAct/0/'.$data['setcks_id'])."\", \"".url('setcks/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
            if ($data['setcks_act']=="0") {
                $data['setcks_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['setcks_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Cara Ke Sekolah\", \"".url('setcks/setAct/1/'.$data['setcks_id'])."\", \"".url('setcks/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['setcks_actAltT'] = "Tidak Aktif";                
            }

        }
        return $data;
    }
}
