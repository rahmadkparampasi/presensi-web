<?php

namespace App\Http\Controllers;

use App\Models\SetkrjModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetkrjController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOSetkrj',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'PENGATURAN PEKERJAAN',
            'PageTitle' => 'Pengaturan Pekerjaan',
            'BasePage' => 'setkrj',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setkrjAddData';
        $this->data['UrlForm'] = 'setkrj';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        
        if ($request->ajax()) {
            return SetkrjController::loadData($this->data['Pgn'], $this->data);
        }

        return view('setkrj.index', $this->data);
    }

    public function load(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setkrjAddData';
        $this->data['UrlForm'] = 'setkrj';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('setkrj.data', $this->data);
    }

    static function loadData($Pgn, $formData)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $Setkrj = SetkrjModel::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'setkrj_id', 'setkrj_nm', 'setkrj_act'])->orderBy('setkrj_ord', 'desc')->get();
        $Setkrj = SetkrjController::setData($Setkrj, $formData);
        return datatables()->of($Setkrj)->addColumn('aksiStatus', function ($Setkrj) use ($Pgn) {
            $button = '';
            $button .= $Setkrj->setkrj_actAltBu;
            return $button;
        })->addColumn('aksiEdit', function ($Setkrj) use ($Pgn, $formData) {
            $button = '';
            $button .= '<button type="button" class="btn btn-warning mx-1" onclick="showForm(\''.$formData['IdForm'].'card\', \'block\'); cActForm(\''.$formData['IdForm'].'\', \''.route('setkrj.update').'\'); addFill(\'setkrj_id\', \''.$Setkrj->setkrj_id.'\'); addFill(\'setkrj_nm\', \''.$Setkrj->setkrj_nm.'\');"><i class="fas fa-pen"></i></button>';
                
            $button .= '<button type="button" class="btn btn-danger mx-1" onclick="callOtherTWLoad(\'Menghapus Data Pengaturan Pekerjaan\',\''.url('setkrj/delete/'.$Setkrj->setkrj_id).'\', \''.url('setkrj/load').'\', \''.$formData['IdForm'].'\', \''.$formData['IdForm'].'data\', \''.$formData['IdForm'].'card\')"><i class="fas fa-trash"></i></button>';
            
            return $button;
        })
        ->rawColumns(['aksiStatus', 'aksiEdit'])->make(true);
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setkrj_nm' => 'required',
            
        ];
        $attributes = [
            'setkrj_nm' => 'Nama Pekerjaan',
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
            $SetkrjModel = new SetkrjModel();
            
            $SetkrjModel->setkrj_nm = addslashes($request->setkrj_nm);
            $save = $SetkrjModel->save();
            if ($save) {
                
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pekerjaan Berhasil Disimpan"
                ];
            }else{
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pekerjaan Tidak Dapat Disimpan'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setkrj_nm' => 'required',
        ];
        $attributes = [
            'setkrj_nm' => 'Nama Pekerjaan',
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
                $update = DB::table('setkrj')->where('setkrj_id', $request->setkrj_id)->update([
                    'setkrj_nm' => addslashes($request->setkrj_nm),
                    'setkrj_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pekerjaan Berhasil Diubah"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pekerjaan Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setAct($setkrj_act, $setkrj_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetkrjsModel = new SetkrjModel();

        $message = "Dinonaktifkan";
        if ($setkrj_act=="1") {
            $message = "Diaktifkan";
        }

        $update = $SetkrjsModel::where('setkrj_id', $setkrj_id)->update([
            'setkrj_act' => $setkrj_act
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Pekerjaan Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pekerjaan Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($setkrj_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetkrjModel = new SetkrjModel();

        $delete = $SetkrjModel::where('setkrj_id', $setkrj_id)->delete([]);
        if ($delete) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Pekerjaan Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pekerjaan Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }

    static function getDataActStat()
    {
        return SetkrjModel::select(['setkrj_id', 'setkrj_nm',])->orderBy('setkrj_ord', 'asc')->get();
    }

    static function getNama($setkrj_id)
    {
        $Setkrj = SetkrjModel::where('setkrj_id', $setkrj_id)->select(['setkrj_id', 'setkrj_nm',])->orderBy('setkrj_ord', 'asc')->get()->first();
        return $Setkrj->setkrj_nm;
    }

    static function setData($data, $formData)
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                
                $data[$i]['setkrj_actAltT'] = "Aktif";
                $data[$i]['setkrj_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
                $data[$i]['setkrj_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Pengaturan Pekerjaan\", \"".url('setkrj/setAct/0/'.$data[$i]['setkrj_id'])."\", \"".url('setkrj/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
                if ($data[$i]['setkrj_act']=="0") {
                    $data[$i]['setkrj_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setkrj_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Pengaturan Pekerjaan\", \"".url('setkrj/setAct/1/'.$data[$i]['setkrj_id'])."\", \"".url('setkrj/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setkrj_actAltT'] = "Tidak Aktif";                
                }
            }
        }else{
            
            $data['setkrj_actAltT'] = "Aktif";
            $data['setkrj_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
            $data['setkrj_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Pengaturan Pekerjaan\", \"".url('setkrj/setAct/0/'.$data['setkrj_id'])."\", \"".url('setkrj/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
            if ($data['setkrj_act']=="0") {
                $data['setkrj_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['setkrj_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Pengaturan Pekerjaan\", \"".url('setkrj/setAct/1/'.$data['setkrj_id'])."\", \"".url('setkrj/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['setkrj_actAltT'] = "Tidak Aktif";                
            }

        }
        return $data;
    }
}
