<?php

namespace App\Http\Controllers;

use App\Models\AbsenModel;
use App\Models\AIModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;

class AbsenController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOAbsen',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'ABSENSI',
            'PageTitle' => 'Absensi',
            'BasePage' => 'absen',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'absenAddData';
        $this->data['UrlForm'] = 'absen';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        if ($this->data['Pgn']->users_tipe=="M") {
            return redirect()->intended();
        }

        if ($request->ajax()) {
           
            return AbsenController::loadData($this->data['Pgn'], $this->data);
        }

        $this->data['Now'] = "Data Absensi Terbaru";
        $this->data['Tahun'] = DB::table('absen')->select(DB::raw('YEAR(absen_tgl) year'))->groupBy('year')->orderBy('year', 'desc')->get();
        $this->data['Satker'] = DB::table('bag')->where('bag_str', '=', '2')->select(['bag_id', 'bag_nm'])->orderBy('bag_nm', 'asc')->get();

        $this->data['Agent'] = new Agent;

        $this->data['url'] = route('absen.index');
        $this->data['paramCtk'] = [
            'filters_satker' => '', 
            'filters_ppk' => '', 
            'filtert_tgl' => '', 
        ];

        return view('absen.index', $this->data);
    }

    public function load(){
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'absenAddData';
        $this->data['UrlForm'] = 'absen';

        $this->data['url'] = route('absen.index');
        $this->data['Now'] = "Data Absensi Terbaru";

        $this->data['paramCtk'] = [
            'filters_satker' => '', 
            'filters_ppk' => '', 
            'filtert_tgl' => '', 
        ];
        $this->data['Agent'] = new Agent;

        return view('absen.data', $this->data);
    }

    public function filter(Request $request)
    {
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'absenAddData';
        $this->data['UrlForm'] = 'absen';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        $this->data['filters_satker'] = '';
        $this->data['filters_ppk'] = '';
        $this->data['filtert_tgl'] = '';
        $this->data['filtert_blnthn'] = '';
        $this->data['filtert_blnbln'] = '';
        $this->data['filtert_smstr'] = '';
        $this->data['filtert_rentangawal'] = '';
        $this->data['filtert_rentangakhir'] = '';
        $this->data['filtert_pilih'] = '';

        $this->data['filtert_bagk'] = '';

        if ($request->post()) {
            $this->data['filters_satker'] = $request->post('filters_satker');
            $this->data['filters_ppk'] = $request->post('filters_ppk');
            $this->data['filtert_tgl'] = $request->post('filtert_tgl');
            $this->data['filtert_blnthn'] = $request->post('filtert_blnthn');
            $this->data['filtert_blnbln'] = $request->post('filtert_blnbln');
            $this->data['filtert_smstr'] = $request->post('filtert_smstr');
            $this->data['filtert_rentangawal'] = $request->post('filtert_rentangawal');
            $this->data['filtert_rentangakhir'] = $request->post('filtert_rentangakhir');
            $this->data['filtert_pilih'] = $request->post('filtert_pilih');

            $this->data['filtert_bagk'] = $request->post('filtert_bagk');

            $this->data['dataAjaxDT'] = "data:{'filters_satker':'".$request->post('filters_satker')."', 'filters_ppk':'".$request->post('filters_ppk')."', 'filtert_tgl':'".$request->post('filtert_tgl')."', 'filtert_blnthn':'".$request->post('filtert_blnthn')."', 'filtert_blnbln':'".$request->post('filtert_blnbln')."', 'filtert_smstr':'".$request->post('filtert_smstr')."', 'filtert_rentangawal':'".$request->post('filtert_rentangawal')."', 'filtert_rentangakhir':'".$request->post('filtert_rentangakhir')."', 'filtert_pilih':'".$request->post('filtert_pilih')."'}";

            $this->data['paramCtk'] = [
                'filters_satker' => $this->data['filters_satker'], 
                'filters_ppk' => $this->data['filters_ppk'], 
                'filtert_tgl' => $this->data['filtert_tgl'], 
                'filtert_blnthn' => $this->data['filtert_blnthn'], 
                'filtert_blnbln' => $this->data['filtert_blnbln'], 
                'filtert_smstr' => $this->data['filtert_smstr'], 
                'filtert_rentangawal' => $this->data['filtert_rentangawal'], 
                'filtert_rentangakhir' => $this->data['filtert_rentangakhir'], 
                'filtert_pilih' => $this->data['filtert_pilih']
            ];
        }

        $this->data['Agent'] = new Agent;

        $this->data['Label'] = $this->labelFilter($this->data);
        if($this->data['filtert_tgl']=='today'||$this->data['filtert_tgl'] =='kemarin'||$this->data['filtert_tgl'] =='pilih'||$this->data['filtert_tgl']==''){


            return view('absen.dataFilterH', $this->data);
        }
        return view('absen.dataFilterR', $this->data);
    }

    static function labelFilter($data):Array
    {
        $labelTanggal = "";
        if($data['filtert_tgl'] ==''){
            $labelTanggal = "Data Absensi Terakhir";
        }elseif($data['filtert_tgl'] =='today'){
            $labelTanggal = "Data Absensi Hari Ini (".AIModel::dayConvIntStr(date("w")).", ".date("d")." ".AIModel::monthConvIntSt((int)date("m"))." ".date("Y").")";
        }elseif($data['filtert_tgl'] =='kemarin'){
            $now = date("Y-m-d", strtotime("-1 days"));
            $labelTanggal = "Data Absensi Hari Kemarin (".AIModel::dayConvIntStr(date("w", strtotime($now))).", ".date("d", strtotime($now))." ".AIModel::monthConvIntSt((int)date("m", strtotime($now)))." ".date("Y", strtotime($now)).")";
        }elseif($data['filtert_tgl'] =='pilih'){
            $now = date("Y-m-d", strtotime($data['filtert_pilih']));
            $labelTanggal = "Data Absensi Hari ".AIModel::dayConvIntStr(date("w", strtotime($now))).", ".date("d", strtotime($now))." ".AIModel::monthConvIntSt((int)date("m", strtotime($now)))." ".date("Y", strtotime($now));
        }elseif($data['filtert_tgl'] =='minggu'){
            $now = date("Y-m-d");
            $today = (int)date('w');
            $week = date("Y-m-d", strtotime("-".(String)$today."days"));

            $labelTanggal = "Rekapan Data Absensi Minggu Ini (Dari ".AIModel::dayConvIntStr(date("w", strtotime($week))).", ".date("d", strtotime($week))." ".AIModel::monthConvIntSt((int)date("m", strtotime($week)))." ".date("Y", strtotime($week))." Sampai ".AIModel::dayConvIntStr(date("w", strtotime($now))).", ".date("d", strtotime($now))." ".AIModel::monthConvIntSt((int)date("m", strtotime($now)))." ".date("Y", strtotime($now)).")";
        }elseif($data['filtert_tgl'] =='bulan'){
            $labelTanggal = "Data Absensi Bulan ".AIModel::monthConvIntSt($data['filtert_blnbln'])." Tahun ".$data['filtert_blnthn'];
        }elseif($data['filtert_tgl'] =='semester'){
            $labelTanggal = "Rekapan Data Absensi Semester ".$data['filtert_smstr'];
        }elseif($data['filtert_tgl'] =='rentang'){
            
            $labelTanggal = "Rekapan Data Absensi Rentang Waktu (Dari ".AIModel::dayConvIntStr(date("w", strtotime($data['filtert_rentangawal']))).", ".date("d", strtotime($data['filtert_rentangawal']))." ".AIModel::monthConvIntSt((int)date("m", strtotime($data['filtert_rentangawal'])))." ".date("Y", strtotime($data['filtert_rentangawal']))." Sampai ".AIModel::dayConvIntStr(date("w", strtotime($data['filtert_rentangakhir']))).", ".date("d", strtotime($data['filtert_rentangakhir']))." ".AIModel::monthConvIntSt((int)date("m", strtotime($data['filtert_rentangakhir'])))." ".date("Y", strtotime($data['filtert_rentangakhir'])).")";
        }

        $labelKategori = "Pegawai";
        $labelBagian = "";
        
        if ($data['filters_ppk'] !='') {
            $Bag = BagController::getDataBag($data['filters_ppk']);
            $Bag1 = BagController::getDataBag($data['filters_satker']);
            $labelBagian = "SATKER: ".$Bag1->bag_nm.", PPK: ".$Bag->bag_nm;
        }elseif($data['filters_satker'] !=''&&$data['filters_ppk'] ==''){
            $Bag = BagController::getDataBag($data['filters_satker']);
            $labelBagian = "SATKER: ".$Bag->bag_nm;
        }
        

        return [
            'labelTanggal' => $labelTanggal,
            'labelKategori' => $labelKategori,
            'labelBagian' => $labelBagian,
        ];
    }

    public function getMonthByYear($year = '')
    {
        $this->data['Bulan'] = DB::table('absen')->select(DB::raw("DATE_FORMAT(absen_tgl, '%m') new_date"), DB::raw('MONTH(absen_tgl) month'))->whereYear('absen_tgl', '=', $year)->groupBy('month')->orderBy('month', 'asc')->get();

        $this->data['BulanN'] = [];
        for ($i=0; $i < count($this->data['Bulan']); $i++) { 
            $this->data['BulanN'][$i]['optValue'] = '';
            $this->data['BulanN'][$i]['optValue'] = $this->data['Bulan'][$i]->new_date;

            $this->data['BulanN'][$i]['optText'] = '';
            $this->data['BulanN'][$i]['optText'] = AIModel::monthConvIntSt((int)$this->data['Bulan'][$i]->new_date);
        }
        return $this->data['BulanN'];
    }

    static function loadData($Pgn, $formData, $satker = '', $ppk = '') {
        $Setkatpes_ps = SetkatpesController::getDataPsStat();

        DB::statement(DB::raw('set @rownum=0'));
        // $Absen = DB::table('absen')->leftJoin('bag', 'absen.absen_bag', '=', 'bag.bag_id')->leftJoin('sisp', 'absen.absen_sisp', '=', 'sisp.sisp_id')->where('absen_tgl', date("Y-m-d"));
        $Absen = DB::table('absen')->leftJoin('bag', 'absen.absen_bag', '=', 'bag.bag_id')->leftJoin('sisp', 'absen.absen_sisp', '=', 'sisp.sisp_id');

        if ($satker!='') {
            $Absen = $Absen->where('bag_prnt', $satker);
        }
        if ($ppk!='') {
            $Absen = $Absen->where('absen_bag', $ppk);
        }

        $Absen = $Absen->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'bag_nm', 'absen_tgl', 'absen_masuk', 'absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp','absen_sts'])->orderBy('absen_ord', 'desc')->limit(100)->get();
        
        $Absen = AbsenController::setData($Absen);
        return datatables()->of($Absen)->addColumn('dataMasuk', function($Absen){
            return $Absen->absen_masukAltT;
        })->addColumn('dataKeluar', function($Absen){
            return $Absen->absen_keluarAltT;
        })->rawColumns(['dataMasuk', 'dataKeluar'])->make(true);
    }

    public function datafilter(Request $request)
    {
        ini_set('max_execution_time', 6000);

        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'absenAddData';
        $this->data['UrlForm'] = 'absen';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        $this->data['filters_satker'] = '';
        $this->data['filters_ppk'] = '';
        $this->data['filtert_tgl'] = '';
        $this->data['filtert_blnthn'] = '';
        $this->data['filtert_blnbln'] = '';
        $this->data['filtert_smstr'] = '';
        $this->data['filtert_rentangawal'] = '';
        $this->data['filtert_rentangakhir'] = '';
        $this->data['filtert_pilih'] = '';

        
        if ($request->ajax()) {
            $this->data['filters_satker'] = '';
            $this->data['filters_ppk'] = '';
            $this->data['filtert_tgl'] = '';
            $this->data['filtert_blnthn'] = '';
            $this->data['filtert_blnbln'] = '';
            $this->data['filtert_smstr'] = '';
            $this->data['filtert_rentangawal'] = '';
            $this->data['filtert_rentangakhir'] = '';
            $this->data['filtert_pilih'] = '';
            
            if ($request->post('filters_satker')!='') {
                $this->data['filters_satker'] = $request->post('filters_satker');
            }
            if ($request->post('filters_ppk')!='') {
                $this->data['filters_ppk'] = $request->post('filters_ppk');
            }
            if ($request->post('filtert_tgl')!='') {
                $this->data['filtert_tgl'] = $request->post('filtert_tgl');
            }
            if ($request->post('filtert_blnthn')!='') {
                $this->data['filtert_blnthn'] = $request->post('filtert_blnthn');
            }
            if ($request->post('filtert_blnbln')!='') {
                $this->data['filtert_blnbln'] = $request->post('filtert_blnbln');
            }
            if ($request->post('filtert_smstr')!='') {
                $this->data['filtert_smstr'] = $request->post('filtert_smstr');
            }
            if ($request->post('filtert_rentangawal')!='') {
                $this->data['filtert_rentangawal'] = $request->post('filtert_rentangawal');
            }
            if ($request->post('filtert_rentangakhir')!='') {
                $this->data['filtert_rentangakhir'] = $request->post('filtert_rentangakhir');
            }
            if ($request->post('filtert_pilih')!='') {
                $this->data['filtert_pilih'] = $request->post('filtert_pilih');
            }
            return AbsenController::loadDataFilter($this->data['Pgn'], $this->data);
        }
    }

    static function loadDataFilter($Pgn, $formData, $dt = true) {

        
        ini_set('max_execution_time', 6000);

        DB::statement(DB::raw('set @rownum=0'));

        $Bag = 'absen_bag';
        $BagNm = 'bag_nm';
        
        $Absen = DB::table('absen')->leftJoin('bag', 'absen.absen_bag', '=', 'bag.bag_id')->leftJoin('sisp', 'absen.absen_sisp', '=', 'sisp.sisp_id');
        
        if($formData['filtert_tgl'] =='minggu'||$formData['filtert_tgl'] =='bulan'||$formData['filtert_tgl'] =='semester'||$formData['filtert_tgl'] =='rentang'){
            $Bag = 'sisp_bag';
            $Absen = DB::table('sisp')->leftJoin('bag', 'sisp.sisp_bag', '=', 'bag.bag_id')->where('sisp_act', '=', '1');
        }

        if ($formData['filters_ppk'] !='') {
            $Absen = $Absen->where('bag_id', $formData['filters_ppk']);
        }elseif($formData['filters_satker'] !=''&&$formData['filters_ppk'] ==''){
            $Absen = $Absen->where('bag_prnt', $formData['filters_satker']);
        }

        if($formData['filtert_tgl'] =='today'||$formData['filtert_tgl'] =='kemarin'||$formData['filtert_tgl'] =='pilih'||$formData['filtert_tgl'] ==''){
            if($formData['filtert_tgl'] =='today'||$formData['filtert_tgl'] ==''){
                $Absen = $Absen->where('absen_tgl', date("Y-m-d"));
            }elseif($formData['filtert_tgl'] =='kemarin'){
                $Absen = $Absen->where('absen_tgl', date("Y-m-d", strtotime("-1 days")));
            }elseif($formData['filtert_tgl'] =='pilih'){
                $Absen = $Absen->where('absen_tgl', $formData['filtert_pilih']);
            }
            $Absen = $Absen->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'bag_nm', 'absen_tgl', 'absen_masuk', 'absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp','absen_sts'])->orderBy('absen_masuk', 'asc')->get();
            
            $Absen = AbsenController::setData($Absen);
            if ($dt) {
                return datatables()->of($Absen)->addColumn('dataMasuk', function($Absen){
                    return $Absen->absen_masukAltT;
                })->addColumn('dataKeluar', function($Absen){
                    return $Absen->absen_keluarAltT;
                })->rawColumns(['dataMasuk', 'dataKeluar'])->make(true);
            }else{
                return $Absen;
            }
        }else{
            $Absen = $Absen->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'bag_nm'])->orderBy('sisp_nm', 'asc')->get();
            
            for ($i=0; $i < count($Absen); $i++) { 
                //Kehadiran
                $Absen[$i]->h = 0;
                $Absen[$i]->Th = 0;
                $Absen[$i]->I = 0;
                $H = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H');
                $Th = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'TH');
                $I = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'I');
                
                //Absensi
                $Absen[$i]->absen_masukk = 0;
                $Absen[$i]->absen_keluark = 0;
                $absen_masukk = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H')->where('absen_masukk', '0');
                $absen_keluark = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H')->where('absen_keluark', '0');
                
                //Disiplin
                $Absen[$i]->lmbt = 0;
                $Absen[$i]->cd = 0;
                $Absen[$i]->lbh = 0;
                $Absen[$i]->cp = 0;
                $lmbt = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H');
                $cd = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H');
                $lbh = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H');
                $cp = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H');

                if($formData['filtert_tgl'] =='minggu'){
                    $today = (int)date('w');
                    $week = date("Y-m-d", strtotime("-".(String)$today."days"));

                    $H = $H->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $Th = $Th->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $I = $I->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);

                    $absen_masukk = $absen_masukk->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $absen_keluark = $absen_keluark->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);

                    $lmbt = $lmbt->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $cd = $cd->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $lbh = $lbh->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $cp = $cp->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);

                }elseif($formData['filtert_tgl'] =='bulan'){
                    $Year = date("Y");
                    $Month = date("m");
                    if ($formData['filtert_blnthn'] !='') {
                        $Year = $formData['filtert_blnthn'];
                    }
                    if ($formData['filtert_blnbln'] !='') {
                        $Month = $formData['filtert_blnbln'];
                    }

                    $H = $H->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $Th = $Th->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $I = $I->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);

                    $absen_masukk = $absen_masukk->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $absen_keluark = $absen_keluark->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);

                    $lmbt = $lmbt->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $cd = $cd->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $lbh = $lbh->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $cp = $cp->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);

                }elseif($formData['filtert_tgl'] =='rentang'){
                    if ($formData['filtert_rentangakhir']!=''&&$formData['filtert_rentangawal']!='') {
                        $H = $H->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $Th = $Th->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $I = $I->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);

                        $absen_masukk = $absen_masukk->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $absen_keluark = $absen_keluark->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);

                        $lmbt = $lmbt->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $cd = $cd->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $lbh = $lbh->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $cp = $cp->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                    }
                }

                // dd($H->get()->count(), $Th->get()->count(), $I->get()->count());
                $Absen[$i]->h = $H->get()->count();
                $Absen[$i]->Th = $Th->get()->count();
                $Absen[$i]->I = $I->get()->count();

                $Absen[$i]->absen_masukk = $absen_masukk->get()->count();
                $Absen[$i]->absen_keluark = $absen_keluark->get()->count();

                $Absen[$i]->lmbt = $lmbt->get()->sum('absen_lmbt');
                $Absen[$i]->cd = $cd->get()->sum('absen_cd');
                $Absen[$i]->lbh = $lbh->get()->sum('absen_lbh');
                $Absen[$i]->cp = $cp->get()->sum('absen_cp');
            }
            $Absen = GuruController::setData($Absen, $formData);
            

            if ($dt) {
                return datatables()->of($Absen)->addColumn('dataKehadiran', function($Absen){
                    return 'Hadir: '.$Absen->h.'<br/>Tidak Hadir: '.$Absen->Th.'<br/>Izin: '.$Absen->I;
                })->addColumn('dataAbsensi', function($Absen){
                    return 'Tidak Absen Masuk: '.$Absen->absen_masukk.'<br/>Tidak Absen Pulang: '.$Absen->absen_keluark;
                })->addColumn('dataDisiplin', function($Absen){
                    return 'Cepat Datang: '.$Absen->cd.'<br/>Lambat: '.$Absen->lmbt.'<br/>Lebih Pulang: '.$Absen->lbh.'<br/>Cepat Pulang: '.$Absen->cp;
                })->rawColumns(['dataKehadiran', 'dataAbsensi', 'dataDisiplin'])->make(true);
            }else{
                return $Absen;
            }
        }
    }

    static function loadDataFilterSisp($formData, $sisp = '') {

        
        ini_set('max_execution_time', 6000);

        DB::statement(DB::raw('set @rownum=0'));

        $Bag = 'absen_bag';
        $BagNm = 'bag_nm';
        
        $Absen = DB::table('absen')->leftJoin('bag', 'absen.absen_bag', '=', 'bag.bag_id')->leftJoin('sisp', 'absen.absen_sisp', '=', 'sisp.sisp_id');
        $Absen = $Absen->where('absen_sisp', $sisp);
        
        if($formData['filtert_tgl'] =='minggu'||$formData['filtert_tgl'] =='bulan'||$formData['filtert_tgl'] =='semester'||$formData['filtert_tgl'] =='rentang'){
            $Bag = 'sisp_bag';
            $Absen = DB::table('sisp')->leftJoin('bag', 'sisp.sisp_bag', '=', 'bag.bag_id')->where('sisp_act', '=', '1');
            $Absen = $Absen->where('sisp_id', $sisp);
        }


        if($formData['filtert_tgl'] =='today'||$formData['filtert_tgl'] =='kemarin'||$formData['filtert_tgl'] =='pilih'||$formData['filtert_tgl'] ==''){
            if($formData['filtert_tgl'] =='today'||$formData['filtert_tgl'] ==''){
                $Absen = $Absen->where('absen_tgl', date("Y-m-d"));
            }elseif($formData['filtert_tgl'] =='kemarin'){
                $Absen = $Absen->where('absen_tgl', date("Y-m-d", strtotime("-1 days")));
            }elseif($formData['filtert_tgl'] =='pilih'){
                $Absen = $Absen->where('absen_tgl', $formData['filtert_pilih']);
            }
            $Absen = $Absen->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'bag_nm', 'absen_tgl', 'absen_masuk', 'absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp','absen_sts'])->orderBy('absen_masuk', 'asc')->get();
            
            $Absen = AbsenController::setData($Absen);
            
            return $Absen;
        }else{
            $Absen = $Absen->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'bag_nm'])->orderBy('sisp_nm', 'asc')->get();
            
            for ($i=0; $i < count($Absen); $i++) { 
                //Kehadiran
                $Absen[$i]->h = 0;
                $Absen[$i]->Th = 0;
                $Absen[$i]->I = 0;
                $H = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H');
                $Th = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'TH');
                $I = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'I');
                
                //Absensi
                $Absen[$i]->absen_masukk = 0;
                $Absen[$i]->absen_keluark = 0;
                $absen_masukk = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H')->where('absen_masukk', '0');
                $absen_keluark = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H')->where('absen_keluark', '0');
                
                //Disiplin
                $Absen[$i]->lmbt = 0;
                $Absen[$i]->cd = 0;
                $Absen[$i]->lbh = 0;
                $Absen[$i]->cp = 0;
                $lmbt = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H');
                $cd = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H');
                $lbh = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H');
                $cp = DB::table('absen')->where('absen_sisp', $Absen[$i]->sisp_id)->where('absen_sts', 'H');

                if($formData['filtert_tgl'] =='minggu'){
                    $today = (int)date('w');
                    $week = date("Y-m-d", strtotime("-".(String)$today."days"));

                    $H = $H->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $Th = $Th->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $I = $I->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);

                    $absen_masukk = $absen_masukk->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $absen_keluark = $absen_keluark->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);

                    $lmbt = $lmbt->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $cd = $cd->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $lbh = $lbh->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
                    $cp = $cp->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);

                }elseif($formData['filtert_tgl'] =='bulan'){
                    $Year = date("Y");
                    $Month = date("m");
                    if ($formData['filtert_blnthn'] !='') {
                        $Year = $formData['filtert_blnthn'];
                    }
                    if ($formData['filtert_blnbln'] !='') {
                        $Month = $formData['filtert_blnbln'];
                    }

                    $H = $H->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $Th = $Th->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $I = $I->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);

                    $absen_masukk = $absen_masukk->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $absen_keluark = $absen_keluark->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);

                    $lmbt = $lmbt->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $cd = $cd->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $lbh = $lbh->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
                    $cp = $cp->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);

                }elseif($formData['filtert_tgl'] =='rentang'){
                    if ($formData['filtert_rentangakhir']!=''&&$formData['filtert_rentangawal']!='') {
                        $H = $H->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $Th = $Th->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $I = $I->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);

                        $absen_masukk = $absen_masukk->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $absen_keluark = $absen_keluark->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);

                        $lmbt = $lmbt->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $cd = $cd->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $lbh = $lbh->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                        $cp = $cp->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);
                    }
                }

                // dd($H->get()->count(), $Th->get()->count(), $I->get()->count());
                $Absen[$i]->h = $H->get()->count();
                $Absen[$i]->Th = $Th->get()->count();
                $Absen[$i]->I = $I->get()->count();

                $Absen[$i]->absen_masukk = $absen_masukk->get()->count();
                $Absen[$i]->absen_keluark = $absen_keluark->get()->count();

                $Absen[$i]->lmbt = $lmbt->get()->sum('absen_lmbt');
                $Absen[$i]->cd = $cd->get()->sum('absen_cd');
                $Absen[$i]->lbh = $lbh->get()->sum('absen_lbh');
                $Absen[$i]->cp = $cp->get()->sum('absen_cp');
            }
            $Absen = GuruController::setData($Absen, $formData);
            
            return $Absen;
        }
    }

    static function setData($data)
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                
                $data[$i]->sisp_nm = ucwords(strtolower(stripslashes($data[$i]->sisp_nm)));
                
                $data[$i]->sisp_nmAltT = '';
                if ($data[$i]->sisp_nmd!='') {
                    $data[$i]->sisp_nmAltT .= stripslashes($data[$i]->sisp_nmd).'. ';
                }
                $data[$i]->sisp_nmAltT .= $data[$i]->sisp_nm;
                if ($data[$i]->sisp_nmb!='') {
                    $data[$i]->sisp_nmAltT .= ', '.stripslashes($data[$i]->sisp_nmb);
                }
                
                $data[$i]->absen_tglAltT = "";
                if ($data[$i]->absen_tgl!='0000-00-00') {
                    $data[$i]->absen_tglAltT = ucwords(strtolower(AIModel::changeDateNFSt($data[$i]->absen_tgl)));
                }

                $data[$i]->absen_masukAltT = "";
                if (isset($data[$i]->absen_masukk)) {
                    $data[$i]->absen_masukAltT = "Masuk: ".$data[$i]->absen_masuk."<br/>Cepat Datang: ".$data[$i]->absen_cd." Menit<br/>Lambat: ".$data[$i]->absen_lmbt." Menit";
                    if ($data[$i]->absen_masukk=="0") {
                        $data[$i]->absen_masukAltT = "Tidak Melakukan Absen Masuk";
                    }
                }

                $data[$i]->absen_keluarAltT = "";
                if (isset($data[$i]->absen_keluark)) {
                    $data[$i]->absen_keluarAltT = "Keluar: ".$data[$i]->absen_keluar."<br/>Cepat Pulang: ".$data[$i]->absen_cp." Menit<br/>Lambat Pulang: ".$data[$i]->absen_lbh." Menit";
                    if ($data[$i]->absen_keluark=="0") {
                        $data[$i]->absen_keluarAltT = "Tidak Melakukan Absen Keluar";
                    }
                }

                $data[$i]->absen_tipeAltT = '';
                if (isset($data[$i]->absen_tipe)) {
                    $data[$i]->absen_tipeAltT = $data[$i]->absen_tipe;
                    if($data[$i]->absen_tipe == 'O'){
                        $data[$i]->absen_tipeAltT = 'ON-SITE';
                    }elseif ($data[$i]->absen_tipe == 'D') {
                        $data[$i]->absen_tipeAltT = 'Dinas';
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

            $data->absen_tglAltT = "";
            if ($data->absen_tgl!='0000-00-00') {
                $data->absen_tglAltT = ucwords(strtolower(AIModel::changeDateNFSt($data->absen_tgl)));
            }

            $data->absen_masukAltT = "";
            if (isset($data->absen_masukk)) {
                $data->absen_masukAltT = "Masuk: ".$data->absen_masuk."<br/>Cepat Datang: ".$data->absen_cd."<br/>Lambat: ".$data->absen_lmbt;
                if ($data->absen_masukk=="0") {
                    $data->absen_masukAltT = "Tidak Melakukan Absen Masuk";
                }
            }

            $data->absen_keluarAltT = "";
            if (isset($data->absen_keluark)) {
                $data->absen_keluarAltT = "Keluar: ".$data->absen_keluar."<br/>Cepat Pulang: ".$data->absen_cp."<br/>Lambat Pulang: ".$data->absen_lbh;
                if ($data->absen_keluark=="0") {
                    $data->absen_keluarAltT = "Tidak Melakukan Absen Keluar";
                }
            }

            $data->absen_tipeAltT = '';
            if (isset($data->absen_tipe)) {
                $data->absen_tipeAltT = $data->absen_tipe;
                if($data->absen_tipe == 'O'){
                    $data->absen_tipeAltT = 'ON-SITE';
                }elseif ($data->absen_tipe == 'D') {
                    $data->absen_tipeAltT = 'Dinas';
                }
            }
        }
        return $data;
    }

    public function insertData(Request $request)
    {
        ini_set('max_execution_time', 6000);
        $this->data['Pgn'] = $this->getUser();
        $Sisp = DB::table('sisp')->where('sisp_idsp', $request->kartu)->select(['sisp_id'])->get()->first();
        if ($Sisp==null) {
            $data['response'] = ['status' => 404, 'response' => 'error','type' => "danger", 'message' => 'Data Peserta Tidak Ditemukan'];
        }else{
            $data = $this->prosesAbsen($this->data['Pgn'], $request->tipe, $Sisp->sisp_id, date("Y-m-d H:i:s"));
        }
        return response()->json($data, $data['response']['status']);
    }

    public function insertDataM(Request $request)
    {
        ini_set('max_execution_time', 6000);
        $this->data['Pgn'] = $this->getUser();
        $Sisp = DB::table('sisp')->where('sisp_id', $request->absen_sisp)->select(['sisp_id'])->get()->first();
        if ($Sisp==null) {
            $data['response'] = ['status' => 404, 'response' => 'error','type' => "danger", 'message' => 'Data Peserta Tidak Ditemukan'];
        }else{
            $img = $request->image;

            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            
            $filename = 'pic-masuk-'.$Sisp->sisp_id.'-'.date("Y-m-d-H-i-s").".png";

            $data = $this->prosesMasuk($this->data['Pgn'], $Sisp->sisp_id, date("Y-m-d H:i:s"), $request->long, $request->lat, $request->tipe, $filename);
            if ($data['response']['status'] == 200) {
                Storage::put('/public/uploads/'.$filename, $image_base64);
                // $file = $request->file('pic')->storeAs('/public/uploads', $filename );
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $Sisp = DB::table('sisp')->where('sisp_id', $request->absen_sisp)->select(['sisp_id'])->get()->first();
        if ($Sisp==null) {
            $data['response'] = ['status' => 404, 'response' => 'error','type' => "danger", 'message' => 'Data Peserta Tidak Ditemukan'];
        }else{
            $img = $request->image;

            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            
            $filename = 'pic-pulang-'.$Sisp->sisp_id.'-'.date("Y-m-d-H-i-s").".png";

            $data = $this->prosesKeluar($request->absen_id, $this->data['Pgn'], $Sisp->sisp_id, date("Y-m-d H:i:s"), $request->long, $request->lat, $request->tipe, $filename);
            if ($data['response']['status'] == 200) {
                Storage::put('/public/uploads/'.$filename, $image_base64);
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function detailProfil($absen_sisp, $month = '', $year = '')
    {
        if ($month=='') {
            $month = date("m");
        }
        if ($year=='') {
            $year = date("Y");
        }
        $this->data['Pgn'] = $this->getUser();
        $this->data['absen_sisp'] = $absen_sisp;

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'absenProfAddData';
        $this->data['UrlForm'] = 'absen';
        $this->data['cal'] = [];
        $this->data['year'] = $year;
        $this->data['month'] = $month;
        $this->data['monthN'] = AIModel::monthConvIntSt($month);

        $this->data['yearSelect'] = DB::table('absen')->select([DB::raw('YEAR(absen_tgl) year, MONTH(absen_tgl) month')])->groupby('year')->get();

        $this->data['Agent'] = new Agent;
        
        $this->data['Agent'] = new Agent;
        $this->data['list'] = DB::table('absen')->leftJoin('sisp', 'sisp.sisp_id', '=', 'absen.absen_sisp')->leftJoin('abseni', 'absen.absen_id', '=', 'abseni.abseni_absen')->leftJoin('sispi', 'abseni.abseni_sispi', '=', 'sispi.sispi_id')->where('absen_sisp', $absen_sisp)->whereYear('absen_tgl', '=', $year)->whereMonth('absen_tgl', '=', $month)->select(['sisp_nm', 'sisp_idsp', 'absen_tgl','absen_masuk','absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp', 'absen_sts', 'sispi_tiket', 'sispi_fl', 'sispi_fle', 'absen_id'])->orderBy('absen_tgl', 'desc')->get();
        // dd($this->data['cal']);
        return view('absen.profil', $this->data);
    }

    public function filterDataProfil(Request $request, $id)
    {
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'absenAddData';
        $this->data['UrlForm'] = 'absen';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        $this->data['year'] = '';
        $this->data['month'] = '';
        $this->data['absen_sisp'] = $id;
        $this->data['search'] = true;
        $this->data['Agent'] = new Agent;
        
        if ($request->post()) {
            $this->data['year'] = $request->post('filter_tahun');
            $this->data['month'] = $request->post('filter_bulan');
        }
        $this->data['monthN'] = AIModel::monthConvIntSt($this->data['month']);

        $this->data['cal'] = AbsenController::getCalendar($id, $this->data['month'], $this->data['year']);
        $this->data['list'] = DB::table('absen')->leftJoin('sisp', 'sisp.sisp_id', '=', 'absen.absen_sisp')->leftJoin('abseni', 'absen.absen_id', '=', 'abseni.abseni_absen')->leftJoin('sispi', 'abseni.abseni_sispi', '=', 'sispi.sispi_id')->where('absen_sisp', $this->data['absen_sisp'])->whereYear('absen_tgl', '=', $this->data['year'])->whereMonth('absen_tgl', '=', $this->data['month'])->select(['sisp_nm', 'sisp_idsp', 'absen_tgl','absen_masuk','absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp', 'absen_sts', 'sispi_tiket', 'sispi_fl', 'sispi_fle', 'absen_id'])->orderBy('absen_tgl', 'desc')->get();
        
        return view('absen.dataProfil', $this->data);
    }

    public function getDataBulanKelas($tahun)
    {
        $this->data['Bulan'] = DB::table('absen')->select([DB::raw('YEAR(absen_tgl) year, MONTH(absen_tgl) month')])->whereYear('absen_tgl', $tahun)->orderBy('month', 'asc')->groupBy('month')->get();

        $this->data['BulanN'] = [];
        for ($i=0; $i < count($this->data['Bulan']); $i++) { 
            $this->data['BulanN'][$i]['optValue'] = '';
            $this->data['BulanN'][$i]['optValue'] = $this->data['Bulan'][$i]->month;

            $this->data['BulanN'][$i]['optText'] = '';
            $this->data['BulanN'][$i]['optText'] = AIModel::monthConvIntSt($this->data['Bulan'][$i]->month);
        }
        return $this->data['BulanN'];
    }

    public function detailProfilCal($absen_sisp, $month = '', $year = '')
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['sispi_sisp'] = $absen_sisp;

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'absenProfCalAddData';
        $this->data['UrlForm'] = 'absen';
        $this->data['cal'] = [];
        $this->data['year'] = $year;
        $this->data['month'] = $month;
        $this->data['absen_sisp'] = $absen_sisp;
        $this->data['monthN'] = AIModel::monthConvIntSt($month);

        $this->data['Agent'] = new Agent;
        $this->data['cal'] = AbsenController::getCalendar($absen_sisp, $month, $year);

        return view('absen.calProfil', $this->data);
    }

    public function detailProfilList($absen_sisp, $month = '', $year = '')
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['sispi_sisp'] = $absen_sisp;

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'absenProfCalAddData';
        $this->data['UrlForm'] = 'absen';
        $this->data['cal'] = [];
        $this->data['year'] = $year;
        $this->data['month'] = $month;
        $this->data['absen_sisp'] = $absen_sisp;
        $this->data['monthN'] = AIModel::monthConvIntSt($month);

        $this->data['Agent'] = new Agent;
        $this->data['list'] = DB::table('absen')->leftJoin('sisp', 'sisp.sisp_id', '=', 'absen.absen_sisp')->leftJoin('abseni', 'absen.absen_id', '=', 'abseni.abseni_absen')->leftJoin('sispi', 'abseni.abseni_sispi', '=', 'sispi.sispi_id')->where('absen_sisp', $absen_sisp)->whereYear('absen_tgl', '=', $year)->whereMonth('absen_tgl', '=', $month)->select(['sisp_nm', 'sisp_idsp', 'absen_tgl','absen_masuk','absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp', 'absen_sts', 'sispi_tiket', 'sispi_fl', 'sispi_fle', 'absen_id'])->orderBy('absen_tgl', 'desc')->get();

        return view('absen.listProfil', $this->data);
    }

    public function loadAjax($absen_id)
    {
        $data = DB::table('absen')->leftJoin('sisp', 'sisp.sisp_id', '=', 'absen.absen_sisp')->leftJoin('abseni', 'absen.absen_id', '=', 'abseni.abseni_absen')->leftJoin('sispi', 'abseni.abseni_sispi', '=', 'sispi.sispi_id')->where('absen_id', $absen_id)->select(['sisp_nm', 'sisp_idsp', 'absen_tgl','absen_masuk','absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp', 'absen_sts', 'sispi_tiket', 'absen_masuklok', 'absen_keluarlok', 'absen_masukpic', 'absen_keluarpic', 'absen_setkatpesj'])->get()->first();

        $Setkatpesj = [];

        if ($data!=null) {
            $Setkatpesj = DB::table('setkatpesj')->where('setkatpesj_id', $data->absen_setkatpesj)->select(['setkatpesj_masuk', 'setkatpesj_keluar'])->get()->first();
        }

        $data->absen_psn = 'Pulang Tepat Waktu';
        if ($data->absen_sts=="TH"||$data->absen_sts=="I") {
            $data->absen_psn = 'Status Absensi Tidak Hadir';
        }else{
            if((int)$data->absen_lmbt>0){
                if ($Setkatpesj!=null) {
                    $MenitTambahan = date('H:i', strtotime("+".(string)$data->absen_lmbt." minutes",strtotime($Setkatpesj->setkatpesj_keluar)));
                    $data->absen_psn = 'Harus Absen Pulang Pada Pukul '.(string)$MenitTambahan;
                }
            }
        }

        $data->absen_lmbt = (string)intdiv((int)$data->absen_lmbt, 60)." Jam,".(string)((int)$data->absen_lmbt % 60)." Menit.";
        $data->absen_lbh = (string)intdiv((int)$data->absen_lbh, 60)." Jam,".(string)((int)$data->absen_lbh % 60)." Menit.";
        $data->absen_cp = (string)intdiv((int)$data->absen_cp, 60)." Jam,".(string)((int)$data->absen_cp % 60)." Menit.";
        $data->absen_cd = (string)intdiv((int)$data->absen_cd, 60)." Jam,".(string)((int)$data->absen_cd % 60)." Menit.";

        return $data;
    }

    static function getAbsenByDate($absen_sisp, $absen_tgl)
    {
        $data = DB::table('absen')->leftJoin('sisp', 'sisp.sisp_id', '=', 'absen.absen_sisp')->leftJoin('abseni', 'absen.absen_id', '=', 'abseni.abseni_absen')->leftJoin('sispi', 'abseni.abseni_sispi', '=', 'sispi.sispi_id')->where('absen_sisp', $absen_sisp)->where('absen_tgl', $absen_tgl)->select(['sisp_nm', 'sisp_idsp', 'absen_tgl','absen_masuk','absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp', 'absen_sts', 'sispi_tiket', 'absen_masuklok', 'absen_keluarlok', 'absen_masukpic', 'absen_keluarpic', 'absen_tipe'])->get()->first();
        if ($data!=null) {
            $data->absen_lmbt = (string)intdiv((int)$data->absen_lmbt, 60)." Jam,".(string)((int)$data->absen_lmbt % 60)." Menit.";
            $data->absen_lbh = (string)intdiv((int)$data->absen_lbh, 60)." Jam,".(string)((int)$data->absen_lbh % 60)." Menit.";
            $data->absen_cp = (string)intdiv((int)$data->absen_cp, 60)." Jam,".(string)((int)$data->absen_cp % 60)." Menit.";
            $data->absen_cd = (string)intdiv((int)$data->absen_cd, 60)." Jam,".(string)((int)$data->absen_cd % 60)." Menit.";
        }

        return $data;
    }

    static function getAbsenByMonth($absen_sisp, $month, $year)
    {
        $data = DB::table('absen')->leftJoin('sisp', 'sisp.sisp_id', '=', 'absen.absen_sisp')->leftJoin('abseni', 'absen.absen_id', '=', 'abseni.abseni_absen')->leftJoin('sispi', 'abseni.abseni_sispi', '=', 'sispi.sispi_id')->where('absen_sisp', $absen_sisp)->whereMonth('absen_tgl', '=',$month)->whereYear('absen_tgl', '=',$year)->select(['sisp_nm', 'sisp_nmd', 'sisp_nmb', 'sisp_idsp', 'absen_tgl','absen_masuk','absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp', 'absen_sts', 'sispi_tiket', 'absen_masuklok', 'absen_keluarlok', 'absen_masukpic', 'absen_keluarpic', 'absen_tipe'])->orderBy('absen_tgl', 'desc')->get();
        if (count($data)>0) {
            for ($i=0; $i < count($data); $i++) { 
                $data[$i]->absen_lmbt = (string)intdiv((int)$data[$i]->absen_lmbt, 60)." Jam,".(string)((int)$data[$i]->absen_lmbt % 60)." Menit.";
                $data[$i]->absen_lbh = (string)intdiv((int)$data[$i]->absen_lbh, 60)." Jam,".(string)((int)$data[$i]->absen_lbh % 60)." Menit.";
                $data[$i]->absen_cp = (string)intdiv((int)$data[$i]->absen_cp, 60)." Jam,".(string)((int)$data[$i]->absen_cp % 60)." Menit.";
                $data[$i]->absen_cd = (string)intdiv((int)$data[$i]->absen_cd, 60)." Jam,".(string)((int)$data[$i]->absen_cd % 60)." Menit.";
            }
        }

        return $data;
    }

    static function getCalendar($absen_sisp, $month = '', $year):array
    {
        if (strlen($month)==1) {
            $month = '0'.$month;
        }
        $jmlhH = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $cal = [
            ['val' => 'S',],
            ['val' => 'S',],
            ['val' => 'R',],
            ['val' => 'K',],
            ['val' => 'J',],
            ['val' => 'S',],
            ['val' => 'M',],
            
        ];
        $cal = AbsenController::getFirstDate($cal, $month, $year);
        for ($i=0; $i < $jmlhH; $i++) { 
            $hari = $i+1;
            $Absen = DB::table('absen')->where('absen_sisp', $absen_sisp)->where('absen_tgl', $year.'-'.$month.'-'.(string)$hari)->select(['absen_id', 'absen_sts', 'absen_masukk', 'absen_keluark', 'absen_lmbt', 'absen_cp'])->get()->first();
            if ($Absen==null) {
                array_push($cal, ['val' => $hari]);
            }else{
                $color = 'success';
                if ($Absen->absen_sts == 'TH') {
                    $color = 'danger';
                }elseif($Absen->absen_sts == 'I'){
                    $color = 'primary';
                }

                $nodeColor = '';
                if ($Absen->absen_sts == 'H') {
                    if ((int)$Absen->absen_lmbt>0||(int)$Absen->absen_cp>0) {
                        $nodeColor = 'warning';
                    }
                    if ($Absen->absen_masukk=="0"||(int)$Absen->absen_keluark=="0") {
                        $nodeColor = 'danger';
                    }
                }
                if ($nodeColor=='') {
                    array_push($cal, ['val' => $hari, 'id' => $Absen->absen_id, 'color' => $color]);
                }else{
                    array_push($cal, ['val' => $hari, 'id' => $Absen->absen_id, 'color' => $color, 'node' => $nodeColor]);
                }
            }
        }

        return $cal;
    }

    static function getFirstDate($cal, $month, $year): array
    {
        $start = date("N", strtotime($year.'-'.$month.'-01'));
        $jmlh = 7 - (int)$start;
        if ($start == 7) {
            $jmlh = 6;
        }elseif($start == 6){
            $jmlh = 5;
        }elseif($start == 5){
            $jmlh = 4;
        }elseif($start == 4){
            $jmlh = 3;
        }elseif($start == 3){
            $jmlh = 2;
        }elseif($start == 2){
            $jmlh = 1;
        }elseif($start == 1){
            $jmlh = 0;
        }
        for ($i=0; $i < $jmlh; $i++) { 
            array_push($cal, ['val' => '']);
        }
        return $cal;
    }

    static function getOldAbsen($absen_sisp, $absen_tgl)
    {
        return DB::table('absen')->where('absen_sisp', $absen_sisp)->where('absen_tgl', $absen_tgl)->select(['absen_id', 'absen_masuk', 'absen_keluar'])->get()->first();
    }

    public function tesAbsen()
    {
        $this->data['Pgn'] = $this->getUser();

        ini_set('max_execution_time', 6000);

        $success = [];
        
        $Sisp = DB::table('sisp')->whereNotIn('sisp_tipes', ['0'])->select(['sisp_id', 'sisp_tipes'])->get();
        $Hari = [30,31,31,30];
        for ($i=0; $i < count($Hari); $i++) { 
            for ($j=0; $j < $Hari[$i]; $j++) { 
                for ($k=0; $k < count($Sisp); $k++) { 
                    $datetime = $j+1;
                    if (strlen((string)$datetime)==1) {
                        $datetime = '0'.$datetime;
                    }
                    if ($i==0) {
                        $datetime = '2024-06-'.$datetime;
                    }elseif ($i==1) {
                        $datetime = '2024-07-'.$datetime;
                    }elseif ($i==2) {
                        $datetime = '2024-08-'.$datetime;
                    }elseif ($i==3) {
                        $datetime = '2024-09-'.$datetime;
                    }

                    //Jam 6 = 21600
                    //Jam 7.25 = 26700
                    //Jam 7.25 = 26700
                    //Jam 7.30 = 27000
                    //Jam 8.00 = 28800
                    //Jam 8.30 = 30600
                    //Jam 9.00 = 32400

                    //Jam 14.00 = 50400
                    //Jam 14.30 = 52200
                    //Jam 15.00 = 54000
                    if (date("N", strtotime($datetime))=="7") {
                        continue;
                    }
                    // $unix_start = strtotime("14:00:00");
                    // $unix_end = strtotime("15:00:00");
                    // dd($unix_start, $unix_end);
                    $Sispi = DB::table('sispi')->where('sispi_sisp', $Sisp[$k]->sisp_id)->where("sispi_tgls", ">=", date('Y-m-d', strtotime($datetime)))->where("sispi_tglm", "<=", date('Y-m-d', strtotime($datetime)))->select(['sispi_id'])->get()->first();
                    if ($Sispi!=null) {
                        // dd($Sispi, $datetime, $Sisp[$k]->sisp_id);
                        AbsenController::prosesIzin($this->data['Pgn'], $Sisp[$k]->sisp_id, $Sispi->sispi_id, $datetime);
                        continue;
                    }

                    if ($Sisp[$k]->sisp_tipes=="SN") {
                        $randAbSNM = 'LTBH';
                        $intM = mt_rand(1726270200, 1726272000);
                        $intP = mt_rand(1726293600, 1726297200);
                        $datetimeM = $datetime.' '.date('H:i:s', $intM);
                        $datetimeP = $datetime.' '.date('H:i:s', $intP);
                        $return = [];
                        if($randAbSNM[rand(0,3)]=="L"){
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                        }elseif($randAbSNM[rand(0,3)]=="T"){
                            $return = AbsenController::prosesTh($this->data['Pgn'], $Sisp[$k]->sisp_id, $datetime, 'TH');
                        }elseif($randAbSNM[rand(0,3)]=="B"){
                            $rand = rand(1,2);
                            if ($rand==1) {
                                $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            }elseif($rand==2){
                                $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                            }
                        }elseif($randAbSNM[rand(0,3)]=="H"){
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                        }
                        if (!empty($return)) {
                            array_push($success, $return[1]);
                        }
                    }elseif($Sisp[$k]->sisp_tipes=="N"){
                        $randAbSNM = 'LBHT';
                        $intM = mt_rand(1726269900, 1726272000);
                        $intP = mt_rand(1726293600, 1726295400);
                        $datetimeM = $datetime.' '.date('H:i:s', $intM);
                        $datetimeP = $datetime.' '.date('H:i:s', $intP);
                        // dd($datetimeM, $datetimeP);
                        $return = [];
                        if($randAbSNM[rand(0,3)]=="L"){
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                        }elseif($randAbSNM[rand(0,3)]=="T"){
                            $return = AbsenController::prosesTh($this->data['Pgn'], $Sisp[$k]->sisp_id, $datetime, 'TH');
                        }elseif($randAbSNM[rand(0,3)]=="B"){
                            $rand = rand(1,2);
                            if ($rand==1) {
                                $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            }elseif($rand==2){
                                $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                            }
                        }elseif($randAbSNM[rand(0,3)]=="H"){
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                        }
                        if (!empty($return)) {
                            array_push($success, $return[1]);
                        }
                    }elseif($Sisp[$k]->sisp_tipes=="B"){
                        $randAbSNM = 'LHT';
                        $intM = mt_rand(1726264800, 1726270200);
                        $intP = mt_rand(1726293600, 1726297200);
                        $datetimeM = $datetime.' '.date('H:i:s', $intM);
                        $datetimeP = $datetime.' '.date('H:i:s', $intP);
                        // dd($datetimeM, $datetimeP);
                        $return = [];
                        if($randAbSNM[rand(0,2)]=="L"){
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                        }elseif($randAbSNM[rand(0,2)]=="T"){
                            $return = AbsenController::prosesTh($this->data['Pgn'], $Sisp[$k]->sisp_id, $datetime, 'TH');
                        }elseif($randAbSNM[rand(0,2)]=="B"){
                            $rand = rand(1,2);
                            if ($rand==1) {
                                $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            }elseif($rand==2){
                                $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                            }
                        }elseif($randAbSNM[rand(0,2)]=="H"){
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                        }
                        if (!empty($return)) {
                            array_push($success, $return[1]);
                        }
                    }elseif($Sisp[$k]->sisp_tipes=="BS"){
                        $randAbSNM = 'LHT';
                        $intM = mt_rand(1726264800, 1726270200);
                        $intP = mt_rand(1726293600, 1726297200);
                        $datetimeM = $datetime.' '.date('H:i:s', $intM);
                        $datetimeP = $datetime.' '.date('H:i:s', $intP);
                        // dd($datetimeM, $datetimeP);
                        $return = [];
                        if($randAbSNM[rand(0,2)]=="L"){
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                        }elseif($randAbSNM[rand(0,2)]=="T"){
                            $return = AbsenController::prosesTh($this->data['Pgn'], $Sisp[$k]->sisp_id, $datetime, 'TH');
                        }elseif($randAbSNM[rand(0,2)]=="B"){
                            $rand = rand(1,2);
                            if ($rand==1) {
                                $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            }elseif($rand==2){
                                $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                            }
                        }elseif($randAbSNM[rand(0,2)]=="H"){
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                        }
                        if (!empty($return)) {
                            array_push($success, $return[1]);
                        }
                    }elseif($Sisp[$k]->sisp_tipes=="SB"){
                        $randAbSNM = 'LH';
                        $intM = mt_rand(1726264800, 1726270200);
                        $intP = mt_rand(1726293600, 1726297200);
                        $datetimeM = $datetime.' '.date('H:i:s', $intM);
                        $datetimeP = $datetime.' '.date('H:i:s', $intP);
                        // dd($datetimeM, $datetimeP);
                        $return = [];
                        if($randAbSNM[rand(0,1)]=="L"){
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                        }elseif($randAbSNM[rand(0,1)]=="T"){
                            // $return = AbsenController::prosesIzin($this->data['Pgn'], $Sisp[$k]->sisp_id, $datetime, 'I');
                        }elseif($randAbSNM[rand(0,1)]=="B"){
                            $rand = rand(1,2);
                            if ($rand==1) {
                                $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            }elseif($rand==2){
                                $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                            }
                        }elseif($randAbSNM[rand(0,1)]=="H"){
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'M', $Sisp[$k]->sisp_id, $datetimeM);
                            $return = AbsenController::prosesAbsen($this->data['Pgn'], 'P', $Sisp[$k]->sisp_id, $datetimeP);
                        }
                        if (!empty($return)) {
                            array_push($success, $return[1]);
                        }
                    }
                }
            }
        }
        dd($success);
    }

    static function prosesIzin($Pgn, $absen_sisp, $abseni_sispi, $datetime, $absen_sts = 'I')
    {
        $newTime = strtotime($datetime);

        $Sisp = DB::table('sisp')->where('sisp_id', $absen_sisp)->get(['sisp_id', 'sisp_bag', 'sisp_setkatpes'])->first();
        $JamKerja = SetkatpesjController::getDataByHr($Sisp->sisp_setkatpes, date("N", $newTime));
        if ($JamKerja==null) {
            $JamKerja = SetkatpesjController::getDataByHr($Sisp->sisp_setkatpes, "O");
        }
        // dd($Sisp, $JamKerja, $datetime, $newTime);
        $AbsenModel = new AbsenModel();
        $AbsenModel->absen_sisp = $Sisp->sisp_id;
        $AbsenModel->absen_setkatpesj = $JamKerja->setkatpesj_id;
        $AbsenModel->absen_bag = $Sisp->sisp_bag;
        $AbsenModel->absen_tgl = date("Y-m-d", $newTime);
        $AbsenModel->absen_masuk = '00:00:00';
        $AbsenModel->absen_keluar = '00:00:00';
        $AbsenModel->absen_sts = $absen_sts;
        $AbsenModel->absen_ucreate = $Pgn->users_id;
        $AbsenModel->absen_uupdate = $Pgn->users_id;
        
        $save = $AbsenModel->save();
        if ($save) {
            $Absen = AbsenModel::where('absen_ord', $AbsenModel->absen_id)->select(['absen_id'])->get()->first();
            AbseniController::prosesIzin($Absen->absen_id, $abseni_sispi);

            $data['response'] = ['status' => 200, 'response' => 'success','type' => "success", 'message' => 'Absen Masuk Telah Disimpan.'];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Absens Masuk Tidak Dapat Disimpan, Silahkan Hubungi Teknisi Aplikasi'];
        }

        return [$data, $data['response']['status']];
    }

    static function prosesTh($Pgn, $absen_sisp, $datetime, $absen_sts = 'TH')
    {
        $newTime = strtotime($datetime);

        $Sisp = DB::table('sisp')->where('sisp_id', $absen_sisp)->get(['sisp_id', 'sisp_bag', 'sisp_setkatpes'])->first();
        $JamKerja = SetkatpesjController::getDataByHr($Sisp->sisp_setkatpes, date("N", $newTime));
        if ($JamKerja==null) {
            $JamKerja = SetkatpesjController::getDataByHr($Sisp->sisp_setkatpes, "O");
        }
        // dd($Sisp, $JamKerja, $datetime, $newTime);
        $AbsenModel = new AbsenModel();
        $AbsenModel->absen_sisp = $Sisp->sisp_id;
        $AbsenModel->absen_setkatpesj = $JamKerja->setkatpesj_id;
        $AbsenModel->absen_bag = $Sisp->sisp_bag;
        $AbsenModel->absen_tgl = date("Y-m-d", $newTime);
        $AbsenModel->absen_masuk = '00:00:00';
        $AbsenModel->absen_keluar = '00:00:00';
        $AbsenModel->absen_sts = $absen_sts;
        $AbsenModel->absen_ucreate = $Pgn->users_id;
        $AbsenModel->absen_uupdate = $Pgn->users_id;
        
        $save = $AbsenModel->save();
        if ($save) {
            $data['response'] = ['status' => 200, 'response' => 'success','type' => "success", 'message' => 'Absen Masuk Telah Disimpan.'];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Absens Masuk Tidak Dapat Disimpan, Silahkan Hubungi Teknisi Aplikasi'];
        }

        return [$data, $data['response']['status']];
    }

    static function prosesMasuk($Pgn, $absen_sisp = '', $datetime = '', $long = '', $lat = '', $tipe = '', $pic = '')
    {
        date_default_timezone_set('Asia/Makassar');

        $Absen = [];

        $newTime = strtotime($datetime);

        $Sisp = DB::table('sisp')->where('sisp_id', $absen_sisp)->get(['sisp_id', 'sisp_bag', 'sisp_setkatpes'])->first();
        $JamKerja = SetkatpesjController::getDataByHr($Sisp->sisp_setkatpes, date("N", $newTime));
        if ($JamKerja==null) {
            $JamKerja = SetkatpesjController::getDataByHr($Sisp->sisp_setkatpes, "O");
        }

        if ($Sisp==null) {
            $data['response'] = ['status' => 404, 'response' => 'error','type' => "danger", 'message' => 'Data Peserta Tidak Ditemukan'];
        }else{
            $data = [
                'absen_sisp' => $Sisp->sisp_id,
                'absen_bag' => $Sisp->sisp_bag,
                'absen_setkatpesj' => $JamKerja->setkatpesj_id,
                'absen_tipe' => $tipe,
                'absen_tgl' => date("Y-m-d", $newTime),
                'absen_masuk' => date("H:i:s", $newTime),
                'absen_masuklong' => $long,
                'absen_masuklat' => $lat,
                'absen_masuklok' => SetlokController::setRadius($lat, $long, 'K'),
                'absen_masukpic' => $pic,
                'absen_masukk' => '1',
                'absen_keluar' => '00:00:00',
                'absen_keluark' => '0',
                'absen_lmbt' => 0,
                'absen_lbh' => 0,
                'absen_cd' => 0,
                'absen_cp' => 0,
                'absen_sts' => 'H',
                'absen_ucreate' => $Pgn->users_id,
                'absen_uupdate' => $Pgn->users_id,
            ];
            $new_masuk = new DateTime(date('H:i:s', strtotime($data['absen_masuk'])));
            $next = false;
            $OldAbsenMasuk = AbsenController::getOldAbsen($absen_sisp, $data['absen_tgl']);
            if ($OldAbsenMasuk!=null) {
                $old_masuk = new DateTime(date('H:i:s', strtotime($OldAbsenMasuk->absen_masuk)));
                if($OldAbsenMasuk->absen_masuk=='00:00:00'){
                    $next = true;
                }elseif ($new_masuk>=$old_masuk) {
                    $next = false;
                }else{
                    $next = true;
                }
            }else{
                $next = true;
            }
            if ($next) {
                $setkatpesj_masuk = date('H:i:s', strtotime("-180 minutes",strtotime($JamKerja->setkatpesj_masuk)));
                $setkatpesj_btsj = date('H:i:s', strtotime($JamKerja->setkatpesj_btsj));
                if ($JamKerja->setkatpesj_bts=='0') {
                    $setkatpesj_btsj = date('H:i:s', strtotime($JamKerja->setkatpesj_keluar));
                }
                $setkatpesj_tolj = date('H:i:s', strtotime($JamKerja->setkatpesj_tolj));
                if ($JamKerja->setkatpesj_tol=='0') {
                    $setkatpesj_tolj = date('H:i:s', strtotime($JamKerja->setkatpesj_masuk));
                }
                $masuk = new DateTime(date('H:i:s', strtotime($data['absen_masuk'])));
                // ddd($masuk, $setkatpesj_masuk, $setkatpesj_btsj, date('Y-m-d H:i:s', $newTime), $masuk->diff(new DateTime($setkatpesj_masuk)));
                if ($masuk >= new DateTime($setkatpesj_masuk) && $masuk <= new DateTime($setkatpesj_btsj)){
                    $new_tol = new DateTime($setkatpesj_tolj);
                    if ($masuk > $new_tol) {
                        $data['absen_cd'] = 0;
                        $diff = $new_tol->diff($masuk);
                        $data['absen_lmbt'] = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                    }else{
                        $data['absen_lmbt'] = 0;
                        if ($masuk < $new_tol) {
                            $diff = $masuk->diff($new_tol);
                            $data['absen_cd'] = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                        }
                    }
                }else{
                    $data['absen_sts'] = 'TH';
                    $data['absen_masukk'] = '0';
                    $data['absen_lmbt'] = 0;
                    $data['absen_cd'] = 0;
                    // dd("disini");
                }
                $PesanTambahan = '';
                $MenitTambahan = '';
                if((int)$data['absen_lmbt']>0){
                    $MenitTambahan = date('H:i', strtotime("+".(string)$data['absen_lmbt']." minutes",strtotime($JamKerja->setkatpesj_keluar)));
                    $PesanTambahan = 'Tapi Anda Terlambat Dan Harus Absen Pulang Pada Pukul '.$MenitTambahan;
                }

                $AbsenModel = new AbsenModel();
                $AbsenModel->absen_sisp = $data['absen_sisp'];
                $AbsenModel->absen_setkatpesj = $data['absen_setkatpesj'];
                $AbsenModel->absen_bag = $data['absen_bag'];
                $AbsenModel->absen_tipe = $data['absen_tipe'];
                $AbsenModel->absen_tgl = $data['absen_tgl'];
                $AbsenModel->absen_masuk = $data['absen_masuk'];
                $AbsenModel->absen_masuklong = $data['absen_masuklong'];
                $AbsenModel->absen_masuklat = $data['absen_masuklat'];
                $AbsenModel->absen_masuklok = $data['absen_masuklok'];
                $AbsenModel->absen_masukpic = $data['absen_masukpic'];
                $AbsenModel->absen_masukk = $data['absen_masukk'];
                $AbsenModel->absen_keluar = $data['absen_keluar'];
                $AbsenModel->absen_lmbt = $data['absen_lmbt'];
                $AbsenModel->absen_lbh = $data['absen_lbh'];
                $AbsenModel->absen_cd = $data['absen_cd'];
                $AbsenModel->absen_cp = $data['absen_cp'];
                $AbsenModel->absen_sts = $data['absen_sts'];
                $AbsenModel->absen_ucreate = $data['absen_ucreate'];
                $AbsenModel->absen_uupdate = $data['absen_uupdate'];
                
                $save = $AbsenModel->save();
                if ($save) {
                    $data['response'] = ['status' => 200, 'response' => 'success','type' => "success", 'message' => 'Absen Masuk Telah Disimpan. '.$PesanTambahan];
                }else{
                    $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Absens Masuk Tidak Dapat Disimpan, Silahkan Hubungi Teknisi Aplikasi'];
                }

            }else{
                $data['response'] = ['status' => 406, 'response' => 'error','type' => "danger", 'message' => 'Data Absen Masuk Ganda'];
            }
        }

        return $data;
    }

    static function prosesKeluar($absen_id, $Pgn, $absen_sisp = '', $datetime = '', $long = '', $lat = '', $tipe = '', $pic = '')
    {
        date_default_timezone_set('Asia/Makassar');

        $Absen = [];

        $newTime = strtotime($datetime);

        $Sisp = DB::table('sisp')->where('sisp_id', $absen_sisp)->get(['sisp_id', 'sisp_bag', 'sisp_setkatpes'])->first();
        $JamKerja = SetkatpesjController::getDataByHr($Sisp->sisp_setkatpes, date("N", $newTime));
        if ($JamKerja==null) {
            $JamKerja = SetkatpesjController::getDataByHr($Sisp->sisp_setkatpes, "O");
        }

        if ($Sisp==null) {
            $data['response'] = ['status' => 404, 'response' => 'error','type' => "danger", 'message' => 'Data Peserta Tidak Ditemukan'];
        }else{
            $data = [
                'absen_tgl' => date("Y-m-d", $newTime),
                'absen_keluar' => date("H:i:s", $newTime),
                'absen_keluarlong' => $long,
                'absen_keluarlat' => $lat,
                'absen_keluarlok' => SetlokController::setRadius($lat, $long, 'K'),
                'absen_keluarpic' => $pic,

                'absen_keluark' => '1',
                'absen_lbh' => null,
                'absen_cp' => null,
                'absen_uupdate' => $Pgn->users_id,
            ];
            $next = false;
            $new_keluar = new DateTime(date('H:i:s', strtotime($data['absen_keluar'])));
            $OldAbsenKeluar = AbsenController::getOldAbsen($absen_sisp, $data['absen_tgl']);

            if ($OldAbsenKeluar!=null) {
                $old_keluar = new DateTime(date('H:i:s', strtotime($OldAbsenKeluar->absen_keluar)));
                if ($old_keluar>=$new_keluar) {
                    $next = false;
                }else{
                    $next = true;
                }
            }

            if ($next) {
                $setkatpesj_keluar = date('H:i:s', strtotime($JamKerja->setkatpesj_keluar));
                    $setkatpesj_keluar1 = date('H:i:s', strtotime("+360 minutes",strtotime($JamKerja->setkatpesj_keluar)));

                    $setkatpesj_masuk = date('H:i:s', strtotime("-180 minutes",strtotime($JamKerja->setkatpesj_masuk)));

                    $keluar = new DateTime(date('H:i:s', strtotime($data['absen_keluar'])));
                    if ($keluar >= new DateTime($setkatpesj_masuk) && $keluar <= new DateTime($setkatpesj_keluar1)) {
                        $setkatpesj_keluar1 = new DateTime($setkatpesj_keluar1);
                        $setkatpesj_keluar = new DateTime($setkatpesj_keluar);
                        if ($keluar < $setkatpesj_keluar) {
                            $data['absen_lbh'] = 0;
                            $diff = $keluar->diff($setkatpesj_keluar);
                            $data['absen_cp'] = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                        }else{
                            $data['absen_cp'] = 0;
                            if ($keluar > $setkatpesj_keluar) {
                                $diff = $setkatpesj_keluar->diff($keluar);
                                $data['absen_lbh'] = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                            }
                        }
                    }else{
                        $data['absen_keluar'] = '00:00:00';
                        // $data['absen_keluark'] = '0';
                        $data['absen_lbh'] = 0;
                        $data['absen_cp'] = 0;
                        $data['absen_uupdate'] = $Pgn->users_id;
                    }
                    try {
                        $update = DB::table('absen')->where('absen_id', $absen_id)->update([
                            'absen_keluar' => $data['absen_keluar'],
                            'absen_keluarlong' => $long,
                            'absen_keluarlat' => $lat,
                            'absen_keluarlok' => SetlokController::setRadius($lat, $long, 'K'),
                            'absen_keluarpic' => $pic,
                            'absen_keluark' => $data['absen_keluark'],
                            'absen_lbh' => $data['absen_lbh'],
                            'absen_cp' => $data['absen_cp'],
                            'absen_uupdate' => $data['absen_uupdate'],
                        ]);

                        $data['response'] = ['status' => 200, 'response' => 'success','type' => "success", 'message' => 'Absen Keluar Telah Disimpan.'];
                    } catch (\Throwable $th) {
                        $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Absens Keluar Tidak Dapat Disimpan, Silahkan Hubungi Teknisi Aplikasi'];
                    }
            }else{
                $data['response'] = ['status' => 406, 'response' => 'error','type' => "danger", 'message' => 'Data Absen Keluar Ganda'];
            }
        }
        return $data;
    }

    static function prosesAbsen($Pgn, $tipe = 'M', $absen_sisp = '', $datetime = '')
    {
        // header('Content-Type: application/json; charset=utf-8');
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Headers: *");
        date_default_timezone_set('Asia/Makassar');

        $Absen = [];

        $newTime = strtotime($datetime);

        $Sisp = DB::table('sisp')->where('sisp_id', $absen_sisp)->get(['sisp_id', 'sisp_bag', 'sisp_setkatpes'])->first();
        // $JamKerja = SetkatpesjController::getDataByHr($Sisp->sisp_setkatpes, date("N", $newTime));
        $JamKerja = SetkatpesjController::getDataByHrD(date("N", $newTime));
        if ($JamKerja==null) {
            // $JamKerja = SetkatpesjController::getDataByHr($Sisp->sisp_setkatpes, "O");
            $JamKerja = SetkatpesjController::getDataByHrD("O");
        }

        // dd($JamKerja);

        if ($Sisp==null) {
            $data['response'] = ['status' => 404, 'response' => 'error','type' => "danger", 'message' => 'Data Peserta Tidak Ditemukan'];
        }else{
            if ($tipe=='M') {
                $data = [
                    'absen_sisp' => $Sisp->sisp_id,
                    'absen_bag' => $Sisp->sisp_bag,
                    'absen_setkatpesj' => $JamKerja->setkatpesj_id,
                    'absen_tgl' => date("Y-m-d", $newTime),
                    'absen_masuk' => date("H:i:s", $newTime),
                    'absen_masukk' => '1',
                    'absen_keluar' => '00:00:00',
                    'absen_keluark' => '0',
                    'absen_lmbt' => 0,
                    'absen_lbh' => 0,
                    'absen_cd' => 0,
                    'absen_cp' => 0,
                    'absen_sts' => 'H',
                    'absen_ucreate' => $Pgn->users_id,
                    'absen_uupdate' => $Pgn->users_id,
                ];
                $new_masuk = new DateTime(date('H:i:s', strtotime($data['absen_masuk'])));
                $next = false;
                $OldAbsenMasuk = AbsenController::getOldAbsen($absen_sisp, $data['absen_tgl']);
                if ($OldAbsenMasuk!=null) {
                    $old_masuk = new DateTime(date('H:i:s', strtotime($OldAbsenMasuk->absen_masuk)));
                    if($OldAbsenMasuk->absen_masuk=='00:00:00'){
                        $next = true;
                    }elseif ($new_masuk>=$old_masuk) {
                        $next = false;
                    }else{
                        $next = true;
                    }
                }else{
                    $next = true;
                }
                if ($next) {
                    $setkatpesj_masuk = date('H:i:s', strtotime("-180 minutes",strtotime($JamKerja->setkatpesj_masuk)));
                    $setkatpesj_btsj = date('H:i:s', strtotime($JamKerja->setkatpesj_btsj));
                    if ($JamKerja->setkatpesj_bts=='0') {
                        $setkatpesj_btsj = date('H:i:s', strtotime($JamKerja->setkatpesj_keluar));
                    }
                    $setkatpesj_tolj = date('H:i:s', strtotime($JamKerja->setkatpesj_tolj));
                    if ($JamKerja->setkatpesj_tol=='0') {
                        $setkatpesj_tolj = date('H:i:s', strtotime($JamKerja->setkatpesj_masuk));
                    }
                    $masuk = new DateTime(date('H:i:s', strtotime($data['absen_masuk'])));
                    // ddd($masuk, $setkatpesj_masuk, $setkatpesj_btsj, date('Y-m-d H:i:s', $newTime), $masuk->diff(new DateTime($setkatpesj_masuk)));
                    if ($masuk >= new DateTime($setkatpesj_masuk) && $masuk <= new DateTime($setkatpesj_btsj)){
                        $new_tol = new DateTime($setkatpesj_tolj);
                        if ($masuk > $new_tol) {
                            $data['absen_cd'] = 0;
                            $diff = $new_tol->diff($masuk);
                            $data['absen_lmbt'] = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                        }else{
                            $data['absen_lmbt'] = 0;
                            if ($masuk < $new_tol) {
                                $diff = $masuk->diff($new_tol);
                                $data['absen_cd'] = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                            }
                        }
                    }else{
                        $data['absen_sts'] = 'TH';
                        $data['absen_masukk'] = '0';
                        $data['absen_lmbt'] = 0;
                        $data['absen_cd'] = 0;
                        // dd("disini");
                    }

                    if ($OldAbsenMasuk!=null) {
                        try {
                            $update = DB::table('absen')->where('absen_id', $OldAbsenMasuk->absen_id)->update([
                                'absen_masuk' => $data['absen_masuk'],
                                'absen_masukk' => $data['absen_masukk'],
                                'absen_lmbt' => $data['absen_lmbt'],
                                'absen_cd' => $data['absen_cd'],
                                'absen_uupdate' => $data['absen_uupdate'],
                            ]);

                            $data['response'] = ['status' => 200, 'response' => 'success','type' => "success", 'message' => 'Absen Masuk Telah Disimpan.'];
                        } catch (\Throwable $th) {
                            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Absens Masuk Tidak Dapat Disimpan, Silahkan Hubungi Teknisi Aplikasi'];
                        }
                    }else{
                        $AbsenModel = new AbsenModel();
                        $AbsenModel->absen_sisp = $data['absen_sisp'];
                        $AbsenModel->absen_setkatpesj = $data['absen_setkatpesj'];
                        $AbsenModel->absen_bag = $data['absen_bag'];
                        $AbsenModel->absen_tgl = $data['absen_tgl'];
                        $AbsenModel->absen_masuk = $data['absen_masuk'];
                        $AbsenModel->absen_masukk = $data['absen_masukk'];
                        $AbsenModel->absen_keluar = $data['absen_keluar'];
                        $AbsenModel->absen_lmbt = $data['absen_lmbt'];
                        $AbsenModel->absen_lbh = $data['absen_lbh'];
                        $AbsenModel->absen_cd = $data['absen_cd'];
                        $AbsenModel->absen_cp = $data['absen_cp'];
                        $AbsenModel->absen_sts = $data['absen_sts'];
                        $AbsenModel->absen_ucreate = $data['absen_ucreate'];
                        $AbsenModel->absen_uupdate = $data['absen_uupdate'];
                        
                        $save = $AbsenModel->save();
                        if ($save) {
                            $data['response'] = ['status' => 200, 'response' => 'success','type' => "success", 'message' => 'Absen Masuk Telah Disimpan.'];
                        }else{
                            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Absens Masuk Tidak Dapat Disimpan, Silahkan Hubungi Teknisi Aplikasi'];
                        }
                    }

                }else{
                    $data['response'] = ['status' => 406, 'response' => 'error','type' => "danger", 'message' => 'Data Absen Masuk Ganda'];
                }

            }else{
                $data = [
                    'absen_tgl' => date("Y-m-d", $newTime),
                    'absen_keluar' => date("H:i:s", $newTime),
                    'absen_keluark' => '1',
                    'absen_lbh' => null,
                    'absen_cp' => null,
                    'absen_uupdate' => $Pgn->users_id,
                ];
                $next = false;
                $new_keluar = new DateTime(date('H:i:s', strtotime($data['absen_keluar'])));
                $OldAbsenKeluar = AbsenController::getOldAbsen($absen_sisp, $data['absen_tgl']);
                if ($OldAbsenKeluar!=null) {
                    $old_keluar = new DateTime(date('H:i:s', strtotime($OldAbsenKeluar->absen_keluar)));
                    if ($old_keluar>=$new_keluar) {
                        $next = false;
                    }else{
                        $next = true;
                    }
                }else{
                    $data = [
                        'absen_sisp' => $Sisp->sisp_id,
                        'absen_bag' => $Sisp->sisp_bag,
                        'absen_setkatpesj' => $JamKerja->setkatpesj_id,
                        'absen_tgl' => date("Y-m-d", $newTime),
                        'absen_masuk' => '00:00:00',
                        'absen_masukk' => '0',
                        'absen_keluar' => date("H:i:s", $newTime),
                        'absen_keluark' => '1',
                        'absen_lmbt' => 0,
                        'absen_lbh' => null,
                        'absen_cd' => 0,
                        'absen_cp' => null,
                        'absen_sts' => 'H',
                        'absen_ucreate' => $Pgn->users_id,
                        'absen_uupdate' => $Pgn->users_id,
                    ];
                    $next = true;
                }

                if ($next) {
                    $setkatpesj_keluar = date('H:i:s', strtotime($JamKerja->setkatpesj_keluar));
                    $setkatpesj_keluar1 = date('H:i:s', strtotime("+360 minutes",strtotime($JamKerja->setkatpesj_keluar)));

                    $setkatpesj_masuk = date('H:i:s', strtotime("-180 minutes",strtotime($JamKerja->setkatpesj_masuk)));

                    $keluar = new DateTime(date('H:i:s', strtotime($data['absen_keluar'])));
                    if ($keluar >= new DateTime($setkatpesj_masuk) && $keluar <= new DateTime($setkatpesj_keluar1)) {
                        $setkatpesj_keluar1 = new DateTime($setkatpesj_keluar1);
                        $setkatpesj_keluar = new DateTime($setkatpesj_keluar);
                        if ($keluar < $setkatpesj_keluar) {
                            $data['absen_lbh'] = 0;
                            $diff = $keluar->diff($setkatpesj_keluar);
                            $data['absen_cp'] = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                        }else{
                            $data['absen_cp'] = 0;
                            if ($keluar > $setkatpesj_keluar) {
                                $diff = $setkatpesj_keluar->diff($keluar);
                                $data['absen_lbh'] = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                            }
                        }
                    }else{
                        $data['absen_keluar'] = '00:00:00';
                        $data['absen_keluark'] = '0';
                        $data['absen_lbh'] = 0;
                        $data['absen_cp'] = 0;
                        $data['absen_uupdate'] = $Pgn->users_id;
                    }
                    if ($OldAbsenKeluar!=null) {
 
                        try {
                            $update = DB::table('absen')->where('absen_id', $OldAbsenKeluar->absen_id)->update([
                                'absen_keluar' => $data['absen_keluar'],
                                'absen_keluark' => $data['absen_keluark'],
                                'absen_lbh' => $data['absen_lbh'],
                                'absen_cp' => $data['absen_cp'],
                                'absen_uupdate' => $data['absen_uupdate'],
                            ]);

                            $data['response'] = ['status' => 200, 'response' => 'success','type' => "success", 'message' => 'Absen Keluar Telah Disimpan.'];
                        } catch (\Throwable $th) {
                            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Absens Keluar Tidak Dapat Disimpan, Silahkan Hubungi Teknisi Aplikasi'];
                        }
                    }else{
                        $AbsenModel = new AbsenModel();
                        $AbsenModel->absen_sisp = $Sisp->sisp_id;
                        $AbsenModel->absen_setkatpesj = $JamKerja->setkatpesj_id;
                        $AbsenModel->absen_bag = $Sisp->sisp_bag;
                        $AbsenModel->absen_tgl = $data['absen_tgl'];
                        $AbsenModel->absen_masuk = $data['absen_masuk'];
                        $AbsenModel->absen_masukk = $data['absen_masukk'];
                        $AbsenModel->absen_keluar = $data['absen_keluar'];
                        $AbsenModel->absen_keluark = $data['absen_keluark'];
                        $AbsenModel->absen_lmbt = $data['absen_lmbt'];
                        $AbsenModel->absen_lbh = $data['absen_lbh'];
                        $AbsenModel->absen_cd = $data['absen_cd'];
                        $AbsenModel->absen_cp = $data['absen_cp'];
                        $AbsenModel->absen_sts = $data['absen_sts'];
                        $AbsenModel->absen_ucreate = $data['absen_ucreate'];
                        $AbsenModel->absen_uupdate = $data['absen_uupdate'];
                        
                        $save = $AbsenModel->save();
                        if ($save) {
                            $data['response'] = ['status' => 200, 'response' => 'success','type' => "success", 'message' => 'Absen Keluar Telah Disimpan.'];
                        }else{
                            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Absens Keluar Tidak Dapat Disimpan, Silahkan Hubungi Teknisi Aplikasi'];
                        }
                    }
                }else{
                    $data['response'] = ['status' => 406, 'response' => 'error','type' => "danger", 'message' => 'Data Absen Keluar Ganda'];
                }
            }
        }
        return $data;
        // return response()->json($data, $data['response']['status']);
    }
}
