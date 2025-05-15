<?php

namespace App\Http\Controllers;

use App\Models\AIModel;
use App\Models\SispiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SispiController extends Controller
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

            'WebTitle' => 'IZIN',
            'PageTitle' => 'Izin',
            'BasePage' => 'izin',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'sispiAddData';
        $this->data['UrlForm'] = 'sispi';

        if ($this->data['Pgn']->users_tipe=="M") {
            return redirect()->intended();
        }

        if ($request->ajax()) {
            return SispiController::loadData($this->data['Pgn'], $this->data, 0, '', '0');
        }
        $this->data['Setkati'] = SetkatiController::getDataActStat();
        $this->data['url'] = route('sispi.index');

        return view('sispi.index', $this->data);
    }

    public function disetujui(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'sispiAddData';
        $this->data['UrlForm'] = 'sispi';

        if ($this->data['Pgn']->users_tipe=="M") {
            return redirect()->intended();
        }

        if ($request->ajax()) {
            return SispiController::loadData($this->data['Pgn'], $this->data, 0, '', '1');
        }
        $this->data['Setkati'] = SetkatiController::getDataActStat();
        $this->data['url'] = route('sispi.approved');

        return view('sispi.index', $this->data);
    }

    public function ditolak(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'sispiAddData';
        $this->data['UrlForm'] = 'sispi';

        if ($this->data['Pgn']->users_tipe=="M") {
            return redirect()->intended();
        }

        if ($request->ajax()) {
            return SispiController::loadData($this->data['Pgn'], $this->data, 0, '', '2');
        }
        $this->data['Setkati'] = SetkatiController::getDataActStat();
        $this->data['url'] = route('sispi.rejected');

        return view('sispi.index', $this->data);
    }

    public function lewat(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'sispiAddData';
        $this->data['UrlForm'] = 'sispi';

        if ($this->data['Pgn']->users_tipe=="M") {
            return redirect()->intended();
        }

        if ($request->ajax()) {
            return SispiController::loadData($this->data['Pgn'], $this->data, 0, '', '3');
        }
        $this->data['Setkati'] = SetkatiController::getDataActStat();
        $this->data['url'] = route('sispi.expired');

        return view('sispi.index', $this->data);
    }

    public function load($stj)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'siswaAddData';
        $this->data['UrlForm'] = 'siswa';

        if ($stj=='0') {
            $this->data['url'] = route('sispi.index');
        }elseif ($stj=='1') {
            $this->data['url'] = route('sispi.approved');
        }elseif ($stj=='2') {
            $this->data['url'] = route('sispi.rejected');
        }elseif ($stj=='3') {
            $this->data['url'] = route('sispi.expired');
        }

        return view('sispi.data', $this->data);
    }

    public function detailProfil(Request $request, $sispi_sisp, $tipe = '')
    {
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'sispiAddData';
        $this->data['UrlForm'] = 'sispi';
        $this->data['urlLoad'] = route('sispi.loadProfil', [$sispi_sisp, $tipe]);

        $this->data['tipe'] = $tipe;
        $this->data['tipeAltT'] = 'Siswa';
        if ($this->data['tipe']=='G'||$this->data['tipe']=='P') {
            $this->data['tipeAltT'] = 'Pegawai';
        }

        $this->data['Setkati'] = SetkatiController::getDataActStat();
        $this->data['Sisp'] = DB::table('sisp')->where('sisp_id', $sispi_sisp)->select(['sisp_id', 'sisp_idsp', 'sisp_nm'])->get()->first();

        $this->data['Pgn'] = $this->getUser();
        $this->data['sispi_sisp'] = $sispi_sisp;
        if ($request->ajax()) {
            if($request->get('jns')=='loadDataProfil'){
                return SispiController::loadData($this->data['Pgn'], $this->data, 0, $sispi_sisp);
            }
        }

        return view('sispi.profil', $this->data);
    }

    public function loadProfil($sispi_sisp, $tipe = '')
    {
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'sispiAddData';
        $this->data['UrlForm'] = 'sispi';

        $this->data['tipe'] = $tipe;
        $this->data['tipeAltT'] = 'Siswa';
        if ($this->data['tipe']=='G'||$this->data['tipe']=='P') {
            $this->data['tipeAltT'] = 'Pegawai';
        }
        $this->data['sispi_sisp'] = $sispi_sisp;
        $this->data['Pgn'] = $this->getUser();
        return view('sispi.dataProfil', $this->data);
    }

    public function loadAjax($sispi_id)
    {
        return DB::table('sispi')->leftJoin('sisp', 'sispi.sispi_sisp', '=', 'sisp.sisp_id')->leftJoin('setkati', 'sispi.sispi_setkati', '=', 'setkati.setkati_id')->where('sispi_id', $sispi_id)->select(['sisp_idsp','sisp_nm','sispi_id', 'sispi_sisp', 'sispi_setkati', 'sispi_tglm', 'sispi_tglms', 'sispi_tgls', 'sispi_tglss', 'sispi_ket', 'sispi_stj', 'sispi_ketstj', 'setkati_nm', 'sisp_idsp', 'sisp_nm', 'sispi_fle', 'sispi_fl', 'sispi_tiket'])->orderBy('sispi_update', 'desc')->orderBy('sispi_ord', 'desc')->get()->first();
    }

    static function loadData($Pgn, $formData, $limit = 0, $sispi_sisp = '', $sispi_stj = '')
    {
        $formData['urlLoad'] = route('sispi.load', [$sispi_stj]);
        if (isset($formData['tipe'])) {
            $formData['urlLoad'] = route('sispi.loadProfil', [$sispi_sisp, $formData['tipe']]);
        }
        DB::statement(DB::raw('set @rownum=0'));
        $Sispi = DB::table('sispi')->leftJoin('sisp', 'sispi.sispi_sisp', '=', 'sisp.sisp_id')->leftJoin('setkati', 'sispi.sispi_setkati', '=', 'setkati.setkati_id');
        if ($sispi_stj!='') {
            if ($sispi_stj!='3') {
                $Sispi = $Sispi->where('sispi_stj', $sispi_stj);
            }
            if ($sispi_stj=='0') {
                $Sispi = $Sispi->where('sispi_tgls','>=',Carbon::now()->format('Y-m-d'));
            }
            if ($sispi_stj=='3') {
                $Sispi = $Sispi->where('sispi_stj', '0');

                $Sispi = $Sispi->where('sispi_tgls','<',Carbon::now()->format('Y-m-d'));
            }
        }
        if ($sispi_sisp!='') {
            $Sispi = $Sispi->where('sispi_sisp', $sispi_sisp);
        }
        $Sispi = $Sispi->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'sispi_id', 'sispi_sisp', 'sispi_setkati', 'sispi_tglm', 'sispi_tglms', 'sispi_tgls', 'sispi_tglss', 'sispi_ket', 'sispi_stj', 'sispi_ketstj', 'setkati_nm', 'sisp_idsp', 'sisp_nm', 'sispi_fle', 'sispi_fl', 'sispi_tiket'])->orderBy('sispi_update', 'desc')->orderBy('sispi_ord', 'desc');
        if ($limit!=0) {
            $Sispi = $Sispi->limit($limit)->get();
        }else{
            $Sispi = $Sispi->get();
        }

        $Sispi = SispiController::setData($Sispi, $formData);

        return datatables()->of($Sispi)->addColumn('dataIDSts', function($Sispi){
            return $Sispi->sispi_tiket.'<br/>'.$Sispi->sispi_stjAltBa;
        })->addColumn('dataTglm', function($Sispi){
            if ($Sispi->sispi_stj!='1') {
                return $Sispi->sispi_tglmAltT;
            }
            return '<ul><li>Ajuan:'.$Sispi->sispi_tglmAltT.'</li><li>Disetujui:'.$Sispi->sispi_tglmsAltT.'</li></ul>';
        })->addColumn('dataNm', function($Sispi){
            
            return 'Nama:'.$Sispi->sisp_nm.'<br/>NISN/NIP:'.$Sispi->sisp_idsp;
        })->addColumn('dataTgls', function($Sispi){
            if ($Sispi->sispi_stj!='1') {
                return $Sispi->sispi_tglsAltT;
            }
            return '<ul><li>Ajuan:'.$Sispi->sispi_tglsAltT.'</li><li>Disetujui:'.$Sispi->sispi_tglssAltT.'</li></ul>';
        })->addColumn('aksiStatus', function ($Sispi) use ($Pgn) {
            $button = '';
            $date_now = date("Y-m-d");
            if ($Pgn->users_tipe=='A') {
                if ($date_now <= $Sispi->sispi_tgls) {
                    $button .= $Sispi->sispi_stjAltBu;
                }
            }else{
                $button .= $Sispi->sispi_stjAltBa;
            }
            return $button;
        })->addColumn('aksiFile', function ($Sispi) use ($Pgn) {
            $button = '';
            if ($Sispi->sispi_fle=='pdf'||$Sispi->sispi_fle=='PDF') {
                $button .= '<button type="button" class="btn btn-info" onclick="changeUrl(\''.asset('storage/uploads/'.$Sispi->sispi_fl).'\', \'Pratinjau Surat Izin\');" data-target="#modalViewPdf" data-toggle="modal"><i class="fas fa-eye"></i></button>';
            }else{
                $button .= '<button type="button" class="btn btn-info" onclick="showPreviewImgSrc(\''.asset('storage/uploads/'.$Sispi->sispi_fl).'\'); $(\'#modalViewImgTitle\').html(\'Pratinjau Surat Izin\')" data-target="#modalViewImg" data-toggle="modal"><i class="fas fa-eye"></i></button>';
            }
            return $button;
        })->addColumn('aksiHapus', function ($Sispi) use ($Pgn, $sispi_stj, $formData) {
            $button = '';
            if ($Sispi->sispi_stj=="0") {
                $date_now = date("Y-m-d");
                if ($date_now <= $Sispi->sispi_tgls) {
                    $button .= "<span onclick='callOtherTWLoad(\"Menghapus Data Izin, Tiket: ".$Sispi->sispi_tiket."\", \"".url('sispi/delete/'.$Sispi->sispi_id)."\", \"".$formData['urlLoad']."\", \"\", \"".$formData['IdForm']."data\", \"\", \"\")' role='button' class='btn btn-danger font-weight-bold'><i class=\"fa fa-trash\"></i> HAPUS</span>";
                }
            }
            return $button;
        })->rawColumns(['aksiStatus', 'dataTglm', 'dataTgls', 'aksiFile', 'dataNm', 'dataIDSts', 'aksiHapus'])->setTotalRecords($limit)->make(true);
    }

    public function insertDataProfil(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');

        $rules = [
            'sisp_nm' => 'required',
            'sispi_sisp' => 'required',
            'sispi_tglm' => 'required',
            'sispi_tgls' => 'required',
            'sispi_setkati' => 'required',
            
            'sispi_fl' => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
        $attributes = [
            'sisp_nm' => 'Nama Peserta',
            'sispi_sisp' => 'Data Peserta',
            'sispi_tglm' => 'Tanggal Mulai Izin',
            'sispi_tgls' => 'Tanggal Selesai Izin',
            'sispi_setkati' => 'Kategori Izin',
            
            'sispi_fl' => 'Berkas Surat Izin',
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
            $tiket = '';
            $nama = explode(' ', $request->sisp_nm);
            if (count($nama)==1) {
                $tiket = substr($request->sisp_nm, 0, 2);
            }else{
                for ($j=0; $j < count($nama); $j++) { 
                    if ($j==2) {
                        break;
                    }
                    $tiket .= substr($nama[$j], 0, 1);
                }
            }
            $tiket = SetkatiController::getKode($request->sispi_setkati).date("ymdHis").$tiket;

            $SispiModel = new SispiModel();
            
            $SispiModel->sispi_tiket = $tiket;
            $SispiModel->sispi_sisp = $request->sispi_sisp;
            $SispiModel->sispi_tglm = $request->sispi_tglm;
            $SispiModel->sispi_tgls = $request->sispi_tgls;
            $SispiModel->sispi_setkati = $request->sispi_setkati;
            $SispiModel->sispi_ket = addslashes($request->sispi_ket);

            $guessExtension = $request->file('sispi_fl')->guessExtension();
            $filename = 'file-izin-'.date("Y-m-d-H-i-s").".".$guessExtension;

            $SispiModel->sispi_fl = $filename;
            $SispiModel->sispi_fle = $guessExtension;
            
            $save = $SispiModel->save();
            if ($save) {            
                $file = $request->file('sispi_fl')->storeAs('/public/uploads', $filename );
                if ($file) {
                    $data['response'] = [
                        'status' => 200,
                        'response' => "success",
                        'type' => "success",
                        'message' => "Data Izin Berhasil Disimpan"];
                }else{
                    $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Izin Tidak Dapat Disimpan, Terdapat Masalah Pada Berkas Surat Izin'];
                }
            }else{
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Izin Tidak Dapat Disimpan'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateDataStj(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');

        $rules = [
            'sispi_id' => 'required',
            'sispi_tglms' => 'required',
            'sispi_tglss' => 'required',
        ];
        $attributes = [
            'sispi_id' => 'Nama Peserta',
            'sispi_tglms' => 'Tanggal Mulai Disetujui',
            'sispi_tglss' => 'Tanggal Selesai Disetujui',
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
                $update = DB::table('sispi')->where('sispi_id', $request->sispi_id)->update([
                    'sispi_tglms' => $request->sispi_tglms,
                    'sispi_tglss' => $request->sispi_tglss,
                    'sispi_stj' => '1',
                    'sispi_ketstj' => addslashes($request->sispi_ketstj),
                    'sispi_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Izin Disetjui Berhasil Disimpan"
                ];
            } catch (\Throwable $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Izin Disetjui Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);

    }

    public function updateDataTlk(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');

        $rules = [
            'sispi_id' => 'required',
            'sispi_ketstj' => 'required',
        ];
        $attributes = [
            'sispi_id' => 'Nama Peserta',
            'sispi_ketstj' => 'Keterangan Tidak Disetujui',
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
                $update = DB::table('sispi')->where('sispi_id', $request->sispi_id)->update([
                    'sispi_stj' => '2',
                    'sispi_ketstj' => addslashes($request->sispi_ketstj),
                    'sispi_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Izin Disetjui Berhasil Disimpan"
                ];
            } catch (\Throwable $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Izin Disetjui Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);

    }

    static function setData($data, $formData = [])
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                $data[$i]->sispi_stjAltT = "Belum Diproses";
                $data[$i]->sispi_stjAltBa = "<span class='badge badge-secondary font-weight-bold'>Belum Diproses</span>";
                $data[$i]->sispi_stjAltBu = "<span onclick='callModalRefSispi(\"".$data[$i]->sispi_id."\"); $(\"#sispiAddDataModalForm\").attr(\"data-urlload\", \"".$formData['urlLoad']."\")' role='button' class='btn btn-secondary font-weight-bold'>Belum Diproses</span>";

                if ($data[$i]->sispi_stj=='1') {
                    $data[$i]->sispi_stjAltT = "Disetujui";
                    $data[$i]->sispi_stjAltBa = "<span class='badge badge-success font-weight-bold'>Disetujui</span>";
                    $data[$i]->sispi_stjAltBu = "<span onclick='callModalRefSispi(\"".$data[$i]->sispi_id."\"); $(\"#sispiAddDataModalForm\").attr(\"data-urlload\", \"".$formData['urlLoad']."\")' role='button' class='btn btn-success font-weight-bold'>Disetujui</span>";
                }elseif ($data[$i]->sispi_stj=='2') {
                    $data[$i]->sispi_stjAltT = "Ditolak";
                    $data[$i]->sispi_stjAltBa = "<span class='badge badge-danger font-weight-bold'>Ditolak</span>";
                    $data[$i]->sispi_stjAltBu = "<span onclick='callModalRefSispi(\"".$data[$i]->sispi_id."\"); $(\"#sispiAddDataModalForm\").attr(\"data-urlload\", \"".$formData['urlLoad']."\")' role='button' class='btn btn-danger font-weight-bold'>Ditolak</span>";
                }

                $data[$i]->sispi_tglmAltT = "";
                if ($data[$i]->sispi_tglm!='0000-00-00') {
                    $data[$i]->sispi_tglmAltT = ucwords(strtolower(AIModel::changeDateNFSt($data[$i]->sispi_tglm)));
                }

                $data[$i]->sispi_tglmsAltT = "";
                if ($data[$i]->sispi_tglms!='0000-00-00') {
                    $data[$i]->sispi_tglmsAltT = ucwords(strtolower(AIModel::changeDateNFSt($data[$i]->sispi_tglms)));
                }

                $data[$i]->sispi_tglsAltT = "";
                if ($data[$i]->sispi_tgls!='0000-00-00') {
                    $data[$i]->sispi_tglsAltT = ucwords(strtolower(AIModel::changeDateNFSt($data[$i]->sispi_tgls)));
                }

                $data[$i]->sispi_tglssAltT = "";
                if ($data[$i]->sispi_tglss!='0000-00-00') {
                    $data[$i]->sispi_tglssAltT = ucwords(strtolower(AIModel::changeDateNFSt($data[$i]->sispi_tglss)));
                }
            }
        }else{
            if (isset($data->sispi_stj)){
                $data['sispi_stjAltT'] = "Belum Diproses";
                if ($data['sispi_stj']=='1') {
                    $data['sispi_stjAltT'] = "Disetujui";
                }elseif ($data['sispi_stj']=='2') {
                    $data['sispi_stjAltT'] = "Ditolak";
                }
            }

            $data->users_actAltT = "Aktif";
            $data->users_actAltBa = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
            $data->users_actAltBu = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Pengguna\", \"".url('user/setAct/0/'.$data->users_id)."\", \"".route('user.detailProfil', [$data->users_sisp, $formData])."\", \"\", \"userDetailProfil\", \"\", \"\", \"Nonaktfikan\", \"#6c757d\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
            if ($data->users_act=="0") {
                $data->users_actAltBa = "<span class='badge badge-secondary font-weight-bold'>TIDAK AKTIF</span>";

                $data->users_actAltBu = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Pengguna\", \"".url('user/setAct/1/'.$data->users_id)."\", \"".route('user.detailProfil', [$data->users_sisp, $formData])."\", \"\", \"userDetailProfil\", \"\", \"\", \"Aktifkan\")' role='button' class='btn btn-secondary font-weight-bold'>TIDAK AKTIF</span>";

                $data->users_actAltT = "Tidak Aktif";                
            }

        }
        return $data;
    }

    public function deleteData($sispi_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SispiModel = new SispiModel();

        $Sispi = DB::table('sispi')->where('sispi_id', $sispi_id)->select(['sispi_fl'])->get()->first();
        $delete = $SispiModel::where('sispi_id', $sispi_id)->delete([]);
        if ($delete) {
            Storage::delete('/public/uploads/'.$Sispi->sispi_fl);
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Siswa Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Siswa Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }
}
