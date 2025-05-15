<?php

namespace App\Http\Controllers;

use App\Models\AIModel;
use App\Models\SurveiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class SurveiController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOIzin',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'SURVEI',
            'PageTitle' => 'Survei',
            'BasePage' => 'survei',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'surveiAddData';
        $this->data['UrlForm'] = 'survei';

        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        $this->data['act'] = '1';
        $this->data['url'] = route('survei.index');
        $this->data['urlLoad'] = route('survei.load');

        if ($request->ajax()) {
            return SurveiController::loadData($this->data, 0, $this->data['act']);
        }
        $this->data['url'] = route('survei.index');

        return view('survei.index', $this->data);
    }

    public function load($act = '')
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'surveiAddData';
        $this->data['UrlForm'] = 'survei';

        if ($act=='') {
            $this->data['act'] = '1';
            $this->data['url'] = route('survei.index');

        }

        return view('survei.data', $this->data);
    }

    public function detail(Request $request, $id){
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'surveiAddData';
        $this->data['UrlForm'] = 'survei';

        $this->data['Agent'] = new Agent;

        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        $SurveiQ = new SurveiqController;
        $this->data['Surveiq'] = $SurveiQ->detailSurvei($id);

        // $this->data['url'] = route('survei.index');
        // $this->data['urlLoad'] = route('survei.load');

        if ($request->ajax()) {
            // return SurveiController::loadData($this->data, 0, $this->data['act']);
        }

        $this->data['Survei'] = SurveiController::setData(DB::table('survei')->where('survei_id', $id)->select(['survei_id', 'survei_thn', 'survei_kuis', 'survei_act'])->get()->first(), $this->data);
        $this->data['url'] = route('survei.index');

        return view('survei.detail', $this->data);
    }

    public function showFormSurvei($idsurvei, $sisp)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'surveiAddData';
        $this->data['UrlForm'] = 'survei';
        $this->data['sisp'] = $sisp;

        $this->data['Agent'] = new Agent;

        $this->data['Survei'] = SurveisController::getSurveis($idsurvei);

        return view('survei.modalDetailSurvei', $this->data);

    }

    public function showFormSurveiA($idsurvei, $sisp)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'surveiAddData';
        $this->data['UrlForm'] = 'survei';
        $this->data['sisp'] = $sisp;

        $this->data['Agent'] = new Agent;

        $this->data['Survei'] = SurveisController::getSurveisa($idsurvei, $sisp);

        return view('surveis.modalAnswer', $this->data);

    }

    static function loadData($formData, $limit = 0, $tipe = '', $sisp ='', $dt = true)
    {
        // if ($tipe == '1') {
        //     if ($sisp!='') {
        //         // $formData['urlLoad'] = route('survei.loadProfil', [$sisp]);
        //     }else{
        //     }
        //     $formData['urlLoad'] = route('survei.load', [$tipe]);
        //     // $formData['urlLoad'] = '';
        // }else{
        // }
        $formData['urlLoad'] = route('survei.load', [$tipe]);

        $now = date("Y-m-d");

        

        DB::statement(DB::raw('set @rownum=0'));
        $Survei = DB::table('survei');
        // if ($tipe=='1') {
        //     $Survei = $Survei->where('survei_tglm', '<=', $now);
        //     $Survei = $Survei->where('survei_tgls', '>=', $now);
        // }else{
        //     $Survei = $Survei->where('survei_tgls', '<', $now);
        // }
        // if ($lap_sisp=='') {
        // }
        // if ($lap_sisp!='') {
        //     $Lap = $Lap->where('lap_sisp', $lap_sisp);
        // }
        $Survei = $Survei->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'survei_id', 'survei_thn', 'survei_kuis', 'survei_act'])->orderBy('survei_thn', 'desc');
        if ($limit!=0) {
            $Survei = $Survei->limit($limit)->get();
        }else{
            $Survei = $Survei->get();
        }

        
        if ($dt) {
            $Survei = SurveiController::setData($Survei, $formData);
            return datatables()->of($Survei)->addColumn('dataThn', function($Survei){
                return 'Tahun: '.$Survei->survei_thn;
            })->addColumn('aksiStatus', function ($Survei) {
                $button = '';
                
                $button .= $Survei->survei_actAltBu;
                return $button;
            })->addColumn('aksiDetail', function ($Survei) use ($formData) {
                $button = '';
                
                $button .= "<a href='".route('survei.detail', [$Survei->survei_id])."' class='btn btn-primary font-weight-bold mx-1'><i class=\"fa fa-eye\"></i> </a>";
                
                return $button;
            })->addColumn('aksiHapus', function ($Survei) use ($formData) {
                $button = '';
                
                $button .= "<span onclick='showForm(\"".$formData['IdForm']."card\", \"block\"); cActForm(\"".$formData['IdForm']."\", \"".route('survei.update')."\"); addFill(\"survei_id\", \"".$Survei->survei_id."\");  addFill(\"survei_thn\", \"".$Survei->survei_thn."\"); addFill(\"survei_kuis\", \"".$Survei->survei_kuis."\")' class='btn btn-warning font-weight-bold mx-1'><i class=\"fa fa-pen\"></i> UBAH</span>";
                $button .= "<span onclick='callOtherTWLoad(\"Menghapus Data Survei Tahun: ".$Survei->survei_thn."\", \"".url('survei/delete/'.$Survei->survei_id)."\", \"".$formData['urlLoad']."\", \"\", \"".$formData['IdForm']."data\", \"\", \"\")' role='button' class='btn btn-danger font-weight-bold mx-1'><i class=\"fa fa-trash\"></i> HAPUS</span>";
                
                return $button;
            })->rawColumns(['dataThn', 'aksiHapus', 'aksiStatus', 'aksiDetail'])->setTotalRecords($limit)->make(true);
        }else{
            return $Survei;
        }

    }

    static function setData($data, $formData = [])
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) {
                $data[$i]->survei_actAltT = "Aktif";
                $data[$i]->survei_actAltBa = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
                $data[$i]->survei_actAltBu = "<span onclick='callOtherTWLoad(\"Menonaktifkan Survei\", \"".url('survei/setAct/0/'.$data[$i]->survei_id)."\", \"".url('survei/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
                if ($data[$i]->survei_act=="0") {
                    $data[$i]->survei_actAltBa = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]->survei_actAltBu = "<span onclick='callOtherTWLoad(\"Mengaktifkan Survei\", \"".url('survei/setAct/1/'.$data[$i]->survei_id)."\", \"".url('survei/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]->survei_actAltT = "Tidak Aktif";                
                }

                $data[$i]->survei_kuisAltT = "Bukan Kuis";
                if ($data[$i]->survei_kuis=="1") {
                    $data[$i]->survei_kuisAltT = "Kuis";                
                }
            }
        }else{
            $data->survei_actAltT = "Aktif";
            $data->survei_actAltBa = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
            $data->survei_actAltBu = "<span onclick='callOtherTWLoad(\"Menonaktifkan Survei\", \"".url('survei/setAct/0/'.$data->survei_id)."\", \"".url('survei/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
            if ($data->survei_act=="0") {
                $data->survei_actAltBa = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data->survei_actAltBu = "<span onclick='callOtherTWLoad(\"Mengaktifkan Survei\", \"".url('survei/setAct/1/'.$data->survei_id)."\", \"".url('survei/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                $data->survei_actAltT = "Tidak Aktif";                
            }

            $data->survei_kuisAltT = "Bukan Kuis";
            if ($data->survei_kuis=="1") {
                $data->survei_kuisAltT = "Kuis";                
            }
        }
        return $data;
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'survei_thn' => 'required',
            'survei_kuis' => 'required',
            
        ];
        $attributes = [
            'survei_thn' => 'Tahun Survei',
            'survei_kuis' => 'Kuis Survei',
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
            $SurveiModel = new SurveiModel();
            
            $SurveiModel->survei_thn = $request->survei_thn;
            $SurveiModel->survei_kuis = $request->survei_kuis;
            $save = $SurveiModel->save();
            if ($save) {
                
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Survei Berhasil Disimpan"
                ];
            }else{
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Survei Tidak Dapat Disimpan'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'survei_thn' => 'required',
            'survei_kuis' => 'required',
            
        ];
        $attributes = [
            'survei_thn' => 'Tahun Survei',
            'survei_kuis' => 'Kuis Survei',
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
                $update = DB::table('survei')->where('survei_id', $request->survei_id)->update([
                    'survei_thn' => $request->survei_thn,
                    'survei_kuis' => $request->survei_kuis,
                    'survei_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Survei Berhasil Diubah"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Survei Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($survei_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SurveiModel = new SurveiModel();

        
        $delete = $SurveiModel::where('survei_id', $survei_id)->delete([]);
        if ($delete) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Survei Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Survei Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }
}
