<?php

namespace App\Http\Controllers;

use App\Http\Resources\AbsenSispResource;
use App\Models\AbsenModel;
use App\Models\AIModel;
use App\Models\SispModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AbsenSispController extends Controller
{
    public function getYear()
    {
        $Tahun = AbsenModel::select(DB::raw('YEAR(absen_tgl) year'))->groupBy('year')->orderBy('year', 'desc')->get();
        return AbsenSispResource::collection($Tahun);
    }

    public function getMonthByYear($year = '')
    {
        $Bulan = AbsenModel::select(DB::raw("DATE_FORMAT(absen_tgl, '%m') new_date"), DB::raw('MONTH(absen_tgl) month'))->whereYear('absen_tgl', '=', $year)->groupBy('month')->orderBy('month', 'asc')->get();

        $BulanN = [];
        for ($i=0; $i < count($Bulan); $i++) { 
            $BulanN[$i]['optValue'] = '';
            $BulanN[$i]['optValue'] = $Bulan[$i]->new_date;

            $BulanN[$i]['optText'] = '';
            $BulanN[$i]['optText'] = AIModel::monthConvIntSt((int)$Bulan[$i]->new_date);
        }
        return AbsenSispResource::collection($BulanN);
    }

    public function showLast()
    {
        DB::statement(DB::raw('set @rownum=0'));
        // $Absen = DB::table('absen')->leftJoin('bag', 'absen.absen_bag', '=', 'bag.bag_id')->leftJoin('sisp', 'absen.absen_sisp', '=', 'sisp.sisp_id')->where('absen_tgl', date("Y-m-d"));
        $Absen = DB::table('absen')->leftJoin('bag', 'absen.absen_bag', '=', 'bag.bag_id')->leftJoin('sisp', 'absen.absen_sisp', '=', 'sisp.sisp_id')->where('absen_sisp', Auth::user()->users_sisp)->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'bag_nm', 'absen_tgl', 'absen_masuk', 'absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp','absen_sts', 'absen_id', 'absen_masuklok', 'absen_keluarlok'])->orderBy('absen_ord', 'desc')->limit(10)->get();


        for ($i=0; $i < count($Absen); $i++) { 
            $Absen[$i]->absen_ket = 'Masuk Tepat Waktu';
            if ((int)$Absen[$i]->absen_lmbt>0) {
                $Absen[$i]->absen_ket = 'Terlambat: '.(string)intdiv((int)$Absen[$i]->absen_lmbt, 60).' Jam, '.(string)((int)$Absen[$i]->absen_lmbt % 60).' Menit';
            }else{
                if ($Absen[$i]->absen_masukk=="0"&&$Absen[$i]->absen_masuk!="00:00:00") {
                    $Absen[$i]->absen_ket = 'Masuk Diluar Jadwal';   
                }
            }

            $Absen[$i]->absen_lokAlT = 'Di area kantor';
            if ($Absen[$i]->absen_masuklok!='1'||$Absen[$i]->absen_keluarlok!='1') {
                $Absen[$i]->absen_lokAlT = 'Di luar area kantor';
            }

            $Absen[$i]->absen_stsAltT = 'Hadir';
            if ($Absen[$i]->absen_sts=='TH') {
                $Absen[$i]->absen_stsAltT = 'Tidak Hadir';
            }elseif ($Absen[$i]->absen_sts=='I'){
                $Absen[$i]->absen_stsAltT = 'Izin';
            }
            
        }
        

        return new AbsenSispResource(AbsenController::setData($Absen));
    }

    public function showMonth($month = '', $year = '')
    {
        if ($month=='') {
            $month = date("m");
        }
        if ($year=='') {
            $year = date("Y");
        }
        $Absen = AbsenModel::leftJoin('bag', 'absen.absen_bag', '=', 'bag.bag_id')->leftJoin('sisp', 'absen.absen_sisp', '=', 'sisp.sisp_id')->where('absen_sisp', Auth::user()->users_sisp)->whereYear('absen_tgl', '=', $year)->whereMonth('absen_tgl', '=', $month)->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'bag_nm', 'absen_tgl', 'absen_masuk', 'absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp','absen_sts', 'absen_id', 'absen_masuklok', 'absen_keluarlok'])->orderBy('absen_ord', 'DESC')->get();
        for ($i=0; $i < count($Absen); $i++) { 
            $Absen[$i]->absen_ket = 'Masuk Tepat Waktu';
            if ((int)$Absen[$i]->absen_lmbt>0) {
                $Absen[$i]->absen_ket = 'Terlambat: '.(string)intdiv((int)$Absen[$i]->absen_lmbt, 60).' Jam, '.(string)((int)$Absen[$i]->absen_lmbt % 60).' Menit';
            }else{
                if ($Absen[$i]->absen_masukk=="0"&&$Absen[$i]->absen_masuk!="00:00:00") {
                    $Absen[$i]->absen_ket = 'Masuk Diluar Jadwal';   
                }
            }

            $Absen[$i]->absen_lokAlT = 'Di area kantor';
            if ($Absen[$i]->absen_masuklok!='1'||$Absen[$i]->absen_keluarlok!='1') {
                $Absen[$i]->absen_lokAlT = 'Di luar area kantor';
            }

            $Absen[$i]->absen_stsAltT = 'Hadir';
            if ($Absen[$i]->absen_sts=='TH') {
                $Absen[$i]->absen_stsAltT = 'Tidak Hadir';
            }elseif ($Absen[$i]->absen_sts=='I'){
                $Absen[$i]->absen_stsAltT = 'Izin';
            }
            
        }
        

        return new AbsenSispResource(AbsenController::setData($Absen));
    }

    public function showDetail($id)
    {
        $Setkatpesj = [];
        $Absen = AbsenModel::leftJoin('sisp', 'absen.absen_sisp', '=', 'sisp.sisp_id')->where('absen_id', $id)->where('absen_sisp', Auth::user()->users_sisp)->select(['absen_tgl', 'absen_masukk', 'absen_masuk', 'absen_masuklat', 'absen_masuklong', 'absen_masuklok', 'absen_masukpic', 'absen_cd', 'absen_lmbt', 'absen_keluark', 'absen_keluar', 'absen_keluarlong', 'absen_keluarlat', 'absen_keluarlok', 'absen_keluarpic', 'absen_cp', 'absen_lbh', 'absen_sts', 'sisp_nm', 'absen_id', 'absen_setkatpesj'])->orderBy('absen_ord', 'DESC')->get()->first();

        $Absen = AbsenController::setData($Absen);

        $Absen->absen_masuklokAltT = 'Di area kantor';
        if($Absen->absen_masuklok == '0'){
            $Absen->absen_masuklokAltT = 'Di luar area kantor';
        }
        $Absen->absen_keluarlokAltT = 'Di area kantor';
        if($Absen->absen_keluarlok == '0'){
            $Absen->absen_keluarlokAltT = 'Di luar area kantor';
        }

        $Absen->absen_stsAltT = 'Hadir';
        if($Absen->absen_sts == 'TH'){
            $Absen->absen_stsAltT = 'Tidak Hadir';
        }elseif($Absen->absen_sts == 'I'){
            $Absen->absen_stsAltT = 'Izin';
        }

        if($Absen->absen_keluarlong == null){
            $Absen->absen_keluarlong = '';
        }
        if($Absen->absen_keluarlat == null){
            $Absen->absen_keluarlat = '';
        }

        if ($Absen!=null) {
            $Setkatpesj = DB::table('setkatpesj')->where('setkatpesj_id', $Absen->absen_setkatpesj)->select(['setkatpesj_masuk', 'setkatpesj_keluar'])->get()->first();
        }

        $Absen->absen_psn = 'Pulang Tepat Waktu';
        if ($Absen->absen_sts=="TH"||$Absen->absen_sts=="I") {
            $Absen->absen_psn = 'Status Absensi Tidak Hadir';
        }else{
            if((int)$Absen->absen_lmbt>0){
                if ($Setkatpesj!=null) {
                    $MenitTambahan = date('H:i', strtotime("+".(string)$Absen->absen_lmbt." minutes",strtotime($Setkatpesj->setkatpesj_keluar)));
                    $Absen->absen_psn = 'Harus Absen Pulang Pada Pukul '.(string)$MenitTambahan;
                }
            }
        }

        $Absen->absen_lmbt = (string)intdiv((int)$Absen->absen_lmbt, 60)." Jam,".(string)((int)$Absen->absen_lmbt % 60)." Menit.";
        $Absen->absen_lbh = (string)intdiv((int)$Absen->absen_lbh, 60)." Jam,".(string)((int)$Absen->absen_lbh % 60)." Menit.";
        $Absen->absen_cp = (string)intdiv((int)$Absen->absen_cp, 60)." Jam,".(string)((int)$Absen->absen_cp % 60)." Menit.";
        $Absen->absen_cd = (string)intdiv((int)$Absen->absen_cd, 60)." Jam,".(string)((int)$Absen->absen_cd % 60)." Menit.";

        
        return new AbsenSispResource($Absen);
    }

    public function showRekap($month = '', $year = '')
    {
        if ($month=='') {
            $month = date("m");
        }
        if ($year=='') {
            $year = date("Y");
        }
        
        $data['data'] = [
            'H' => '0',
            'I' => '0',
            'TH' => '0',
        ];
        $data['data']['H'] = AbsenModel::where('absen_sts', 'H')->where('absen_sisp', Auth::user()->users_sisp)->whereYear('absen_tgl', '=', $year)->whereMonth('absen_tgl', '=', $month)->get()->count();
        $data['data']['I'] = AbsenModel::where('absen_sts', 'I')->where('absen_sisp', Auth::user()->users_sisp)->whereYear('absen_tgl', '=', $year)->whereMonth('absen_tgl', '=', $month)->get()->count();
        $data['data']['TH'] = AbsenModel::where('absen_sts', 'TH')->where('absen_sisp', Auth::user()->users_sisp)->whereYear('absen_tgl', '=', $year)->whereMonth('absen_tgl', '=', $month)->get()->count();
        
        return response()->json($data, 200);
    }

    public function sispDetail()
    {
        $data['WebTitle'] = 'DATA DETAIL PEGAWAI';
        $data['MethodForm'] = 'insertData';
        $data['IdForm'] = 'guruAddData';
        $data['UrlForm'] = 'sisp';

        $Sisp = SispModel::leftJoin('users', 'sisp.sisp_id', '=', 'users.users_sisp')->leftJoin('bag', 'sisp.sisp_bag', '=', 'bag.bag_id')->leftJoin('setpd', 'sisp.sisp_setpd', '=', 'setpd.setpd_id')->leftJoin('sispdp', 'sisp.sisp_id', '=', 'sispdp.sispdp_sisp')->leftJoin('setstspeg', 'sispdp.sispdp_setstspeg', '=', 'setstspeg.setstspeg_id')->where('sisp_id', Auth::user()->users_sisp)->select(['sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'sisp_tmptlhr', 'username', 'sisp_email', 'sisp_tgllhr', 'sisp_jk', 'sisp_alt', 'setstspeg_nm', 'sisp_act', 'sispdp_setstspeg', 'sisp_pic', 'sisp_telp', 'sisp_kntrk', 'sisp_tglkntrk', 'sisp_wak', 'sisp_wa', 'sisp_bag', 'bag_nm', 'bag_prnt', 'sisp_setpd', 'setpd_nm'])->get()->first();
        if ($Sisp->sisp_email==null) {
            $Sisp->sisp_email = '';
        }
        if ($Sisp->sisp_idsp==null) {
            $Sisp->sisp_idsp = '';
        }
        if ($Sisp->sisp_tglkntrk==null) {
            $Sisp->sisp_tglkntrk = '0000-00-00';
        }
        if ($Sisp->sisp_setpd==null) {
            $Sisp->sisp_setpd = '';
        }
        if ($Sisp->setpd_nm==null) {
            $Sisp->setpd_nm = '';
        }
        if ($Sisp->sisp_wa==null) {
            $Sisp->sisp_wa = '';
        }
        if ($Sisp->sisp_wak==null) {
            $Sisp->sisp_wak = '';
        }
        if ($Sisp->sisp_telp==null) {
            $Sisp->sisp_telp = '';
        }
        $Sisp = GuruController::setData($Sisp, $data);
        
        return new AbsenSispResource($Sisp);

    }

    public function showDetailByDate()
    {
        $Setkatpesj = [];
        $Absen = AbsenModel::leftJoin('sisp', 'absen.absen_sisp', '=', 'sisp.sisp_id')->whereDate('absen_tgl', '=', date('Y-m-d'))->where('absen_sisp', Auth::user()->users_sisp)->select(['absen_tgl', 'absen_masukk', 'absen_masuk', 'absen_masuklat', 'absen_masuklong', 'absen_masuklok', 'absen_masukpic', 'absen_cd', 'absen_lmbt', 'absen_keluark', 'absen_keluar', 'absen_keluarlong', 'absen_keluarlat', 'absen_keluarlok', 'absen_keluarpic', 'absen_cp', 'absen_lbh', 'absen_sts', 'sisp_nm', 'absen_id', 'absen_setkatpesj'])->orderBy('absen_ord', 'DESC')->get()->first();

        if ($Absen!=null) {
            $Absen = AbsenController::setData($Absen);
    
            $Absen->absen_masuklokAltT = 'Di area kantor';
            if($Absen->absen_masuklok == '0'){
                $Absen->absen_masuklokAltT = 'Di luar area kantor';
            }
            $Absen->absen_keluarlokAltT = 'Di area kantor';
            if($Absen->absen_keluarlok == '0'){
                $Absen->absen_keluarlokAltT = 'Di luar area kantor';
            }
    
            $Absen->absen_stsAltT = 'Hadir';
            if($Absen->absen_sts == 'TH'){
                $Absen->absen_stsAltT = 'Tidak Hadir';
            }elseif($Absen->absen_sts == 'I'){
                $Absen->absen_stsAltT = 'Izin';
            }

            if($Absen->absen_keluarlong == null){
                $Absen->absen_keluarlong = '';
            }
            if($Absen->absen_keluarlat == null){
                $Absen->absen_keluarlat = '';
            }
            $Setkatpesj = DB::table('setkatpesj')->where('setkatpesj_id', $Absen->absen_setkatpesj)->select(['setkatpesj_masuk', 'setkatpesj_keluar'])->get()->first();
        }

        $Absen->absen_psn = 'Pulang Tepat Waktu';
        if ($Absen->absen_sts=="TH"||$Absen->absen_sts=="I") {
            $Absen->absen_psn = 'Status Absensi Tidak Hadir';
        }else{
            if((int)$Absen->absen_lmbt>0){
                if ($Setkatpesj!=null) {
                    $MenitTambahan = date('H:i', strtotime("+".(string)$Absen->absen_lmbt." minutes",strtotime($Setkatpesj->setkatpesj_keluar)));
                    $Absen->absen_psn = 'Harus Absen Pulang Pada Pukul '.(string)$MenitTambahan;
                }
            }
        }

        $Absen->absen_lmbt = (string)intdiv((int)$Absen->absen_lmbt, 60)." Jam,".(string)((int)$Absen->absen_lmbt % 60)." Menit.";
        $Absen->absen_lbh = (string)intdiv((int)$Absen->absen_lbh, 60)." Jam,".(string)((int)$Absen->absen_lbh % 60)." Menit.";
        $Absen->absen_cp = (string)intdiv((int)$Absen->absen_cp, 60)." Jam,".(string)((int)$Absen->absen_cp % 60)." Menit.";
        $Absen->absen_cd = (string)intdiv((int)$Absen->absen_cd, 60)." Jam,".(string)((int)$Absen->absen_cd % 60)." Menit.";
        
        return new AbsenSispResource($Absen);
    }

    public function insertData(Request $request)
    {
        ini_set('max_execution_time', 6000);
        
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');
        $rules = [
            'long' => 'required',
            'lat' => 'required',
            'tipe' => 'required',
            'pic' => 'required',
        ];
        $attributes = [
            'long' => 'Koordinat Longitude',
            'lat' => 'Koordinat Latitude',
            'tipe' => 'Tipe Absensi',
            'pic' => 'Foto Absensi',
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
            $Sisp = DB::table('sisp')->where('sisp_id', Auth::user()->users_sisp)->select(['sisp_id'])->get()->first();
            if ($Sisp==null) {
                $data['response'] = ['status' => 404, 'response' => 'error','type' => "danger", 'message' => 'Data Peserta Tidak Ditemukan'];
            }else{

                $guessExtension = $request->file('pic')->guessExtension();
                $filename = 'pic-masuk-'.$Sisp->sisp_id.'-'.date("Y-m-d-H-i-s").".".$guessExtension;
    
                $data = AbsenController::prosesMasuk(Auth::user(), $Sisp->sisp_id, date("Y-m-d H:i:s"), $request->long, $request->lat, $request->tipe, $filename);
                if ($data['response']['status'] == 200) {
                    $file = $request->file('pic')->storeAs('/public/uploads', $filename );
                    // $file = $request->file('pic')->storeAs('/public/uploads', $filename );
                }
            }
        }
        return response()->json($data['response'], $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        ini_set('max_execution_time', 6000);
        
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');
        $rules = [
            'long' => 'required',
            'lat' => 'required',
            'id' => 'required',
            'pic' => 'required',
        ];
        $attributes = [
            'long' => 'Koordinat Longitude',
            'lat' => 'Koordinat Latitude',
            'id' => 'ID Absen',
            'pic' => 'Foto Absensi',
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
            $Sisp = DB::table('sisp')->where('sisp_id', Auth::user()->users_sisp)->select(['sisp_id'])->get()->first();
            if ($Sisp==null) {
                $data['response'] = ['status' => 404, 'response' => 'error','type' => "danger", 'message' => 'Data Peserta Tidak Ditemukan'];
            }else{

                $guessExtension = $request->file('pic')->guessExtension();
                $filename = 'pic-pulang-'.$Sisp->sisp_id.'-'.date("Y-m-d-H-i-s").".".$guessExtension;
    
                $data = AbsenController::prosesKeluar($request->id, Auth::user(), $Sisp->sisp_id, date("Y-m-d H:i:s"), $request->long, $request->lat, $request->tipe, $filename);

                if ($data['response']['status'] == 200) {
                    $file = $request->file('pic')->storeAs('/public/uploads', $filename );
                    // $file = $request->file('pic')->storeAs('/public/uploads', $filename );
                }
            }
        }
        return response()->json($data['response'], $data['response']['status']);
    }

    public function updateDataPic(Request $request)
    {   
        $rules = [
            'pic' => 'required|max:5120',
        ];
        $attributes = [
            'pic' => 'Foto Pegawai',
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
            $guessExtension = $request->file('pic')->guessExtension();
            $filename = 'pic-sisp-'.date("Y-m-d-H-i-s").".".$guessExtension;

            try {
                $update = DB::table('sisp')->where('sisp_id', Auth::user()->users_sisp)->update([
                    'sisp_pic' => $filename,
                    'sisp_uupdate' => Auth::user()->users_id
                ]);
                $file = $request->file('pic')->storeAs('/public/uploads', $filename );
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
        return response()->json($data['response'], $data['response']['status']);
    }

    public function updateDataGuru(Request $request)
    {
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
            'sisp_telp' => 'required',
            
        ];
        $attributes = [
            
            'sisp_nm' => 'Nama Lengkap',
            'sisp_tmptlhr' => 'Tempat Lahir',
            'sisp_tgllhr' => 'Tanggal Lahir',
            'sisp_bag' => 'PPK Pegawai',
            'sisp_jk' => 'Jenis Kelamin',
            'sisp_alt' => 'Alamat',
            'sisp_kntrk' => 'Nomor Kontrak',
            'sisp_tglkntrk' => 'Tanggal Kontrak',
            'sisp_wa' => 'Nomor WA',
            'sisp_wak' => 'Nomor WA Keluarga',
            'sisp_setpd' => 'Tingkat Pendidikan',
            'sisp_telp' => 'Telepon',
            
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
                $update = DB::table('sisp')->where('sisp_id', Auth::user()->users_sisp)->update([
                    
                    'sisp_nm' => addslashes($request->sisp_nm),
                    'sisp_tmptlhr' => addslashes($request->sisp_tmptlhr),
                    'sisp_tgllhr' => $request->sisp_tgllhr,
                    'sisp_bag' => $request->sisp_bag,
                    'sisp_jk' => $request->sisp_jk,
                    'sisp_alt' => addslashes($request->sisp_alt),
                    'sisp_kntrk' => $request->sisp_kntrk,
                    'sisp_tglkntrk' => $request->sisp_tglkntrk,
                    'sisp_wa' => $request->sisp_wa,
                    'sisp_wak' => $request->sisp_wak,
                    'sisp_setpd' => $request->sisp_setpd,
                    'sisp_telp' => $request->sisp_telp,
                    
                    'sisp_uupdate' => Auth::user()->users_id
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
        return response()->json($data['response'], $data['response']['status']);
    }
}
