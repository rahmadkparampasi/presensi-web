<?php

namespace App\Http\Controllers;

use App\Models\AIModel;
use App\Models\SispdpModel;
use App\Models\SispdsModel;
use App\Models\SispModel;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Milon\Barcode\Facades\DNS1DFacade;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PHPUnit\Framework\Constraint\FileExists;

class SispController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOSisp',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'SISWA PEGAWAI',
            'PageTitle' => 'Siswa Pegawai',
            'BasePage' => 'sisp',
        ];
    }

    public function insertDataGuru(Request $request)
    {
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
            'sisp_bag' => 'required',
            
            'sisp_pic' => 'required|mimes:jpg,jpeg,png',
            'sisp_setkatpes' => 'required',
            'sispdp_setstspeg' => 'required',
            'username' => 'required|unique:users,username,'.$request->username.',username',
            'password' => 'required|required_with:password_confirmation|same:password_confirmation|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|confirmed',
            'password_confirmation' => 'min:6',
            
            
            // 'captcha1' => 'required|captcha'
        ];
        $attributes = [
            'sisp_idsp' => 'NIP/NIK',
            'sisp_nm' => 'Nama Lengkap',
            'sisp_tmptlhr' => 'Tempat Lahir',
            'sisp_tgllhr' => 'Tanggal Lahir',
            'sisp_jk' => 'Jenis Kelamin',
            'sisp_alt' => 'Alamat',
            'sisp_bag' => 'PPK Pegawai',
            'sisp_pic' => 'Foto Pegawai',
            'sisp_setkatpes' => 'Kategori Peserta',
            'sispdp_setstspeg' => 'Status Pegawai',
            'username' => 'Username',
            'password' => 'Passowrd',
            'password_confirmation' => 'Ulangi Passowrd',
            // 'captcha1' => 'Captha'
        ];
        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }else{
            $SispModel = new SispModel();
            
            $sisp_telp = null;
            if($request->sisp_telp!=''){
                $sisp_telp = $request->sisp_telp;
            }

            $SispSama = SispController::checkPrsn(addslashes($request->sisp_nm), $request->sisp_tgllhr, $request->sisp_bag);
            if (count($SispSama)>0) {
                return back()->with(['registerError'=> 'Data Pegawai Sudah Ada, Silahkan Hubungi Pihak Administrasi Untuk Melihat Data Pegawai']);
            }
            $SispNisn = SispController::checkNisn($request->sisp_idsp);
            if ($SispNisn!=null) {
                return back()->with(['registerError'=> 'Data NIP/NIK Pegawai Sudah Ada, Silahkan Hubungi Pihak Administrasi Untuk Melihat Data Pegawai']);
            }
            $sisp_idsp = $request->sisp_idsp;
            $SispModel->sisp_idsp = $request->sisp_idsp;
            $SispModel->sisp_nmd = addslashes($request->sisp_nmd);
            $SispModel->sisp_nm = addslashes($request->sisp_nm);
            $SispModel->sisp_nmb = addslashes($request->sisp_nmb);
            $SispModel->sisp_tmptlhr = addslashes($request->sisp_tmptlhr);
            $SispModel->sisp_tgllhr = $request->sisp_tgllhr;
            $SispModel->sisp_jk = $request->sisp_jk;
            $SispModel->sisp_alt = addslashes($request->sisp_alt);
            $SispModel->sisp_bag = $request->sisp_bag;
            $SispModel->sisp_telp = $sisp_telp;
            
            $SispModel->sisp_setkatpes = $request->sisp_setkatpes;

            $guessExtension = $request->file('sisp_pic')->guessExtension();
            $filename = 'pic-peg-'.date("Y-m-d-H-i-s").".".$guessExtension;
            $filenameBC = 'bc-peg-'.date("Y-m-d-H-i-s").".png";

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
                    $Users = UserController::insertDataU(addslashes($request->sisp_nm), $Guru->sisp_id, $request->username, $request->password, 'P');
                        
                    $file = $request->file('sisp_pic')->storeAs('/public/uploads', $filename );
                    Storage::disk('public_bc')->put($filenameBC,base64_decode(DNS1DFacade::getBarcodePNG($sisp_idsp, 'C128',2,70,array(1,1,1), true)));
                    if ($file) {
                        $token = SispController::token($SispModel->sisp_idsp);
                        return redirect()->to(url('register/success'));
                    }else{
                        DB::table('sisp')->where('sisp_idsp', $sisp_idsp)->delete([]);
                        return back()->with(['registerError'=> 'Data Pegawai Tidak Dapat Disimpan, Terdapat Masalah Pada Foto Pegawai']);
                    }
                }else{
                    DB::table('sisp')->where('sisp_idsp', $sisp_idsp)->delete([]);

                    return back()->with(['registerError'=> 'Data Detail Pegawai Tidak Dapat Disimpan']);
                    
                }

            }else{
                return back()->with(['registerError'=> 'Data Pegawai Tidak Dapat Disimpan']);
            }
        }
    }

    public function generateUser()
    {
        // $Setkatpes_ps = SetkatpesController::getDataPgStat();
        ini_set('max_execution_time', 6000);

        UserController::insertDataU('ADMIN UTAMA', null, 'AdminUPresensi', 'AdmPre123!', 'A');

        // $Siswa = SispModel::where('sisp_setkatpes', $Setkatpes_ps->setkatpes_id)->select(['sisp_id', 'sisp_idsp', 'sisp_nm'])->orderBy('sisp_ord', 'asc')->get();

        
        // for ($i=0; $i < count($Siswa); $i++) { 
        //     // $Password = '';
        //     // $Password = $Siswa[$i]->sisp_idsp;
        //     // $nama = explode(' ', $Siswa[$i]->sisp_nm);
        //     // for ($j=0; $j < count($nama); $j++) { 
        //     //     $Password .= substr($nama[$j], 0, 1);
        //     // }
        //     // $Password .= '@';
        //     UserController::insertDataU($Siswa[$i]->sisp_nm, $Siswa[$i]->sisp_id, $Siswa[$i]->sisp_idsp, $Siswa[$i]->sisp_idsp, 'G');
        //     // UserController::insertDataU($Siswa[$i]->sisp_nm, $Siswa[$i]->sisp_id, $Siswa[$i]->sisp_idsp, $Siswa[$i]->sisp_idsp);
        // }
    }

    public function generatePhoto()
    {
        $Setkatpes_ps = SetkatpesController::getDataPsStat();
        ini_set('max_execution_time', 6000);

        // UserController::insertDataU('ADMIN UTAMA', null, 'AdminUCKT', 'AdmCKTKTG123!');

        // $Siswa = SispModel::where('sisp_setkatpes', $Setkatpes_ps->setkatpes_id)->select(['sisp_id', 'sisp_idsp', 'sisp_nm'])->orderBy('sisp_ord', 'asc')->get();
        $Siswa = SispModel::where('sisp_act', '1')->select(['sisp_id', 'sisp_pic'])->orderBy('sisp_ord', 'asc')->get();

        
        for ($i=0; $i < count($Siswa); $i++) { 
            // $Password = '';
            // $Password = $Siswa[$i]->sisp_idsp;
            // $nama = explode(' ', $Siswa[$i]->sisp_nm);
            // for ($j=0; $j < count($nama); $j++) { 
            //     $Password .= substr($nama[$j], 0, 1);
            // }
            // $Password .= '@';
            // UserController::insertDataU($Siswa[$i]->sisp_nm, $Siswa[$i]->sisp_id, $Siswa[$i]->sisp_idsp, $Siswa[$i]->sisp_idsp, 'G');
            if (file_exists(storage_path('app/public/uploads/'.$Siswa[$i]->sisp_pic))) {
                Storage::copy(storage_path('app/public/uploads/'.$Siswa[$i]->sisp_pic), storage_path('app/public/uploads/new/'.$Siswa[$i]->sisp_pic));
            }
            // UserController::insertDataU($Siswa[$i]->sisp_nm, $Siswa[$i]->sisp_id, $Siswa[$i]->sisp_idsp, $Siswa[$i]->sisp_idsp);
        }
    }

    public function generateBc()
    {
        $Setkatpes_ps = SetkatpesController::getDataPsStat();
        ini_set('max_execution_time', 6000);

        // UserController::insertDataU('ADMIN UTAMA', null, 'AdminUCKT', 'AdmCKTKTG123!');

        // $Siswa = SispModel::where('sisp_setkatpes', $Setkatpes_ps->setkatpes_id)->select(['sisp_id', 'sisp_idsp', 'sisp_nm'])->orderBy('sisp_ord', 'asc')->get();
        $Siswa = SispModel::where('sisp_setkatpes', $Setkatpes_ps->setkatpes_id)->select(['sisp_id', 'sisp_pic', 'sisp_idsp'])->orderBy('sisp_ord', 'asc')->get();

        // dd($Siswa);

        $this->data['Pgn'] = $this->getUser();
        
        for ($i=0; $i < count($Siswa); $i++) { 
            $filenameBC = 'bc-siswa-'.$Siswa[$i]->sisp_idsp.'-'.date("Y-m-d-H-i-s").".png";
            DB::table('sisp')->where('sisp_id', $Siswa[$i]->sisp_id)->update([
                'sisp_bc' => $filenameBC,
                'sisp_uupdate' => $this->data['Pgn']->users_id
            ]);
            Storage::disk('public_bc')->put($filenameBC,base64_decode(DNS1DFacade::getBarcodePNG($Siswa[$i]->sisp_idsp, 'C128',2,70,array(1,1,1), true)));
        }
    }

    static function token($id)
    {
        $expired_time = time() + (1440 * 30); // 1 hari token
        $payload = [
            'id' => $id,
            'exp' => $expired_time
        ];
        
        return JWT::encode($payload, env('ACCESS_TOKEN_SECRET'), 'HS256');
    }
    
    static function checkPrsn($nama, $tgl_lhr, $sisp_bag)
    {
        return DB::table('sisp')
        ->where(function($query) use ($nama){
            $query->where('sisp_nm', 'like', '%'.$nama.'%');
            $namaBaru = explode(" ", $nama);
            if (count($namaBaru)>1) {
                for ($i=1; $i < count($namaBaru); $i++) { 
                    $query->orWhere('sisp_nm', 'like', '%'.$namaBaru[$i].'%');
                }
            }
        })
        ->where('sisp_tgllhr', $tgl_lhr)->where('sisp_bag', $sisp_bag)->orderBy('sisp_ord', 'asc')
        ->limit(20)
        ->get();
    }

    static function checkLenghtNisn()
    {
        dd(DB::table('sisp')->where('sisp_conf', '1')->whereRaw('LENGTH(sisp_idsp) > 10')->get());
    }

    static function checkNISNByExcel()
    {
        ini_set('max_execution_time', 6000);

        $reader = new ReaderXlsx();
        // dd(Storage::path('public_template')->get('searchNisn.xlsx'));
        // dd(Storage::url('searchNisn.xlsx'));
        $path = Storage::path('public/template').'/searchNisn.xlsx';
        $spreadsheet = $reader->load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $worksheetinfo = $reader->listWorksheetInfo($path);
        $totalRows = $worksheetinfo[0]['totalRows'];
        for ($i=1; $i <= $totalRows ; $i++) { 
            $id = $sheet->getCell("A{$i}")->getValue();
            $Sisp = DB::table('sisp')->where('sisp_idsp', $id)->select(['sisp_idsp'])->get()->first();
            // DB::table('sisp')->leftJoin('sispds', 'sisp.sisp_id', '=', 'sispds.sispds_sisp')->leftJoin('bag', 'sisp.sisp_bag', '=', 'bag.bag_id')->where('sisp_idsp', $sisp_idsp)->select(['sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'sisp_tmptlhr', 'sisp_tgllhr', 'sisp_jk', 'sisp_alt', 'sisp_bag', 'sisp_tkt','bag_nm', 'sisp_thn', 'sisp_conf', 'sisp_conft', 'sispds_setcks', 'sispds_settks', 'sisp_act', 'sisp_bc'])->get()->first()
            if($Sisp!=null){
                continue;
            }
            $s = $sheet->getCell("F{$i}")->getValue();
            if ($s=='N') {
                continue;
            }
            echo $id . '<br/>';
        }
        // dd($sheet, $totalRows);
    }

    static function checkNama()
    {
        ini_set('max_execution_time', 6000);

        $sama = [];
        $Sisp = DB::table('sisp')->orderBy('sisp_ord', 'asc')->get();
        for ($i=0; $i < count($Sisp); $i++) { 
            $nama = $Sisp[$i]->sisp_nm;
            $NewSisp = DB::table('sisp')
            ->where(function($query) use ($nama){
                $query->where('sisp_nm', 'like', '%'.$nama.'%');
                $namaBaru = explode(" ", $nama);
                if (count($namaBaru)>1) {
                    for ($i=1; $i < count($namaBaru); $i++) { 
                        $query->orWhere('sisp_nm', 'like', '%'.$namaBaru[$i].'%');
                    }
                }
            })
            ->where('sisp_tgllhr', $Sisp[$i]->sisp_tgllhr)->where('sisp_tkt', $Sisp[$i]->sisp_tkt)->whereNotIn('sisp_id', [$Sisp[$i]->sisp_id])->orderBy('sisp_ord', 'asc')
            ->limit(20)
            ->get();

            if (count($NewSisp)>0) {
                array_push($sama, $Sisp[$i]);
            }
        }
        dd($sama);
    }

    static function checkNisn($sisp_idsp)
    {
        return DB::table('sisp')->where('sisp_idsp', $sisp_idsp)->get()->first();
    }

    static function sugestionMessageDelete($nama, $tipe = 'Siswa'){
        return 'Data ini terhubung dengan data lain yang mungkin juga akan terpengaruh. Apakah Anda yakin ingin melanjutkan penghapusan '.$tipe.' A.N. '.$nama.'? Jika tidak sepenuhnya yakin, sebaiknya nonaktifkan data ini terlebih dahulu untuk menghindari dampak yang tidak diinginkan.';
    }

    public function detailBcKrt($sisp_id)
    {
        $formData['Pgn'] = $this->getUser();
        $formData['sisp_id'] = $sisp_id;
        $formData['Sisp'] = DB::table('sisp')->where('sisp_id', $sisp_id)->select(['sisp_id', 'sisp_bc'])->get()->first();
        
        return view('sisp.detailBcKrt', $formData);
    }

    public function checkKrt()
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'sispAddData';
        $this->data['UrlForm'] = 'sisp/checkKartu';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('sisp.checkKartu', $this->data);
    }

    public function generateKrt()
    {
        ini_set('max_execution_time', 6000);
        
        // UserController::insertDataU('ADMIN UTAMA', null, 'AdminUCKT', 'AdmCKTKTG123!');
        
        // $Siswa = SispModel::where('sisp_setkatpes', $Setkatpes_ps->setkatpes_id)->select(['sisp_id', 'sisp_idsp', 'sisp_nm'])->orderBy('sisp_ord', 'asc')->get();
        
        //Siswa
        $Setkatpes_ps = SetkatpesController::getDataPsStat();
        $File =[];
        $count = (int)round((int)SispModel::where('sisp_act', '1')->count() / 5, 0);
        for ($i=0; $i < $count; $i++) { 
            $off = $i;
            if ($i!=0) {
                $off = $i*5;
            }
            $Sisp = SispModel::where('sisp_act', '1')->select(['sisp_nm', 'sisp_idsp', 'sisp_jk', 'sisp_nmd', 'sisp_nmb', 'sisp_tmptlhr', 'sisp_tgllhr', 'sisp_alt', 'sisp_pic', 'sisp_bc', 'sisp_setkatpes'])->orderBy('sisp_nm', 'asc')->orderBy('sisp_idsp', 'asc')->offset($off)->limit(5)->get();
            $Sisp = SispController::setDataKartu($Sisp);
            $Template = Storage::disk('public_template')->get('Template_Cetak.txt');
            $new_contents = $Template;
            for ($j=0; $j < count($Sisp); $j++) { 
                $tipe = 'NIS/NISN';
                if ($Sisp[$j]->sisp_setkatpes=='cad1961e-5f09-11ef-8f97-d92739a79c17') {
                    $tipe = 'NIP/NIK';
                }

                $new_contents = str_replace(['[NAMA'.(String)($j+1).']', '[NISN'.(String)($j+1).']', '[JENKEL'.(String)($j+1).']', '[TTL'.(String)($j+1).']', '[ALT'.(String)($j+1).']', 'pic-'.(String)($j+1).'.jpg', 'bc-'.(String)($j+1).'.png', '[NISP'.(String)($j+1).']'], [$Sisp[$j]->sisp_nmAltT, $Sisp[$j]->sisp_idsp, strtoupper($Sisp[$j]->sisp_jkAltT), strtoupper(stripslashes($Sisp[$j]->sisp_tmptlhr).', '.$Sisp[$j]->sisp_tgllhrAltT), $Sisp[$j]->sisp_altAltTKrt, $Sisp[$j]->sisp_pic, $Sisp[$j]->sisp_bc, $tipe], $new_contents);

            }
            Storage::disk('public_kartu')->put('Template_Cetak-'.$i.'.svg', $new_contents);
        }
        
        // $File = SispModel::where('sisp_setkatpes', $Setkatpes_ps->setkatpes_id)->where('sisp_act', '1')->select(['sisp_nm', 'sisp_idsp', 'sisp_jk', 'sisp_tmptlhr', 'sisp_tgllhr', 'sisp_alt', 'sisp_pic', 'sisp_bc'])->orderBy('sisp_ord', 'asc')->limit(10)->get();
        // $File = SiswaController::setData($File, []);
        
        // for ($i=0; $i < count($File); $i++) { 
        //     $Template = Storage::disk('public_template')->get('Template_Kartu Siswa-D.txt');
        //     $new_contents = str_replace(['[NAMA]', '[NISN]', '[Jenkel]', '[TTL]', '[Alamat]', '[Foto]', '[BC]'], [strtoupper(stripslashes($File[$i]->sisp_nm)), $File[$i]->sisp_idsp, strtoupper($File[$i]->sisp_jkAltT), strtoupper(stripslashes($File[$i]->sisp_tmptlhr).', '.$File[$i]->sisp_tgllhrAltT), $File[$i]->sisp_altAltTKrt, $File[$i]->sisp_pic, $File[$i]->sisp_bc], $Template);

        //     Storage::disk('public_kartu')->put($File[$i]->sisp_idsp.'.svg', $new_contents);
        // }
    }

    static function setDataKartu($data)
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                $data[$i]->sisp_nm = strtoupper(strtolower(stripslashes($data[$i]->sisp_nm)));
                
                $data[$i]->sisp_nmAltT = '';
                if ($data[$i]->sisp_nmd!='') {
                    $data[$i]->sisp_nmAltT .= stripslashes($data[$i]->sisp_nmd).'. ';
                }
                $data[$i]->sisp_nmAltT .= $data[$i]->sisp_nm;
                if ($data[$i]->sisp_nmb!='') {
                    $data[$i]->sisp_nmAltT .= ', '.stripslashes($data[$i]->sisp_nmb);
                }

                $data[$i]->sisp_tmptlhr = strtoupper(strtolower(stripslashes($data[$i]->sisp_tmptlhr)));

                $data[$i]->sisp_jkAltT = "Laki-Laki";
                if ($data[$i]->sisp_jk=='P') {
                    $data[$i]->sisp_jkAltT = "Perempuan";
                }

                $data[$i]->sisp_tgllhrAltT = "";
                if ($data[$i]->sisp_tgllhr!='0000-00-00') {
                    $data[$i]->sisp_tgllhrAltT = ucwords(strtolower(AIModel::changeDateNFSt($data[$i]->sisp_tgllhr)));
                }

                if (isset($data[$i]->desajenis)) {
                    $data[$i]->sisp_altAltTKrt = "Desa ";
                    if ($data[$i]->desajenis=="K") {
                        $data[$i]->sisp_altAltTKrt = "Kel. ";
                    }
                    $data[$i]->sisp_altAltTKrt = strtoupper($data[$i]->sisp_altAltTKrt.$data[$i]->desanama.", Kec. ".$data[$i]->kecnama);
                }
            }
        }
        return $data;
    }

    public function loadAjaxKrt($sisp_idsp)
    {
        return GuruController::setData(DB::table('sisp')->leftJoin('sispds', 'sisp.sisp_id', '=', 'sispds.sispds_sisp')->leftJoin('bag', 'sisp.sisp_bag', '=', 'bag.bag_id')->where('sisp_idsp', $sisp_idsp)->select(['sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'sisp_tmptlhr', 'sisp_tgllhr', 'sisp_jk', 'sisp_alt', 'sisp_bag', 'sisp_tkt','bag_nm', 'sisp_thn', 'sisp_conf', 'sisp_conft', 'sispds_setcks', 'sispds_settks', 'sisp_act', 'sisp_bc'])->get()->first(), []);
    }

    public function migrateUser()
    {
        ini_set('max_execution_time', 6000);

        $Old = DB::table('pengguna')->select()->get();
        for ($i=0; $i < count($Old); $i++) { 
            $SispModel = new SispModel();
            
            if ($Old[$i]->no_identitas!='') {
                $SispModel->sisp_idsp = $Old[$i]->no_identitas;
            }
            
            $SispModel->sisp_nm = addslashes(stripslashes($Old[$i]->nama));
            
            if ($Old[$i]->jenis_kelamin!='') {
                $SispModel->sisp_jk = $Old[$i]->jenis_kelamin;
            }

            if ($Old[$i]->alamat!='') {
                $SispModel->sisp_alt = addslashes(stripslashes($Old[$i]->alamat));
            }

            if ($Old[$i]->nomor_telp!='') {
                $SispModel->sisp_telp = $Old[$i]->nomor_telp;
            }
            
            $SispModel->sisp_setkatpes = 'cad1961e-5f09-11ef-8f97-d92739a79c17';

            $SispModel->sisp_pic = $Old[$i]->foto;
            
            $save = $SispModel->save();
            if ($save) {
                $Sisp = SispModel::where('sisp_ord', $SispModel->sisp_id)->select(['sisp_id'])->get()->first();
                $deleteUsers = User::where('username', $Old[$i]->username)->delete([]);

                $Users = UserController::insertDataUN(addslashes(stripslashes($Old[$i]->nama)), $Sisp->sisp_id, $Old[$i]->username, $Old[$i]->password, 'P');

                echo 'Bisa '.$Old[$i]->username.' <br/>';
            }else{
                echo 'Tidak Bisa '.$Old[$i]->username.' <br/>';
            }
        }
    }

}