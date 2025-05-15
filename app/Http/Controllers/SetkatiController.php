<?php

namespace App\Http\Controllers;

use App\Models\SetkatiModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetkatiController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOSetkati',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'PENGATURAN KATEGORI IZIN',
            'PageTitle' => 'Pengaturan Kategori Izin',
            'BasePage' => 'setkati',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setkatiAddData';
        $this->data['UrlForm'] = 'setkati';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        
        if ($request->ajax()) {
            return SetkatiController::loadData($this->data['Pgn'], $this->data);
        }

        return view('setkati.index', $this->data);
    }

    public function load(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setkatiAddData';
        $this->data['UrlForm'] = 'setkati';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('setkati.data', $this->data);
    }

    static function loadData($Pgn, $formData)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $Setkati = SetkatiModel::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'setkati_id', 'setkati_nm', 'setkati_kd', 'setkati_act'])->orderBy('setkati_ord', 'desc')->get();
        $Setkati = SetkatiController::setData($Setkati, $formData);
        return datatables()->of($Setkati)->addColumn('aksiStatus', function ($Setkati) use ($Pgn) {
            $button = '';
            $button .= $Setkati->setkati_actAltBu;
            return $button;
        })->addColumn('aksiEdit', function ($Setkati) use ($Pgn, $formData) {
            $button = '';
            $button .= '<button type="button" class="btn btn-warning mx-1" onclick="showForm(\''.$formData['IdForm'].'card\', \'block\'); cActForm(\''.$formData['IdForm'].'\', \''.route('setkati.update').'\'); addFill(\'setkati_id\', \''.$Setkati->setkati_id.'\'); addFill(\'setkati_nm\', \''.$Setkati->setkati_nm.'\'); addFill(\'setkati_kd\', \''.$Setkati->setkati_kd.'\');"><i class="fas fa-pen"></i></button>';
                
            $button .= '<button type="button" class="btn btn-danger mx-1" onclick="callOtherTWLoad(\'Menghapus Data Pengaturan Pendidikan\',\''.url('setkati/delete/'.$Setkati->setkati_id).'\', \''.url('setkati/load').'\', \''.$formData['IdForm'].'\', \''.$formData['IdForm'].'data\', \''.$formData['IdForm'].'card\')"><i class="fas fa-trash"></i></button>';
            
            return $button;
        })
        ->rawColumns(['aksiStatus', 'aksiEdit'])->make(true);
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setkati_nm' => 'required',
            'setkati_kd' => 'required|string|min:2|max:2|unique:setkati,setkati_kd,'.$request->setkati_kd.',setkati_kd',
        ];
        $attributes = [
            'setkati_nm' => 'Nama Kategori Izin',
            'setkati_kd' => 'Kode Kategori Izin',
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
            $SetkatiModel = new SetkatiModel();
            
            $SetkatiModel->setkati_nm = addslashes($request->setkati_nm);
            $SetkatiModel->setkati_kd = $request->setkati_kd;
            $save = $SetkatiModel->save();
            if ($save) {
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Kategori Izin Berhasil Disimpan"
                ];
            }else{
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Izin Tidak Dapat Disimpan'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setkati_nm' => 'required',
            'setkati_kd' => 'required|string|min:2|max:2|unique:setkati,setkati_kd,'.$request->setkati_kd.',setkati_kd',
        ];
        $attributes = [
            'setkati_nm' => 'Nama Kategori Izin',
            'setkati_kd' => 'Kode Kategori Izin',
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
                $update = DB::table('setkati')->where('setkati_id', $request->setkati_id)->update([
                    'setkati_nm' => addslashes($request->setkati_nm),
                    'setkati_kd' => $request->setkati_kd,
                    'setkati_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Kategori Izin Berhasil Diubah"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Izin Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setAct($setkati_act, $setkati_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetkatisModel = new SetkatiModel();

        $message = "Dinonaktifkan";
        if ($setkati_act=="1") {
            $message = "Diaktifkan";
        }

        $update = $SetkatisModel::where('setkati_id', $setkati_id)->update([
            'setkati_act' => $setkati_act
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Izin Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Izin Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($setkati_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetkatiModel = new SetkatiModel();

        $delete = $SetkatiModel::where('setkati_id', $setkati_id)->delete([]);
        if ($delete) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Izin Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Izin Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }

    static function getDataActStat()
    {
        return SetkatiModel::select(['setkati_id', 'setkati_nm', 'setkati_kd'])->orderBy('setkati_ord', 'asc')->get();
    }

    static function getNama($setkati_id)
    {
        $Setkati = SetkatiModel::where('setkati_id', $setkati_id)->select(['setkati_id', 'setkati_nm',])->orderBy('setkati_ord', 'asc')->get()->first();
        return $Setkati->setkati_nm;
    }

    static function getKode($setkati_id)
    {
        $Setkati = SetkatiModel::where('setkati_id', $setkati_id)->select(['setkati_id', 'setkati_kd',])->orderBy('setkati_ord', 'asc')->get()->first();
        return $Setkati->setkati_kd;
    }

    static function setData($data, $formData)
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                
                $data[$i]['setkati_actAltT'] = "Aktif";
                $data[$i]['setkati_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
                $data[$i]['setkati_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Pengaturan Kategori Izin\", \"".url('setkati/setAct/0/'.$data[$i]['setkati_id'])."\", \"".url('setkati/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
                if ($data[$i]['setkati_act']=="0") {
                    $data[$i]['setkati_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setkati_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Pengaturan Kategori Izin\", \"".url('setkati/setAct/1/'.$data[$i]['setkati_id'])."\", \"".url('setkati/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setkati_actAltT'] = "Tidak Aktif";                
                }
            }
        }else{
            
            $data['setkati_actAltT'] = "Aktif";
            $data['setkati_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
            $data['setkati_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Pengaturan Kategori Izin\", \"".url('setkati/setAct/0/'.$data['setkati_id'])."\", \"".url('setkati/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
            if ($data['setkati_act']=="0") {
                $data['setkati_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['setkati_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Pengaturan Kategori Izin\", \"".url('setkati/setAct/1/'.$data['setkati_id'])."\", \"".url('setkati/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data['setkati_actAltT'] = "Tidak Aktif";                
            }

        }
        return $data;
    }
}
