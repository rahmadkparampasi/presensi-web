<?php

namespace App\Http\Controllers;

use App\Http\Resources\SetpdResource;
use App\Models\SetpdModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetpdController extends Controller
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

            'WebTitle' => 'PENGATURAN TINGKAT PENDIDIKAN',
            'PageTitle' => 'Pengaturan Tingkat Pendidikan',
            'BasePage' => 'setpd',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setpdAddData';
        $this->data['UrlForm'] = 'setpd';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        
        if ($request->ajax()) {
            return SetpdController::loadData($this->data['Pgn'], $this->data);
        }

        return view('setpd.index', $this->data);
    }

    public function load(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setpdAddData';
        $this->data['UrlForm'] = 'setpd';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('setpd.data', $this->data);
    }

    static function loadData($Pgn, $formData)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $Setpd = SetpdModel::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'setpd_id', 'setpd_nm', 'setpd_act'])->orderBy('setpd_ord', 'desc')->get();
        $Setpd = SetpdController::setData($Setpd, $formData);
        return datatables()->of($Setpd)->addColumn('aksiStatus', function ($Setpd) use ($Pgn) {
            $button = '';
            $button .= $Setpd->setpd_actAltBu;
            return $button;
        })->addColumn('aksiEdit', function ($Setpd) use ($Pgn, $formData) {
            $button = '';
            $button .= '<button type="button" class="btn btn-warning mx-1" onclick="showForm(\''.$formData['IdForm'].'card\', \'block\'); cActForm(\''.$formData['IdForm'].'\', \''.route('setpd.update').'\'); addFill(\'setpd_id\', \''.$Setpd->setpd_id.'\'); addFill(\'setpd_nm\', \''.$Setpd->setpd_nm.'\');"><i class="fas fa-pen"></i></button>';
                
            $button .= '<button type="button" class="btn btn-danger mx-1" onclick="callOtherTWLoad(\'Menghapus Data Pengaturan Pendidikan\',\''.url('setpd/delete/'.$Setpd->setpd_id).'\', \''.url('setpd/load').'\', \''.$formData['IdForm'].'\', \''.$formData['IdForm'].'data\', \''.$formData['IdForm'].'card\')"><i class="fas fa-trash"></i></button>';
            
            return $button;
        })
        ->rawColumns(['aksiStatus', 'aksiEdit'])->make(true);
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setpd_nm' => 'required',
        ];
        $attributes = [
            'setpd_nm' => 'Nama Pendidikan',
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
            $SetpdModel = new SetpdModel();
            
            $SetpdModel->setpd_nm = addslashes($request->setpd_nm);
            $save = $SetpdModel->save();
            if ($save) {
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Tingkat Pendidikan Berhasil Disimpan"
                ];
            }else{
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Tingkat Pendidikan Tidak Dapat Disimpan'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setpd_nm' => 'required',
        ];
        $attributes = [
            'setpd_nm' => 'Nama Tingkat Pendidikan',
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
                $update = DB::table('setpd')->where('setpd_id', $request->setpd_id)->update([
                    'setpd_nm' => addslashes($request->setpd_nm),
                    'setpd_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Tingkat Pendidikan Berhasil Diubah"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Tingkat Pendidikan Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setAct($setpd_act, $setpd_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetpdsModel = new SetpdModel();

        $message = "Dinonaktifkan";
        if ($setpd_act=="1") {
            $message = "Diaktifkan";
        }

        $update = $SetpdsModel::where('setpd_id', $setpd_id)->update([
            'setpd_act' => $setpd_act
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Tingkat Pendidikan Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Tingkat Pendidikan Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($setpd_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetpdModel = new SetpdModel();

        $delete = $SetpdModel::where('setpd_id', $setpd_id)->delete([]);
        if ($delete) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Tingkat Pendidikan Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Tingkat Pendidikan Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }

    static function getNama($setpd_id)
    {
        $Setpd = SetpdModel::where('setpd_id', $setpd_id)->select(['setpd_id', 'setpd_nm',])->orderBy('setpd_ord', 'asc')->get()->first();
        return $Setpd->setpd_nm;
    }

    static function getDataActStat()
    {
        return SetpdModel::where('setpd_act', '1')->select(['setpd_id', 'setpd_nm',])->orderBy('setpd_ord', 'asc')->get();
    }

    static function getAPI()
    {
        $Setpd = SetpdModel::where('setpd_act', '1')->select(['setpd_id', 'setpd_nm',])->orderBy('setpd_ord', 'asc')->get();
        return SetpdResource::collection($Setpd);
    }

    static function setData($data, $formData)
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                
                $data[$i]['setpd_actAltT'] = "Aktif";
                $data[$i]['setpd_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
                $data[$i]['setpd_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Pengaturan Pendidikan\", \"".url('setpd/setAct/0/'.$data[$i]['setpd_id'])."\", \"".url('setpd/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
                if ($data[$i]['setpd_act']=="0") {
                    $data[$i]['setpd_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setpd_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Pengaturan Pendidikan\", \"".url('setpd/setAct/1/'.$data[$i]['setpd_id'])."\", \"".url('setpd/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setpd_actAltT'] = "Tidak Aktif";                
                }
            }
        }else{
            
            $data['setpd_actAltT'] = "Aktif";
            $data['setpd_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
            $data['setpd_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Pengaturan Pendidikan\", \"".url('setpd/setAct/0/'.$data['setpd_id'])."\", \"".url('setpd/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
            if ($data['setpd_act']=="0") {
                $data['setpd_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['setpd_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Pengaturan Pendidikan\", \"".url('setpd/setAct/1/'.$data['setpd_id'])."\", \"".url('setpd/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['setpd_actAltT'] = "Tidak Aktif";                
            }

        }
        return $data;
    }
}
