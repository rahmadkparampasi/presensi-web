<?php

namespace App\Http\Controllers;

use App\Models\AIModel;
use App\Models\LapModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class LapController extends Controller
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

            'WebTitle' => 'LAPORAN',
            'PageTitle' => 'Laporan',
            'BasePage' => 'lap',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'lapAddData';
        $this->data['UrlForm'] = 'lap';

        if ($this->data['Pgn']->users_tipe=="M") {
            return redirect()->intended();
        }

        if ($request->ajax()) {
            return LapController::loadData($this->data['Pgn'], $this->data, 0, '');
        }
        $this->data['url'] = route('lap.index');

        return view('lap.index', $this->data);
    }

    public function disetujui(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'lapAddData';
        $this->data['UrlForm'] = 'lap';

        if ($this->data['Pgn']->users_tipe=="M") {
            return redirect()->intended();
        }

        if ($request->ajax()) {
            return LapController::loadData($this->data['Pgn'], $this->data, 0, '', '1');
        }
        
        $this->data['url'] = route('lap.approved');

        return view('lap.index', $this->data);
    }

    public function load($stj)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'lapAddData';
        $this->data['UrlForm'] = 'lap';

        if ($stj=='0') {
            $this->data['url'] = route('lap.index');
        }elseif ($stj=='1') {
            $this->data['url'] = route('lap.approved');
        }

        return view('lap.data', $this->data);
    }

    public function detailProfil(Request $request, $lap_sisp)
    {
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'lapAddData';
        $this->data['UrlForm'] = 'lap';
        $this->data['urlLoad'] = route('lap.loadProfil', [$lap_sisp]);

        $this->data['Sisp'] = DB::table('sisp')->where('sisp_id', $lap_sisp)->select(['sisp_id', 'sisp_idsp', 'sisp_nm'])->get()->first();

        $this->data['Pgn'] = $this->getUser();
        $this->data['lap_sisp'] = $lap_sisp;
        if ($request->ajax()) {
            if($request->get('jns')=='loadDataProfil'){
                return LapController::loadData($this->data['Pgn'], $this->data, 0, $lap_sisp, '');
            }
        }

        return view('lap.profil', $this->data);
    }

    public function loadProfil($sispi_sisp)
    {
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'lapAddData';
        $this->data['UrlForm'] = 'lap';

        $this->data['lap_sisp'] = $sispi_sisp;
        $this->data['Pgn'] = $this->getUser();
        return view('lap.dataProfil', $this->data);
    }

    public function detailKoo(Request $request, $id)
    {
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagformPpkAddData';
        $this->data['UrlForm'] = 'bagformPpk';

        $this->data['Bagk'] = DB::table('bagk')->join('bag', 'bagk.bagk_bag', '=', 'bag.bag_id')->where('bagk_id', $id)->select(['bagk_bag', 'bag_nm', 'bagk_satker', 'bagk_id', 'bag_id'])->get()->first();

        $this->data['Namakoo'] = $this->data['Bagk']->bag_nm;

        $this->data['url'] = route('lap.lapKoo', [$id]);
        $this->data['urlLoad'] = route('lap.lapKoo', [$id]);
        if ($request->ajax()) {
            if($request->get('jns')=='loadDataKoo'){
                return LapController::loadData($this->data['Pgn'], $this->data, 0, $this->data['Bagk']->bag_id, $this->data['Bagk']->bagk_satker, true);
            }
        }

        $this->data['Agent'] = new Agent;

        return view('lap.data', $this->data);
    }

    static function loadData($Pgn, $formData, $limit = 0, $id = '', $tipe = '', $koo = false, $dt = true)
    {
        if (!$koo) {
            // $formData['urlLoad'] = route('lap.lapKoo', [$id]);
            if ($tipe == '') {
                $formData['urlLoad'] = route('lap.loadProfil', [$id]);
            }else{
                $formData['urlLoad'] = route('lap.load', [$tipe]);
            }
        }else{
        }

        DB::statement(DB::raw('set @rownum=0'));
        $Lap = DB::table('lap')->leftJoin('sisp', 'lap.lap_sisp', '=', 'sisp.sisp_id')->join('bag', 'sisp.sisp_bag', '=', 'bag.bag_id');

        if ($koo) {
            if ($tipe=='1') {
                $Lap = $Lap->where('bag_prnt', $id);
            }else {
                $Lap = $Lap->where('sisp_bag', $id);
            }
        }else{
            if ($id=='') {
                if ($tipe=='') {
                    $Lap = $Lap->where('lap_nl', '0');
                }else{
                    $Lap = $Lap->where('lap_nl', '!=', '0');
                }
            }
            if ($id!='') {
                $Lap = $Lap->where('lap_sisp', $id);
            }

        }
        $Lap = $Lap->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'lap_id', 'lap_sisp', 'lap_fl', 'lap_nl', 'lap_ket', 'lap_bln', 'lap_thn', 'sisp_nm'])->orderBy('lap_thn', 'desc')->orderBy('lap_bln', 'desc');
        if ($limit!=0) {
            $Lap = $Lap->limit($limit)->get();
        }else{
            $Lap = $Lap->get();
        }

        $Lap = LapController::setData($Lap, $formData);

        if ($dt) {
            return datatables()->of($Lap)->addColumn('dataBln', function($Lap){
                
                return 'Bulan:'.$Lap->lap_bln.'<br/>Tahun:'.$Lap->lap_thn;
            })->addColumn('aksiStatus', function ($Lap) use ($Pgn) {
                $button = '';
                
                if ((int)$Lap->lap_nl==0) {
                    $button .= $Lap->lap_nlAltT;
                }else{
                    $button .= $Lap->lap_nlAltBa;
                }
                return $button;
            })->addColumn('aksiKomen', function ($Lap) use ($Pgn) {
                $button = '';
                
                if ($Lap->lap_ket!='') {
                    $button .= "<span onclick='$(\"#modalViewLblText\").html(\"".$Lap->lap_ket."\")' data-toggle='modal' data-target='#modalViewLbl' role='button' class='btn btn-success font-weight-bold'><i class=\"fa fa-eye\"></i> LIHAT</span>";
                }
                return $button;
            })->addColumn('aksiFile', function ($Lap) use ($Pgn) {
                $button = '';
                $button .= '<button type="button" class="btn btn-info" onclick="changeUrl(\''.asset('storage/uploads/'.$Lap->lap_fl).'\', \'Pratinjau Surat Izin\');" data-target="#modalViewPdf" data-toggle="modal"><i class="fas fa-eye"></i></button>';
                return $button;
            })->addColumn('aksiHapus', function ($Lap) use ($Pgn, $formData, $id, $koo) {
                $button = '';
                if ($koo) {
                    $button .= "<span onclick='addFill(\"lap_id\", \"".$Lap->lap_id."\"); $(\"#modalNilaiForm\").attr(\"data-urlload\", \"".$formData['urlLoad']."\"); $(\"#modalNilaiForm\").attr(\"data-div\", \"bagkLaporanKooTab\");' data-toggle='modal' data-target='#modalNilai' role='button' class='btn btn-primary font-weight-bold mx-1'><i class=\"fa fa-star\"></i> NILAI</span>";
                }else{
                    if ($id=='') {
                        $button .= "<span onclick='addFill(\"lap_id\", \"".$Lap->lap_id."\")' data-toggle='modal' data-target='#modalNilai' role='button' class='btn btn-primary font-weight-bold mx-1'><i class=\"fa fa-star\"></i> NILAI</span><br/>";
                    }else{
                        $button .= "<span onclick='addFill(\"lap_id_c\", \"".$Lap->lap_id."\"); addFill(\"lap_bln_c\", \"".$Lap->lap_bln."\"); addFill(\"lap_thn_c\", \"".$Lap->lap_thn."\")' data-toggle='modal' data-target='#lapUpdateDataModal' role='button' class='btn btn-warning font-weight-bold mx-1'><i class=\"fa fa-pen\"></i> UBAH LAPORAN</span><br/>";
                    }
                    if ((int)$Lap->lap_nl==0) {
                        $button .= "<span onclick='callOtherTWLoad(\"Menghapus Data Laporan, Bulan: ".$Lap->lap_bln." Tahun: ".$Lap->lap_thn."\", \"".url('lap/delete/'.$Lap->lap_id)."\", \"".$formData['urlLoad']."\", \"\", \"".$formData['IdForm']."data\", \"\", \"\")' role='button' class='btn btn-danger font-weight-bold mx-1'><i class=\"fa fa-trash\"></i> HAPUS</span>";
                    }
                }
                return $button;
            })->rawColumns(['dataBln', 'aksiFile', 'aksiHapus', 'aksiStatus', 'aksiKomen'])->setTotalRecords($limit)->make(true);
        }else{
            return $Lap;
        }

    }

    static function setData($data, $formData = [])
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                $data[$i]->lap_nlAltT = "Belum Dinilai";
                $data[$i]->lap_nlAltBa = "<span class='badge badge-secondary font-weight-bold'>Belum Dinilai</span>";

                if ((int)$data[$i]->lap_nl>0) {
                    $data[$i]->lap_nlAltT = (String)$data[$i]->lap_nl." Bintang";
                    $data[$i]->lap_nlAltBa = "<div class='d-flex justify-content-between align-items-center'><div class='ratings'>";
                    for ($k=0; $k < ((int)$data[$i]->lap_nl); $k++) { 
                        $data[$i]->lap_nlAltBa .= "<i class='fa fa-star rating-color'></i>";
                    }
                    for ($k=0; $k < (5-((int)$data[$i]->lap_nl)); $k++) { 
                        $data[$i]->lap_nlAltBa .= "<i class='fa fa-star'></i>";
                    }
                    $data[$i]->lap_nlAltBa .= "</div></div>";
                }

                if (isset($data[$i]->lap_ket)) {
                    if ($data[$i]->lap_ket!='') {
                        $data[$i]->lap_ket = stripslashes($data[$i]->lap_ket);
                    }
                }
                if (isset($data[$i]->lap_bln)) {
                    if ((string)$data[$i]->lap_bln!='0') {
                        $data[$i]->lap_bln = ucwords(AIModel::monthConvIntSt((int)$data[$i]->lap_bln));
                    }
                }
            }
        }else{
            

        }
        return $data;
    }

    public function insertDataProfil(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');

        $rules = [
            'lap_bln' => 'required',
            'lap_thn' => 'required',
            'lap_fl' => 'required|mimes:pdf|max:10000',
        ];
        $attributes = [
            'lap_bln' => 'Bulan Laporan',
            'lap_thn' => 'Tahun Laporan',
            'lap_fl' => 'Berkas Laporan',
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
            $Lap = DB::table('lap')->where('lap_sisp', $request->lap_sisp)->where('lap_bln', $request->lap_bln)->where('lap_thn', $request->lap_thn)->get()->first();
            if ($Lap!=null) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Laporan Tidak Dapat Disimpan, Sudah Ada Laporan Yang Sama'];
            }else{
                $LapModel = new LapModel();
                
                $LapModel->lap_sisp = $request->lap_sisp;
                $LapModel->lap_bln = $request->lap_bln;
                $LapModel->lap_thn = $request->lap_thn;
    
                $guessExtension = $request->file('lap_fl')->guessExtension();
                $filename = 'file-lap-'.date("Y-m-d-H-i-s").".".$guessExtension;
    
                $LapModel->lap_fl = $filename;
                
                $save = $LapModel->save();
                if ($save) {            
                    $file = $request->file('lap_fl')->storeAs('/public/uploads', $filename );
                    if ($file) {
                        $data['response'] = [
                            'status' => 200,
                            'response' => "success",
                            'type' => "success",
                            'message' => "Data Laporan Berhasil Disimpan"];
                    }else{
                        $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Laporan Tidak Dapat Disimpan, Terdapat Masalah Pada Berkas Surat Laporan'];
                    }
                }else{
                    $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Laporan Tidak Dapat Disimpan'];
                }
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateDataProfil(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');

        $rules = [
            'lap_id' => 'required',
            'lap_fl' => 'required|mimes:pdf|max:1000',
        ];
        $attributes = [
            'lap_id' => 'ID Laporan',
            'lap_fl' => 'Berkas Laporan',
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

            $guessExtension = $request->file('lap_fl')->guessExtension();
            $filename = 'file-lap-'.date("Y-m-d-H-i-s").".".$guessExtension;

            try {
                $update = DB::table('lap')->where('lap_id', $request->lap_id)->update([
                    'lap_c' => addslashes($request->lap_c),
                    'lap_fl' => $filename,
                    'lap_uupdate' => $this->data['Pgn']->users_id
                ]);
                $file = $request->file('lap_fl')->storeAs('/public/uploads', $filename );

                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Perubahan Laporan Berhasil Disimpan"
                ];
            } catch (\Throwable $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Perubahan Laporan Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');

        $rules = [
            'lap_nl' => 'required',
            'lap_ket' => 'required',
        ];
        $attributes = [
            'lap_nl' => 'Nilai Laporan',
            'lap_ket' => 'Komentar Laporan',
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
                $update = DB::table('lap')->where('lap_id', $request->lap_id)->update([
                    'lap_nl' => $request->lap_nl,
                    'lap_ket' => addslashes($request->lap_ket),
                    'lap_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Laporan Berhasil Dinilai"
                ];
            } catch (\Throwable $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Laporan Tidak Dapat Dinilai, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);

    }

    public function deleteData($lap_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $LapModel = new LapModel();

        $Lap = DB::table('lap')->where('lap_id', $lap_id)->select(['lap_fl'])->get()->first();
        $delete = $LapModel::where('lap_id', $lap_id)->delete([]);
        if ($delete) {
            Storage::delete('/public/uploads/'.$Lap->lap_fl);
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Laporan Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Laporan Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }
}
