<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCPDF;

class MYPDF extends TCPDF
{
    public function Header()
    {
        # code...
    }
    public function footer()
    {
        $this->setY(-15);
        $this->setFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '    ' . '*** ' . date("Y-m-d") . ' ***', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class GuruCtkController extends Controller
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

    public function index($kat, $id = '')
    {
        return $this->viewPdf($this->data, $kat, $id);
    }

    public function viewPdf($formData, $kat = '', $id = '', )
    {
        ob_start();

        $pdf = new MYPDF('L', 'mm', 'Legal', true, 'UTF-8', false);

        $pdf->setCreator(PDF_CREATOR);
        //Bug
        $pdf->setAuthor('Kirana Tri Gemilang');
        // $pdf->setTitle('E-INM');
        $pdf->setSubject('Absensi Digital Cekatan');
        $pdf->setKeywords('Cekatan');

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

        $urlLogo = '/assets/img/logocop.png';
        $urlLine = '/assets/img/line.png';
        $urlCekatan = '/assets/img/Logo-NLT-1080.png';
        $tblpri = '<div class="container-fluid"> <table cellspacing="0" cellpadding="2" >
                <tr>';
        $tblpri .= '<td rowspan="2" align="center" width="60"><img src="' . $urlCekatan . '" width="180" alt="Logo / Lambang CEKATAN"></td>';
        $tblpri .= '<td rowspan="2" align="center" width="60"><img src="' . $urlLogo . '" width="180" alt="Logo / Lambang SMA 1 Parigi"></td>';
        $tblpri .= '<td rowspan="2" align="center" width="8.5"><img src="' . $urlLine . '" width="180" alt="GARIS"></td>';
        $tblpri .= '
                <td width="500"><font size="17" style="text-transform:uppercase"><b>SMA NEGERI 1 PARIGI</b></font></td>
            </tr>
            <tr>
                <td width="500"><font size="12" style="text-transform:uppercase"><b>ABSENSI DIGITAL CEKATAN</b></font></td>
            </tr>
            </table></div>';
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = 10, $tblpri, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
        
        if ($kat == '' && $id == '') {
            $dpri = '<br><br><p align="center" ><font size="12" style="text-transform:uppercase"><b>TIDAK ADA DATA</b></font></p>';
            $pdf->writeHTML($dpri, true, false, false, false, '');

            $pdf->Output('Cekatan - Data Pegawai.pdf', 'I');
            exit;
        }
        $this->data['Pgn'] = $this->getUser();

        $namaKelas = 'Data Pegawai';
        
        $Setkatpes_ps = SetkatpesController::getDataPgStat();

        $Guru = DB::table('sisp')->leftJoin('sispdp', 'sisp.sisp_id', '=', 'sispdp.sispdp_sisp')->leftJoin('setstspeg', 'sispdp.sispdp_setstspeg', '=', 'setstspeg.setstspeg_id')->where('sisp_setkatpes', $Setkatpes_ps->setkatpes_id);
        if ($kat=='sts') {
            $Sts = SetstspegController::getNama($id);
            $namaKelas .= '. Status : '.$Sts;

            $Guru->where('sispdp_setstspeg', $id);
        }
        $Guru = $Guru->select(['sisp_id', 'sisp_idsp', 'sisp_nmd', 'sisp_nmb', 'sisp_nm', 'sisp_tmptlhr', 'sisp_tgllhr', 'sisp_jk', 'sisp_alt', 'setstspeg_nm', 'sisp_conf', 'sisp_conft'])->orderBy('sisp_nm', 'asc')->orderBy('sisp_ord', 'desc')->get();
        
        if ($Guru==null) {
            $dpri = '<br><br><p align="center" ><font size="12" style="text-transform:uppercase"><b>TIDAK ADA DATA</b></font></p>';
            $pdf->writeHTML($dpri, true, false, false, false, '');

            $pdf->Output('Cekatan - Data Guru Dan Pegawai.pdf', 'I');
            exit;
        }

        $Guru = GuruController::setData($Guru, $formData);

        $TabelSiswa = GuruCtkController::generateTablePDF($Guru, '', $namaKelas);
        
        $pdf->writeHTML($TabelSiswa[0], true, false, false, false, '');
        
        $pdf->writeHTML($TabelSiswa[1], true, false, false, false, '');

        $pdf->Output('Cekatan - ' . $namaKelas .'.pdf', 'I');
        exit;
    }

    static function generateTablePDF($data, $pb = '', $bag = '')
    {
        $dpri = '<br '.$pb.'><p><strong><font size="12" >'.$bag.'<font></strong></p>';

        $tblhar = '<table nobr="false" cellspacing="0" cellpadding="6" border="1" width="100%" style="font-size:9pt;">
            <thead>
                <tr>
                    <th align="center" width="5%">No</th>
                    <th align="center" width="14%" class="text-wrap">NIS/NISN</th>
                    <th align="center" width="23%" class="text-wrap">Nama Lengkap</th>
                    <th align="center" width="10%" class="text-wrap">Status</th>
                    <th align="center" width="17%" class="text-wrap">TTL / Umur</th>
                    <th align="center" width="13%" class="text-wrap">Jenis Kelamin</th>
                    <th align="center" width="18%" class="text-wrap">Alamat</th>
                </tr>
            </thead>
            <tbody>';
        if (count($data)==0) {
            $tblhar .= '<tr><td colspan="8" align="center" width="100%">Tidak Ada Data</td></tr>';
        }else{
        
            $no = 1;
            foreach ($data as $tk) {
                $bgColor = "";
                if ($tk->sisp_conf=="1") {
                    $bgColor = 'bgcolor="#FF0000"';
                }elseif($tk->sisp_conf=="2"){
                    $bgColor = 'bgcolor="#00FF00"';
                }
                $tblhar .= '<tr nobr="true">
                <td '.$bgColor.' align="center" width="5%">' . $no++ . '</td>
                <td align="left" width="14%">' . $tk->sisp_idsp . '</td>
                <td align="left" width="23%">' . stripslashes($tk->sisp_nmAltT) . '</td>
                <td align="left" width="10%">' . $tk->setstspeg_nm . '</td>
                <td align="left" width="17%">' . ucwords(strtolower(stripslashes($tk->sisp_tmptlhr).', '.$tk->sisp_tgllhrAltT)) . '<br/>Umur'.$tk->umur.'</td>
                <td align="left" width="13%">' . $tk->sisp_jkAltT . '</td>
                <td align="left" width="18%">' . $tk->sisp_altAltT . '</td>
                </tr>';
            }
        }

        $tblhar .= '</tbody><tfoot>
        <tr>
        <td colspan="5" align="center" width="64%"><strong>Jumlah Pegawai</strong></td>
        <td colspan="3" align="center" width="36%"><strong>'.(string)count($data).'</strong></td>
        </tr>
        </tfoot></table>';
        
        return [$dpri, $tblhar];
    }
}
