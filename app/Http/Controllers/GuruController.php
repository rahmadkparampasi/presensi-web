<?php

namespace App\Http\Controllers;

use App\Models\AIModel;
use App\Models\BagModel;
use App\Models\SispdpModel;
use App\Models\SispdsModel;
use App\Models\SispModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;
use Milon\Barcode\DNS1D;
use Milon\Barcode\Facades\DNS1DFacade;
use Milon\Barcode\Facades\DNS2DFacade;

class GuruController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOPegawai',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'PEGAWAI',
            'PageTitle' => 'Pegawai',
            'BasePage' => 'sisp',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'sispAddData';
        $this->data['UrlForm'] = 'sisp';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        $this->data['Kec'] = KecController::getData();
        $this->data['Agama'] = AgController::getDataActStat();

        $this->data['Setstspeg'] = SetstspegController::getDataActStat();

        $this->data['Setkatpes_ps'] = SetkatpesController::getDataPgStat();

        $this->data['act'] = '1';

        if ($this->data['Pgn']->users_tipe=="M") {
            return redirect()->intended();
        }

        if ($request->ajax()) {
            $sts_id = '';
            if ($request->post('sts_id')!='') {
                $sts_id = $request->post('sts_id');
            }
            return GuruController::loadData($this->data['Pgn'], $this->data, 0, $sts_id);
        }

        return view('guru.index', $this->data);
    }

    public function nonaktif(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'guruAddData';
        $this->data['UrlForm'] = 'guru';
        $this->data['PageTitle'] = 'Pegawai Non Aktif';
        $this->data['WebTitle'] = 'PEGAWAI NON AKTIF';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        $this->data['Setstspeg'] = SetstspegController::getDataActStat();

        if ($this->data['Pgn']->users_tipe=="M") {
            return redirect()->intended();
        }

        $this->data['act'] = '0';

        if ($request->ajax()) {
            $sts_id = '';
            if ($request->post('sts_id')!='') {
                $sts_id = $request->post('sts_id');
            }
            return GuruController::loadData($this->data['Pgn'], $this->data, 0, $sts_id, $this->data['act']);
        }

        return view('guru.index', $this->data);
    }

    public function load($act = '')
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'guruAddData';
        $this->data['UrlForm'] = 'guru';

        if ($act=='') {
            $this->data['act'] = '1';
        }else{
            $this->data['act'] = $act;
        }

        return view('guru.data', $this->data);
    }

    public function detail($id) 
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['Agent'] = new Agent;

        if ($this->data['Pgn']->users_tipe=="A") {
            if (SispModel::where('sisp_id', $id)->get()->first()==null) {
                return redirect()->intended('guru');
            }
        }elseif ($this->data['Pgn']->users_tipe=="G") {
            if (SispModel::where('sisp_id', $id)->get()->first()==null) {
                Auth::logout();
                return back()->with(['loginError'=> 'Data Pegawai Tidak Ditemukan']);
            }
        }
        
        
        $this->data['WebTitle'] = 'DATA DETAIL PEGAWAI';
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'guruAddData';
        $this->data['UrlForm'] = 'sisp/'.$id;
        $UserC = new UserController;
        $SispC = new SispController;
        $this->data['User'] = $UserC->detailSisp($id, 'G');
        $this->data['BcKrt'] = $SispC->detailBcKrt($id);
        $this->data['Prov'] = ProvController::getData();
        
        $this->data['Agama'] = AgController::getDataActStat();
        $this->data['SatkerC'] = BagController::getDataStat();
        $this->data['Setstspeg'] = SetstspegController::getDataActStat();
        $this->data['Setpd'] = SetpdController::getDataActStat();

        $this->data['Satker'] = DB::table('bagk')->leftJoin('bag', 'bag.bag_id', '=', 'bagk.bagk_bag')->where('bagk_satker', '1')->where('bagk_sisp', $id)->select(['bag_nm', 'bag_id', 'bagk_id'])->get()->first();

        $this->data['Ppk'] = DB::table('bagk')->leftJoin('bag', 'bag.bag_id', '=', 'bagk.bagk_bag')->where('bagk_satker', '0')->where('bagk_sisp', $id)->select(['bag_nm', 'bag_id', 'bagk_id'])->get()->first();

        $this->data['Guru'] = DB::table('sisp')->leftJoin('bag', 'sisp.sisp_bag', '=', 'bag.bag_id')->leftJoin('setpd', 'sisp.sisp_setpd', '=', 'setpd.setpd_id')->leftJoin('sispdp', 'sisp.sisp_id', '=', 'sispdp.sispdp_sisp')->leftJoin('setstspeg', 'sispdp.sispdp_setstspeg', '=', 'setstspeg.setstspeg_id')->where('sisp_id', $id)->select(['sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'sisp_tmptlhr', 'sisp_tgllhr', 'sisp_jk', 'sisp_alt', 'setstspeg_nm', 'sisp_act', 'sispdp_setstspeg', 'sisp_pic', 'sisp_telp', 'sisp_kntrk', 'sisp_tglkntrk', 'sisp_wak', 'sisp_wa', 'sisp_bag', 'bag_nm', 'bag_prnt', 'sisp_setpd', 'setpd_nm'])->get()->first();
        $this->data['Guru'] = GuruController::setData($this->data['Guru'], $this->data);

        $this->data['Bag'] = [];
        if (isset($this->data['Guru']->sisp_satker)) {
            $this->data['Bag'] = DB::table('bag')->where('bag_prnt', $this->data['Guru']->sisp_satker)->select(['bag_id', 'bag_nm'])->get();
        }


        return view('guru.detail', $this->data);
    }
    
    public function detailGuru($id)
    {
        $formData['Pgn'] = $this->getUser();

        $formData['Guru'] = DB::table('sisp')->leftJoin('bag', 'sisp.sisp_bag', '=', 'bag.bag_id')->leftJoin('setpd', 'sisp.sisp_setpd', '=', 'setpd.setpd_id')->leftJoin('sispdp', 'sisp.sisp_id', '=', 'sispdp.sispdp_sisp')->leftJoin('setstspeg', 'sispdp.sispdp_setstspeg', '=', 'setstspeg.setstspeg_id')->where('sisp_id', $id)->select(['sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'sisp_tmptlhr', 'sisp_tgllhr', 'sisp_jk', 'sisp_alt', 'setstspeg_nm', 'sisp_act', 'sispdp_setstspeg', 'sisp_kntrk', 'sisp_tglkntrk', 'sisp_wak', 'sisp_wa', 'sisp_bag', 'bag_nm', 'bag_prnt', 'sisp_setpd', 'setpd_nm'])->get()->first();

        $formData['Guru'] = GuruController::setData($formData['Guru'], $formData);

        return view('guru.detailGuru', $formData);
    }

    public function filter(Request $request, $act = '')
    {
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'guruAddData';
        $this->data['UrlForm'] = 'guru';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        $this->data['filter_sts'] = '';

        if ($act=='') {
            $this->data['act'] = '1';
        }else{
            $this->data['act'] = $act;
        }

        if ($request->post()) {
            $this->data['filter_sts'] = $request->post('filter_sts');
            $this->data['dataAjaxDT'] = "data:{'sts_id':'".$request->post('filter_sts')."'}";
            $this->data['paramCtk'] = ['sts' , $this->data['filter_sts']];
        }
        
        return view('guru.data', $this->data);
    }

    static function loadData($Pgn, $formData, $limit = 0, $sts = '', $sisp_act = '1') {
        $Setkatpes_ps = SetkatpesController::getDataPgStat();

        DB::statement(DB::raw('set @rownum=0'));
        $Guru = DB::table('sisp')->leftJoin('bag', 'sisp.sisp_bag', '=', 'bag.bag_id')->where('sisp_act', $sisp_act)->where('sisp_setkatpes', $Setkatpes_ps->setkatpes_id);
        
        $Guru = $Guru->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'sisp_id', 'sisp_idsp', 'sisp_nmd', 'sisp_nmb', 'sisp_nm', 'sisp_tmptlhr', 'sisp_tgllhr', 'sisp_jk', 'sisp_alt', 'sisp_act', 'sisp_pic', 'sisp_telp', 'sisp_bag', 'bag_nm', 'bag_prnt'])->orderBy('sisp_nm', 'asc')->orderBy('sisp_update', 'desc')->orderBy('sisp_ord', 'desc');
        if ($limit!=0) {
            $Guru = $Guru->limit($limit)->get();
        }else{
            $Guru = $Guru->get();
        }
        $Guru = GuruController::setData($Guru, $formData);
        return datatables()->of($Guru)->addColumn('dataTTL', function($Guru){
            return stripslashes($Guru->sisp_tmptlhr).', '.$Guru->sisp_tgllhrAltT;
        })->addColumn('dataAlt', function($Guru){
            return $Guru->sisp_altAltT.'<br/>Kontak:'.$Guru->sisp_telp;
        })->addColumn('aksiFoto', function ($Guru) use ($Pgn) {
            $button = '';
            $button .= '<button type="button" class="btn btn-info" onclick="showPreviewImgSrc(\''.asset('storage/uploads/'.$Guru->sisp_pic).'\'); $(\'#modalViewImgTitle\').html(\'Pratinjau Foto '.$Guru->sisp_nm.'\')" data-target="#modalViewImg" data-toggle="modal"><i class="fas fa-image"></i></button>';
            return $button;
        })->addColumn('aksiDetail', function ($Guru) use ($Pgn) {
            $button = '';
            $button .= $Guru->sisp_nmAltT.' <a target="_blank" href="'.route('sisp.detail', [$Guru->sisp_id]).'" type="button" class="btn btn-primary btn-sm" ><i class="fas fa-user"></i></a>';
            return $button;
        })->addColumn('aksiStatus', function ($Guru) use ($Pgn) {
            $button = '';
            $button .= $Guru->sisp_actAltBu;
        })->addColumn('satker', function ($Guru) use ($Pgn) {
            $button = '';
            $button .= $Guru->sisp_satkernm.' - '.$Guru->bag_nm;
            return $button;
        })->addColumn('aksiHapus', function ($Guru) use ($Pgn, $sisp_act, $formData) {
            $button = '';
            $pesan = SispController::sugestionMessageDelete($Guru->sisp_nm, 'Pegawai');
            if ($sisp_act=="1") {
                $button .= "<span onclick='callOtherTWCLoad(\"".$pesan."\", \"".url('sisp/delete/'.$Guru->sisp_id)."\", \"".url('sisp/setAct/0/'.$Guru->sisp_id)."\", \"Hapus\", \"Nonaktifkan\", \"".route('sisp.load', [$sisp_act])."\", \"modalAddDataForm\", \"".$formData['IdForm']."data\", \"modalAddData\", \"\", \"#6c757d\")' role='button' class='btn btn-danger font-weight-bold'><i class=\"fa fa-trash\"></i> HAPUS</span>";
            }else{
                $button .= "<span onclick='callOtherTWCLoad(\"".$pesan."\", \"".url('sisp/delete/'.$Guru->sisp_id)."\", \"".url('sisp/setAct/0/'.$Guru->sisp_id)."\", \"Hapus\", \"Nonaktifkan\", \"".route('sisp.load', [$sisp_act])."\", \"\", \"".$formData['IdForm']."data\", \"\", \"\", \"#6c757d\")' role='button' class='btn btn-danger font-weight-bold'><i class=\"fa fa-trash\"></i> HAPUS</span>";
            }
            return $button;
        })->rawColumns(['dataTTL', 'dataAlt', 'aksiFoto', 'aksiDetail', 'aksiStatus', 'aksiHapus', 'satker'])->setTotalRecords($limit)->make(true);
    }

    static function setCountChart()
    {
        $Setkatpes_ps = SetkatpesController::getDataPgStat();

        $count = array();
        $count['countAll'] = DB::table('sisp')->where('sisp_setkatpes', $Setkatpes_ps->setkatpes_id)->count();
        
        return $count;
    }

    static function setData($data, $formData)
    {
        $today = date("Y-m-d");
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
               
                $data[$i]->sisp_nm = ucwords(strtolower(stripslashes($data[$i]->sisp_nm)));
                
                $data[$i]->sisp_nmAltT = '';
                if (isset($data[$i]->sisp_nmd)) {
                    if ($data[$i]->sisp_nmd!='') {
                        $data[$i]->sisp_nmAltT .= stripslashes($data[$i]->sisp_nmd).'. ';
                    }
                }
                
                $data[$i]->sisp_nmAltT .= $data[$i]->sisp_nm;
                if (isset($data[$i]->sisp_nmb)) {
                    if ($data[$i]->sisp_nmb!='') {
                        $data[$i]->sisp_nmAltT .= ', '.stripslashes($data[$i]->sisp_nmb);
                    }
                }

                if (isset($data[$i]->sisp_tmptlhr)) {
                    $data[$i]->sisp_tmptlhr = ucwords(strtolower(stripslashes($data[$i]->sisp_tmptlhr)));
                }

                if (isset($data[$i]->sisp_jk)) {
                    $data[$i]->sisp_jkAltT = "Laki-Laki";
                    if ($data[$i]->sisp_jk=='P') {
                        $data[$i]->sisp_jkAltT = "Perempuan";
                    }
                }

                if (isset($data[$i]->sisp_tgllhr)) {
                    $data[$i]->uT = "0 Tahun";
                    $data[$i]->uB = "0 Bulan";
                    $data[$i]->uH = "0 Hari";
                    $data[$i]->umur = "0 Tahun, 0 Bulan, 0 Hari";
                    $data[$i]->sisp_tgllhrAltT = "";
                    if ($data[$i]->sisp_tgllhr!='0000-00-00') {
                        $data[$i]->sisp_tgllhrAltT = ucwords(strtolower(AIModel::changeDateNFSt($data[$i]->sisp_tgllhr)));
                        $diff = date_diff(date_create($data[$i]->sisp_tgllhr), date_create($today));
                        $data[$i]->uT = (string)$diff->format('%y')." Tahun";
                        $data[$i]->uB = (string)$diff->format('%m')." Bulan";
                        $data[$i]->uH = (string)$diff->format('%d')." Hari";
                        $data[$i]->umur = (string)$diff->format('%y')." Tahun, ".(string)$diff->format('%m')." Bulan, ".(string)$diff->format('%d')." Hari";
                    }
                }

                if (isset($data[$i]->sisp_alt)) {
                    $data[$i]->sisp_altAltT = '';
                    if (isset($data[$i]->desajenis)) {
                        $data[$i]->sisp_altAltT = "Desa ";
                        if ($data[$i]->desajenis=="K") {
                            $data[$i]->sisp_altAltT = "Kel. ";
                        }
                        $data[$i]->sisp_altAltT = ucwords(strtolower(stripslashes($data[$i]->sisp_alt))).", ".$data[$i]->sisp_altAltT.$data[$i]->desanama.", Kec. ".$data[$i]->kecnama;
                    }
                }

                $data[$i]->sisp_satker = "";
                $data[$i]->sisp_satkernm = "";
                if (isset($data[$i]->bag_prnt)) {
                    if ($data[$i]->bag_prnt!='') {
                        $Satker = BagModel::where('bag_id', $data[$i]->bag_prnt)->select(['bag_id', 'bag_nm'])->get()->first();
                        if($Satker!=null){
                            $data[$i]->sisp_satker = $Satker->bag_id;
                            $data[$i]->sisp_satkernm = $Satker->bag_nm;
                        }
    
                    }
                }

                if (isset($data[$i]->sisp_act)) {
                    $data[$i]->sisp_actAltBa = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
                    $data[$i]->sisp_actAltBu = "<span onclick='callOtherTWLoad(\"Mengubah Status Pegawai\", \"".url('sisp/setAct/0/'.$data[$i]->sisp_id)."\", \"".route('sisp.load', ['1'])."\", \"modalAddDataForm\", \"".$formData['IdForm']."data\", \"modalAddData\", \"\",\"Nonaktifkan\", \"#6c757d\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
                    if ($data[$i]->sisp_act=="0") {
                        $data[$i]->sisp_actAltBa = "<span class='badge badge-secondary font-weight-bold'>TIDAK AKTIF</span>";
    
                        $data[$i]->sisp_actAltBu = "<span onclick='callOtherTWLoad(\"Mengubah Status Pegawai\", \"".url('sisp/setAct/1/'.$data[$i]->sisp_id)."\", \"".route('sisp.load', ['0'])."\", \"\", \"".$formData['IdForm']."data\", \"\", \"\", \"Aktifkan\")' role='button' class='btn btn-secondary font-weight-bold'>TIDAK AKTIF</span>";
                    }
                }

            }
        }else{
            $data->sisp_nm = ucwords(strtolower(stripslashes($data->sisp_nm)));
                
            $data->sisp_nmAltT = '';
            if ($data->sisp_nmd!='') {
                $data->sisp_nmAltT .= stripslashes($data->sisp_nmd).'. ';
            }
            $data->sisp_nmAltT .= $data->sisp_nm;
            if ($data->sisp_nmb!='') {
                $data->sisp_nmAltT .= ', '.stripslashes($data->sisp_nmb);
            }

            if (isset($data->sisp_tmptlhr)) {
                $data->sisp_tmptlhr = ucwords(strtolower(stripslashes($data->sisp_tmptlhr)));
            }
            if (isset($data->sisp_jk)) {
                $data->sisp_jkAltT = "Laki-Laki";
                if ($data->sisp_jk=='P') {
                    $data->sisp_jkAltT = "Perempuan";
                }
            }
            
            if (isset($data->sisp_tgllhr)) {
                $data->uT = "0 Tahun";
                $data->uB = "0 Bulan";
                $data->uH = "0 Hari";
                $data->umur = "0 Tahun, 0 Bulan, 0 Hari";
                $data->sisp_tgllhrAltT = "";
                if ($data->sisp_tgllhr!='0000-00-00') {
                    $data->sisp_tgllhrAltT = ucwords(strtolower(AIModel::changeDateNFSt($data->sisp_tgllhr)));
                    $diff = date_diff(date_create($data->sisp_tgllhr), date_create($today));
                    $data->uT = (string)$diff->format('%y')." Tahun";
                    $data->uB = (string)$diff->format('%m')." Bulan";
                    $data->uH = (string)$diff->format('%d')." Hari";
                    $data->umur = (string)$diff->format('%y')." Tahun, ".(string)$diff->format('%m')." Bulan, ".(string)$diff->format('%d')." Hari";
                }
            }

            if (isset($data->sisp_tglkntrk)) {
                $data->sisp_tglkntrkAltT = "";
                if ($data->sisp_tglkntrk!='0000-00-00') {
                    $data->sisp_tglkntrkAltT = ucwords(strtolower(AIModel::changeDateNFSt($data->sisp_tglkntrk)));
                }
            }

            $data->sisp_altAltT = '';
            if (isset($data->desajenis)) {
                $data->sisp_altAltT = "Desa ";
                if ($data->desajenis=="K") {
                    $data->sisp_altAltT = "Kel. ";
                }
                $data->sisp_altAltT = ucwords(strtolower(stripslashes($data->sisp_alt))).", ".$data->sisp_altAltT.$data->desanama.", Kec. ".$data->kecnama;
            }

            $data->sisp_telpAltT = "";
            if (isset($data->sisp_telp)) {
                if ($data->sisp_telp!='') {
                    $data->sisp_telpAltT = "<br/>".$data->sisp_telp;
                }
            }

            $data->sisp_satker = "";
            $data->sisp_satkernm = "";
            if (isset($data->bag_prnt)) {
                if ($data->bag_prnt!='') {
                    $Satker = BagModel::where('bag_id', $data->bag_prnt)->select(['bag_id', 'bag_nm'])->get()->first();
                    if($Satker!=null){
                        $data->sisp_satker = $Satker->bag_id;
                        $data->sisp_satkernm = $Satker->bag_nm;
                    }

                }
            }

            if (isset($data->sisp_act)) {
                $data->sisp_actAltBa = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
                $data->sisp_actAltBu = "<span onclick='callOtherTWF(\"Mengubah Status Pegawai\", \"".url('sisp/setAct/0/'.$data->sisp_id)."\", loadDataHtml, {\"link\":[\"".route('sisp.detailSisp', [$data->sisp_id])."\", \"".route('user.detailProfil', [$data->sisp_id, 'G'])."\"], \"div\":[\"guruDetailGuru\", \"userDetailProfil\"]}, \"Nonaktifkan\", \"#6c757d\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
                if ($data->sisp_act=="0") {
                    $data->sisp_actAltBa = "<span class='badge badge-secondary font-weight-bold'>TIDAK AKTIF</span>";

                    $data->sisp_actAltBu = "<span onclick='callOtherTWF(\"Mengubah Status Pegawai\", \"".url('sisp/setAct/1/'.$data->sisp_id)."\",loadDataHtml, {\"link\":[\"".route('sisp.detailSisp', [$data->sisp_id])."\", \"".route('user.detailProfil', [$data->sisp_id, 'G'])."\"], \"div\":[\"guruDetailGuru\", \"userDetailProfil\"]}, \"Aktifkan\")' role='button' class='btn btn-secondary font-weight-bold'>TIDAK AKTIF</span>";
                }
            }
        }
        return $data;
    }

    public function setAct($sisp_act, $sisp_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SispModel = new SispModel();

        $message = "Dinonaktifkan";
        if ($sisp_act=="1") {
            $message = "Diaktifkan";
        }

        $update = $SispModel::where('sisp_id', $sisp_id)->update([
            'sisp_act' => $sisp_act
        ]);
        if ($update) {
            if ($sisp_act=="1") {
                $actUser = UserController::setActBySisp('1', $sisp_id);
            }else{
                $actUser = UserController::setActBySisp('0', $sisp_id);
            }
            if ($actUser) {
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pegawai Berhasil ".$message
                ];
            }else{
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pegawai Berhasil ".$message.", Tetapi Data Akun Pegawai Bermasalah."
                ];
            }
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pegawai Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');

        $rules = [
            'sisp_idsp' => 'required|numeric|unique:sisp,sisp_idsp,'.$request->sisp_idsp.',sisp_idsp',
            'sisp_nm' => 'required|alpha_spaces',
            'sisp_tmptlhr' => 'required|alpha_spaces',
            'sisp_tgllhr' => 'required',
            'sisp_jk' => 'required',
            'sisp_alt' => 'required',
            
            'sisp_pic' => 'required|mimes:jpg,jpeg,png',
            'sisp_setkatpes' => 'required',
            'sispdp_setstspeg' => 'required',
        ];
        $attributes = [
            'sisp_idsp' => 'NIP/NIK',
            'sisp_nm' => 'Nama Lengkap',
            'sisp_tmptlhr' => 'Tempat Lahir',
            'sisp_tgllhr' => 'Tanggal Lahir',
            'sisp_jk' => 'Jenis Kelamin',
            'sisp_alt' => 'Alamat',
            
            'sisp_pic' => 'Foto Pegawai',
            'sisp_setkatpes' => 'Kategori Peserta',
            'sispdp_setstspeg' => 'Status Pegawai',
            // 'captcha1' => 'Captha'
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
            $SispModel = new SispModel();
            
            $sisp_telp = null;
            if($request->sisp_telp!=''){
                $sisp_telp = $request->sisp_telp;
            }
            $SispSama = SispController::checkPrsn(addslashes($request->sisp_nm), $request->sisp_tgllhr, $request->sisp_bag);
            if (count($SispSama)>0) {
                $data['response'] = [
                    'status' =>  Response::HTTP_BAD_REQUEST,
                    'response' => "danger",
                    'type' => "danger",
                    'message' => 'Data Pegawai Sudah Ada, Silahkan Cek Kembali Data Pegawai'
                ];
            }else{
                $SispNisn = SispController::checkNisn($request->sisp_idsp);
                if ($SispNisn!=null) {
                    $data['response'] = [
                        'status' =>  Response::HTTP_BAD_REQUEST,
                        'response' => "danger",
                        'type' => "danger",
                        'message' => 'Data NIP Pegawai Sudah Ada, Silahkan Hubungi Pihak Administrasi Untuk Melihat Data Pegawai'
                    ];
                }else{
                    $sisp_idsp = $request->sisp_idsp;
                    $SispModel->sisp_idsp = $request->sisp_idsp;
                    $SispModel->sisp_nmd = addslashes($request->sisp_nmd);
                    $SispModel->sisp_nm = addslashes($request->sisp_nm);
                    $SispModel->sisp_nmb = addslashes($request->sisp_nmb);
                    $SispModel->sisp_tmptlhr = addslashes($request->sisp_tmptlhr);
                    $SispModel->sisp_tgllhr = $request->sisp_tgllhr;
                    $SispModel->sisp_jk = $request->sisp_jk;
                    $SispModel->sisp_alt = addslashes($request->sisp_alt);
                    
                    $SispModel->sisp_telp = $sisp_telp;
                    
                    $SispModel->sisp_setkatpes = $request->sisp_setkatpes;

                    $guessExtension = $request->file('sisp_pic')->guessExtension();
                    $filename = 'pic-guru-'.date("Y-m-d-H-i-s").".".$guessExtension;
                    $filenameBC = 'bc-guru-'.date("Y-m-d-H-i-s").".png";

                    $SispModel->sisp_pic = $filename;
                    $SispModel->sisp_bc = $filenameBC;
                    
                    $save = $SispModel->save();
                    if ($save) {
                        $Guru = SispModel::where('sisp_ord', $SispModel->sisp_id)->select(['sisp_id'])->get()->first();
                        $deleteUsers = User::where('username', $sisp_idsp)->delete([]);

                        $SispdpModel = new SispdpModel();
                        $SispdpModel->sispdp_sisp = $Guru->sisp_id;
                        $SispdpModel->sispdp_setstspeg = $request->sispdp_setstspeg;
                        $savedp = $SispdpModel->save();
                        if ($savedp) {
                            $Users = UserController::insertDataU(addslashes($request->sisp_nm), $Guru->sisp_id, $sisp_idsp, $sisp_idsp);
                    
                            $file = $request->file('sisp_pic')->storeAs('/public/uploads', $filename );
                            Storage::disk('public_bc')->put($filenameBC,base64_decode(DNS1DFacade::getBarcodePNG($sisp_idsp, 'C128',2,70,array(1,1,1), true)));
                            if ($file) {
                                $data['response'] = [
                                    'status' => 200,
                                    'response' => "success",
                                    'type' => "success",
                                    'message' => "Data Pegawai Berhasil Disimpan"];
                            }else{
                                DB::table('sisp')->where('sisp_idsp', $sisp_idsp)->delete([]);
                                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pegawai Tidak Dapat Disimpan, Terdapat Masalah Pada Foto Pegawai'];
                            }
                        }else{
                            DB::table('sisp')->where('sisp_idsp', $sisp_idsp)->delete([]);

                            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Detail Pegawai Tidak Dapat Disimpan'];
                        }
                    }else{
                        $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pegawai Tidak Dapat Disimpan'];
                    }
                }
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateDataGuru(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');
        $rules = [
            
            'sisp_nm' => 'required|alpha_spaces',
            'sisp_tmptlhr' => 'required|alpha_spaces',
            'sisp_tgllhr' => 'required',
            'sisp_bag' => 'required',
            'sisp_jk' => 'required',
            'sisp_alt' => 'required',
            'sisp_kntrk' => 'required',
            'sisp_tglkntrk' => 'required',
            'sisp_wa' => 'required',
            'sisp_wak' => 'required',
            'sisp_setpd' => 'required',
            
        ];
        $attributes = [
            
            'sisp_nm' => 'Nama Lengkap',
            'sisp_tmptlhr' => 'Tempat Lahir',
            'sisp_tgllhr' => 'Tanggal Lahir',
            'sisp_jk' => 'Jenis Kelamin',
            'sisp_bag' => 'PPK Pegawai',
            'sisp_alt' => 'Alamat',
            'sisp_kntrk' => 'Nomor Kontrak',
            'sisp_wa' => 'Nomor Kontrak',
            'sisp_wak' => 'Nomor WA Keluarga',
            'sisp_setpd' => 'Tingkat Pendidikan',
            'sisp_tglkntrk' => 'Tanggal Kontrak',
            
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
            $sisp_telp = null;
            if($request->sisp_telp!=''){
                $sisp_telp = $request->sisp_telp;
            }

            try {
                $update = DB::table('sisp')->where('sisp_id', $request->sisp_id)->update([
                    
                    'sisp_nmd' => addslashes($request->sisp_nmd),
                    'sisp_nm' => addslashes($request->sisp_nm),
                    'sisp_nmb' => addslashes($request->sisp_nmb),
                    'sisp_tmptlhr' => addslashes($request->sisp_tmptlhr),
                    'sisp_tgllhr' => $request->sisp_tgllhr,
                    'sisp_jk' => $request->sisp_jk,
                    'sisp_bag' => $request->sisp_bag,
                    'sisp_kntrk' => $request->sisp_kntrk,
                    'sisp_tglkntrk' => $request->sisp_tglkntrk,
                    'sisp_wa' => $request->sisp_wa,
                    'sisp_setpd' => $request->sisp_setpd,
                    'sisp_wak' => $request->sisp_wak,
                    
                    'sisp_alt' => addslashes($request->sisp_alt),
                    'sisp_telp' => $sisp_telp,
                    
                    'sisp_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pegawai Berhasil Diubah"
                ];
            } catch (\Throwable $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pegawai Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($sisp_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SispModel = new SispModel();

        $Guru = DB::table('sisp')->where('sisp_id', $sisp_id)->select(['sisp_pic', 'sisp_bc'])->get()->first();
        $delete = $SispModel::where('sisp_id', $sisp_id)->delete([]);
        if ($delete) {

            $deleteUser = UserController::deleteDataBySisp($sisp_id);
            Storage::delete('/public/uploads/'.$Guru->sisp_pic);
            Storage::delete('/public/bc/'.$Guru->sisp_bc);
            if ($deleteUser) {
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pegawai Berhasil Dihapus"
                ];
            }else{
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pegawai Berhasil Dihapus, Tetapi Data Akun Pegawai Bermasalah."
                ];
            }
            
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pegawai Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateDataPic(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        
        $rules = [
            'sisp_pic' => 'required|mimes:jpg,jpeg,png|max:5120',
        ];
        $attributes = [
            'sisp_pic' => 'Foto Pegawai',
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
            $guessExtension = $request->file('sisp_pic')->guessExtension();
            $filename = 'pic-guru-'.date("Y-m-d-H-i-s").".".$guessExtension;

            try {
                $update = DB::table('sisp')->where('sisp_id', $request->sisp_id)->update([
                    'sisp_pic' => $filename,
                    'sisp_uupdate' => $this->data['Pgn']->users_id
                ]);
                Storage::delete('/public/uploads/'.$request->brksImgName);
                $file = $request->file('sisp_pic')->storeAs('/public/uploads', $filename );
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pegawai Berhasil Diubah"
                ];
            } catch (\Throwable $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pegawai Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }
}
