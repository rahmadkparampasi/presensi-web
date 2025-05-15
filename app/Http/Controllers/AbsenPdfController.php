<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCPDF;

class AbsenPdfController extends Controller
{
    protected $data;

    public function pdfSisp($id = '', $month = '', $year = '')
    {
        ob_start();

        $this->data['Pgn'] = $this->getUser();

        $pdf = new MYPDF('L', 'mm', 'Legal', true, 'UTF-8', false);

        $pdf->setCreator(PDF_CREATOR);
        //Bug
        $pdf->setAuthor('BWS SULAWESI III');
        $pdf->setTitle('PRESENSI');
        $pdf->setCreator('BWS SULAWESI III');
        $pdf->setSubject('Absensi Digital');
        $pdf->setKeywords('PRESENSI');

        $pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->setMargins(12, 20, 12);
        $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->setAutoPageBreak(true, 20);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $pdf->AddPage();
        $pdf->setFont('helvetica', '', 9);

        $Sisp = DB::table('sisp')->leftJoin('bag', 'sisp.sisp_bag', '=', 'bag.bag_id')->where('sisp_id', $id)->select(['sisp_nm', 'bag_nm', 'sisp_bag', 'bag_prnt'])->get()->first();
        if ($Sisp==null) {
            echo "<script>window.close();</script>";
        }

        $Bag = DB::table('bag')->where('bag_id', $Sisp->bag_prnt)->select(['bag_nm', 'bag_id'])->get()->first();
        if ($Bag==null) {
            echo "<script>window.close();</script>";
        }

        $data = [
            'filters_satker' => $Bag->bag_id,
            'filters_ppk' => $Sisp->sisp_bag,
            'filtert_tgl' => 'bulan',
            'filtert_blnthn' => (int)$year,
            'filtert_blnbln' => (int)$month,
            'filtert_smstr' => '',
            'filtert_rentangawal' => '',
            'filtert_rentangakhir' => '',
            'filtert_pilih' => '',
        ];

        $Satker = null;

        if ($data['filters_satker']!=null||$data['filters_ppk']!=null) {
            $Satker = DB::table('bag')->where('bag_id', $data['filters_satker'])->select(['bag_id', 'bag_nm'])->orderBy('bag_nm')->get()->first();
            if ($data['filters_ppk'] !='') {
                // dd('PPK');
            }elseif($data['filters_satker'] !=''&&$data['filters_ppk'] ==''){
            }
        }

        if ($Satker==null) {
            $dpri = '<br><br><p align="center" ><font size="12" style="text-transform:uppercase"><b>TIDAK ADA DATA</b></font></p>';
            $pdf->writeHTML($dpri, true, false, false, false, '');

            $pdf->Output('Presensi - Data Absensi.pdf', 'I');
            exit;
        }
        
        $Label = AbsenController::labelFilter($data);

        if ($id == '' || $month == '' || $year == '') {
            echo "<script>window.close();</script>";
        }else{
            $Sisp = DB::table('sisp')->leftJoin('bag', 'sisp.sisp_bag', '=', 'bag.bag_id')->where('sisp_id', $id)->select(['sisp_nm', 'bag_nm', 'sisp_bag', 'bag_prnt'])->get()->first();
            if ($Sisp==null) {
                echo "<script>window.close();</script>";
            }else{
                $Bag = DB::table('bag')->where('bag_id', $Sisp->bag_prnt)->select(['bag_nm', 'bag_id'])->get()->first();
                if ($Bag==null) {
                    echo "<script>window.close();</script>";
                }else{

                    $pdf->writeHTML(AbsenPdfController::generateHeader(''), true, false, false, false, '');
            
                    $Label['labelKategori'] = $Satker->bag_nm;

                    $Ppk = DB::table('bag');
                    
                    if ($data['filters_ppk'] !='') {
                        $Ppk = $Ppk->where('bag_id', $data['filters_ppk']);
                    }else{
                        $Ppk = $Ppk->where('bag_prnt', $Satker->bag_id);
                    }

                    $Ppk = $Ppk->select(['bag_id', 'bag_nm'])->orderBy('bag_nm')->get()->first();

                    $Label['labelBagian'] = $Ppk->bag_nm;
                    $DataAbsen = AbsenController::getAbsenByMonth($id, $month, $year);
                    $DataAbsen = AbsenController::setData($DataAbsen);
                    // $DataAbsen = AbsenController::loadDataFilterSisp($data, $id);
                    $TabelAbsen = AbsenPdfController::generateTable($DataAbsen, '', $Label);
                    $pdf->writeHTML($TabelAbsen[0], true, false, false, false, '');
                
                    $pdf->writeHTML($TabelAbsen[1], true, false, false, false, '');

                }
            }
        }

        

        $Label = AbsenController::labelFilter($data);
        $namaEkspor = $Label['labelKategori'];
        if ($Label['labelBagian']!='') {
            $namaEkspor .= " ".$Label['labelBagian'];
        }
        $namaEkspor .= " ".$Label['labelTanggal'];

        $pdf->Output($namaEkspor .'.pdf', 'I');
        exit;
    }

    public function pdfFilter(Request $request)
    {
        ob_start();

        $this->data['Pgn'] = $this->getUser();

        $pdf = new MYPDF('L', 'mm', 'Legal', true, 'UTF-8', false);

        $pdf->setCreator(PDF_CREATOR);
        //Bug
        $pdf->setAuthor('BWS SULAWESI III');
        $pdf->setTitle('PRESENSI');
        $pdf->setCreator('BWS SULAWESI III');
        $pdf->setSubject('Absensi Digital');
        $pdf->setKeywords('PRESENSI');

        $pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->setMargins(12, 20, 12);
        $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->setAutoPageBreak(true, 20);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $pdf->AddPage();
        $pdf->setFont('helvetica', '', 9);

        $urlLogo = '/assets/img/logo-sma-1-parigi.png';
        $urlLine = '/assets/img/line.png';
        $urlCekatan = '/assets/img/Logo-NLT-1080.png';

        $data = [
            'filters_satker' => '',
            'filters_ppk' => '',
            'filtert_tgl' => '',
            'filtert_blnthn' => '',
            'filtert_blnbln' => '',
            'filtert_smstr' => '',
            'filtert_rentangawal' => '',
            'filtert_rentangakhir' => '',
            'filtert_pilih' => '',
        ];

        if ($request) {
            $data['filters_satker'] = $request->get('filters_satker');
            $data['filters_ppk'] = $request->get('filters_ppk');
            $data['filtert_tgl'] = $request->get('filtert_tgl');
            $data['filtert_blnthn'] = $request->get('filtert_blnthn');
            $data['filtert_blnbln'] = $request->get('filtert_blnbln');
            $data['filtert_smstr'] = $request->get('filtert_smstr');
            $data['filtert_rentangawal'] = $request->get('filtert_rentangawal');
            $data['filtert_rentangakhir'] = $request->get('filtert_rentangakhir');
            $data['filtert_pilih'] = $request->get('filtert_pilih');
        }

        $Satker = null;

        if ($data['filters_satker']!=null||$data['filters_ppk']!=null) {
            $Satker = DB::table('bag')->where('bag_id', $data['filters_satker'])->select(['bag_id', 'bag_nm'])->orderBy('bag_nm')->get();
            if ($data['filters_ppk'] !='') {
                // dd('PPK');
            }elseif($data['filters_satker'] !=''&&$data['filters_ppk'] ==''){
            }
        }else{
            $Satker = DB::table('bag')->where('bag_str', '2')->select(['bag_id', 'bag_nm'])->orderBy('bag_nm')->get();
        }

        if ($Satker==null) {
            $dpri = '<br><br><p align="center" ><font size="12" style="text-transform:uppercase"><b>TIDAK ADA DATA</b></font></p>';
            $pdf->writeHTML($dpri, true, false, false, false, '');

            $pdf->Output('Presensi - Data Absensi.pdf', 'I');
            exit;
        }

        // if ($data['filtert_tgl'] == '') {
        //     $dpri = '<br><br><p align="center" ><font size="12" style="text-transform:uppercase"><b>TIDAK ADA DATA</b></font></p>';
        //     $pdf->writeHTML($dpri, true, false, false, false, '');

        //     $pdf->Output('Presensi - Data Absensi.pdf', 'I');
        //     exit;
        // }

        $Label = AbsenController::labelFilter($data);
        for ($i=0; $i < count($Satker); $i++) { 
            $pb = 'pagebreak="true"';
            if ($i==0) {
                $pb = '';
            }

            $pdf->writeHTML(AbsenPdfController::generateHeader($pb), true, false, false, false, '');
            
            $Label['labelKategori'] = $Satker[$i]->bag_nm;

            $Ppk = DB::table('bag');
            
            if ($data['filters_ppk'] !='') {
                $Ppk = $Ppk->where('bag_id', $data['filters_ppk']);
            }else{
                $Ppk = $Ppk->where('bag_prnt', $Satker[$i]->bag_id);
            }

            $Ppk = $Ppk->select(['bag_id', 'bag_nm'])->orderBy('bag_nm')->get();

            if ($Ppk!=null) {
                for ($j=0; $j < count($Ppk); $j++) { 
                    $Label['labelBagian'] = $Ppk[$j]->bag_nm;
                    $DataAbsen = AbsenPdfController::loadDataCtk($data, $Ppk[$j]->bag_id);
                    $TabelAbsen = AbsenPdfController::generateTable($DataAbsen, '', $Label);
                    $pdf->writeHTML($TabelAbsen[0], true, false, false, false, '');
                
                    $pdf->writeHTML($TabelAbsen[1], true, false, false, false, '');
                }
            }
        }
        $Label = AbsenController::labelFilter($data);
        $namaEkspor = $Label['labelKategori'];
        if ($Label['labelBagian']!='') {
            $namaEkspor .= " ".$Label['labelBagian'];
        }
        $namaEkspor .= " ".$Label['labelTanggal'];

        $pdf->Output($namaEkspor .'.pdf', 'I');
        exit;
    }

    static function generateTable($data, $pb = '', $label = [])
    {
        $dpri = '<br '.$pb.'><p><strong><font size="12" >'.strtoupper($label['labelTanggal']).'<font></strong></p>';
        $dpri .= '<br><p><strong><font size="12" >SATKER: '.strtoupper($label['labelKategori']);
        $dpri .= '<br>PPK: '.strtoupper($label['labelBagian']);
        $dpri .= '<font></strong></p>';

        $tblhar = '<table nobr="false" cellspacing="0" cellpadding="6" border="1" width="100%" style="font-size:9pt;">
            <thead>
                <tr>
                    <th align="center" width="20%"><strong>Nama</strong></th>
                    <th align="center" width="9%" class="text-wrap"><strong>Tanggal</strong></th>
                    <th align="center" width="7%" class="text-wrap"><strong>Masuk</strong></th>
                    <th align="center" width="11%" class="text-wrap"><strong>M Lokasi</strong></th>
                    <th align="center" width="7%" class="text-wrap"><strong>Pulang</strong></th>
                    <th align="center" width="11%" class="text-wrap"><strong>P Lokasi</strong></th>
                    <th align="center" width="17%" class="text-wrap"><strong>Jam Kerja</strong></th>
                    <th align="center" width="6%" class="text-wrap"><strong>Status</strong></th>
                    <th align="center" width="12%" class="text-wrap"><strong>Keterangan</strong></th>
                </tr>
            </thead>
            <tbody>';
        if (count($data)==0) {
            $tblhar .= '<tr><td colspan="8" align="center" width="100%">Tidak Ada Data</td></tr>';
        }else{
        
            $no = 1;
            foreach ($data as $tk) {
                $bgColor = "";
                if ($tk->absen_sts=="TH") {
                    $bgColor = 'bgcolor="#FF0000"';
                }elseif($tk->absen_sts=="I"){
                    $bgColor = 'bgcolor="#0000FF"';
                }elseif($tk->absen_keluark=="0"||$tk->absen_masukk=="0"){
                    $bgColor = 'bgcolor="#FFFF00"';
                }
                $tblhar .= '<tr nobr="true">
                <td '.$bgColor.' align="center" width="20%">' . ucwords(strtolower(stripslashes($tk->sisp_nmAltT))) . '</td>
                <td align="left" width="9%">' . $tk->absen_tgl . '</td>
                <td align="left" width="7%">' . $tk->absen_masuk . '</td>';
                if ($tk->absen_masuklok=='1') {
                    $tblhar .='<td align="left" width="11%" class="text-wrap">Di area kantor</td>';
                }else{
                    $tblhar .='<td align="left" width="11%" class="text-wrap">Di luar area kantor</td>';
                }
                $tblhar .='<td align="left" width="7%">' . $tk->absen_keluar.'</td>';
                if ($tk->absen_keluarlok=='1') {
                    $tblhar .='<td align="left" width="11%" class="text-wrap">Di area kantor</td>';
                }else{
                    $tblhar .='<td align="left" width="11%" class="text-wrap">Di luar area kantor</td>';
                }
                if ((int)$tk->absen_lmbt>0) {
                    $tblhar .='<td align="left" width="17%" class="text-wrap">Terlambat: '.(string)intdiv((int)$tk->absen_lmbt, 60).' Jam, '.(string)((int)$tk->absen_lmbt % 60).' Menit</td>';
                }else{
                    if ($tk->absen_masukk=="0"&&$tk->absen_masuk!="00:00:00") {
                        $tblhar .='<td align="left" width="17%" class="text-wrap">Masuk Diluar Jadwal</td>';
                    }else{
                        $tblhar .='<td align="left" width="17%" class="text-wrap">Masuk Tepat Waktu</td>';
                    }

                }

                if ($tk->absen_tipe=='D') {
                    $tblhar .='<td align="left" width="6%" class="text-wrap">Dinas</td>';
                }elseif ($tk->absen_tipe=='O') {
                    $tblhar .='<td align="left" width="6%" class="text-wrap">ON-SITE</td>';
                }else{
                    $tblhar .='<td align="left" width="6%" class="text-wrap">'.$tk->absen_tipe.'</td>';
                }
                $tblhar .='<td align="left" width="12%"></td>
                </tr>';
            }
        }

        $tblhar .= '</tbody><tfoot>
        <tr>
        <td colspan="5" align="center" width="64%"><strong>Jumlah Pegawai SATKER: '.strtoupper($label['labelKategori']).'<br/>PPK: '.strtoupper($label['labelBagian']).'</strong></td>
        <td colspan="3" align="center" width="36%"><strong>'.(string)count($data).'</strong></td>
        </tr>
        </tfoot></table>';

        return [$dpri, $tblhar];

    }

    static function generateHeader($pb = ''):String
    {
        $urlLogo = '/assets/img/logo-sma-1-parigi.png';
        $urlLine = '/assets/img/line.png';
        $urlCekatan = '/assets/img/Logo-NLT-1080.png';

        $tblpri = '<br '.$pb.'><div class="container-fluid"> <table cellspacing="0" cellpadding="2" >
                <tr>';
        $tblpri .= '<td rowspan="2" align="center" width="60"><img src="' . $urlCekatan . '" width="180" alt="Logo / Lambang PU"></td>';
        // $tblpri .= '<td rowspan="2" align="center" width="60"><img src="' . $urlLogo . '" width="180" alt="Logo / Lambang SMA 1 Parigi"></td>';
        $tblpri .= '<td rowspan="2" align="center" width="8.5"><img src="' . $urlLine . '" width="180" alt="GARIS"></td>';
        $tblpri .= '
                <td width="500"><font size="17" style="text-transform:uppercase"><b>BALAI WILAYAH SUNGAI SULAWESI III</b></font></td>
            </tr>
            <tr>
                <td width="500"><font size="12" style="text-transform:uppercase"><b>ABSENSI DIGITAL</b></font></td>
            </tr>
            </table></div>';

        return $tblpri;
    }

    static function loadDataCtk($formData, $Ppk = '')
    {
        ini_set('max_execution_time', 6000);

        DB::statement(DB::raw('set @rownum=0'));

        $Bag = 'absen_bag';
        $BagNm = 'bag_nm';
        
        $Absen = DB::table('absen')->leftJoin('bag', 'absen.absen_bag', '=', 'bag.bag_id')->leftJoin('sisp', 'absen.absen_sisp', '=', 'sisp.sisp_id');

        // if ($formData['filters_ppk'] !='') {
        //     $Absen = $Absen->where('bag_id', $formData['filters_ppk']);
        // }elseif($formData['filters_satker'] !=''&&$formData['filters_ppk'] ==''){
        //     $Absen = $Absen->where('bag_prnt', $formData['filters_satker']);
        // }

        if ($Ppk!='' ) {
            $Absen = $Absen->where('bag_id', $Ppk);
        }

        if($formData['filtert_tgl'] ==''){

        }elseif($formData['filtert_tgl'] =='today'||$formData['filtert_tgl'] =='kemarin'||$formData['filtert_tgl'] =='pilih'){
            if($formData['filtert_tgl'] =='today'){
                $Absen = $Absen->where('absen_tgl', date("Y-m-d"));
            }elseif($formData['filtert_tgl'] =='kemarin'){
                $Absen = $Absen->where('absen_tgl', date("Y-m-d", strtotime("-1 days")));
            }elseif($formData['filtert_tgl'] =='pilih'){
                $Absen = $Absen->where('absen_tgl', $formData['filtert_pilih']);
            }
        }else{
            if($formData['filtert_tgl'] =='minggu'){
                $today = (int)date('w');
                $week = date("Y-m-d", strtotime("-".(String)$today."days"));
                $Absen = $Absen->whereBetween('absen_tgl', [$week,  date("Y-m-d")]);
            }elseif($formData['filtert_tgl'] =='bulan'){
                $Year = date("Y");
                $Month = date("m");
                if ($formData['filtert_blnthn'] !='') {
                    $Year = $formData['filtert_blnthn'];
                }
                if ($formData['filtert_blnbln'] !='') {
                    $Month = $formData['filtert_blnbln'];
                }
                $Absen = $Absen->whereMonth('absen_tgl', '=', $Month)->whereYear('absen_tgl', '=', $Year);
            }elseif($formData['filtert_tgl'] =='rentang'){
                if ($formData['filtert_rentangakhir']!=''&&$formData['filtert_rentangawal']!='') {
                    $Absen = $Absen->whereBetween('absen_tgl', [$formData['filtert_rentangawal'],  $formData['filtert_rentangakhir']]);

                }
            }
        }
        $Absen = $Absen->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'bag_nm', 'absen_tgl', 'absen_masuk', 'absen_masukk', 'absen_keluar', 'absen_keluark', 'absen_lmbt', 'absen_lbh', 'absen_cd', 'absen_cp','absen_sts', 'absen_masuklok', 'absen_keluarlok', 'absen_tipe'])->orderBy('sisp_nm', 'asc')->orderBy('absen_tgl', 'asc');

        if($formData['filtert_tgl'] ==''){
            $Absen = $Absen->limit(10)->get();
        }else{
            $Absen = $Absen->get();
        }
            
        $Absen = AbsenController::setData($Absen);
        return $Absen;
    }
}

class MYPDF extends TCPDF
{
    public function getUser()
    {
        $user = Auth::user(); 

        return $user;
    }
    
    protected $data;
    public function Header()
    {
        # code...
    }
    public function footer()
    {
        $this->data['Pgn'] = $this->getUser();

        $this->setY(-15);
        $this->setFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '    ' . '*** ' . date("Y-m-d") . ' ***', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}