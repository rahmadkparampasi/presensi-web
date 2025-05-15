<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Reader;
use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AbsenExcelController extends Controller
{
    public function eSisp($id = '', $month = '', $year = '')
    {
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

                   
            
                    $inputFileName = base_path('storage/app/public/template/Template_Excel.xlsx');
                    // dd($inputFileName);
                    $reader = new Reader();
                    // $spreadsheet = $reader->load($inputFileName);
            
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
            
                    $activeSheet = $spreadsheet->getActiveSheet();
                    $data = $activeSheet->toArray("", false, false);
                    $activeSheet->setCellValue('B1', strtoupper($Sisp->bag_nm.' '.$Bag->bag_nm));

                    // $cal = AbsenController::getCalendar($id, $month, $year);
                    $cal = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
                    for ($i=0; $i < $cal; $i++) { 
                        // if ($cal[$i]['val']==""||!is_int($cal[$i]['val'])) {
                        //     continue;
                        // }
        
                        $Absen = AbsenController::getAbsenByDate($id, $year."-".$month."-".(string)$i+1);
                        $activeSheet->getStyle('B'.(string)($i+6).':L'.(string)$i+6)->getAlignment()->setHorizontal('center');
                        if ($Absen!=null) {
                            $activeSheet->setCellValue('B'.(string)$i+6, $Absen->sisp_nm);
                            $activeSheet->setCellValue('C'.(string)$i+6, date('Y-m-d', strtotime($Absen->absen_tgl)));
                            $activeSheet->setCellValue('D'.(string)$i+6, date('H:i:s', strtotime($Absen->absen_masuk)));
                            if ($Absen->absen_masuklok=="1") {
                                $activeSheet->setCellValue('E'.(string)$i+6, 'Di area kantor');
                            }else{
                                $activeSheet->setCellValue('E'.(string)$i+6, 'Di luar area kantor');
                            }
                            $activeSheet->setCellValue('F'.(string)$i+6, date('H:i:s', strtotime($Absen->absen_keluar)));
                            if ($Absen->absen_keluarlok=="1") {
                                $activeSheet->setCellValue('G'.(string)$i+6, 'Di area kantor');
                            }else{
                                $activeSheet->setCellValue('G'.(string)$i+6, 'Di luar area kantor');
                            }
                            if ((int)$Absen->absen_lmbt>0) {
                                $activeSheet->setCellValue('H'.(string)$i+6, 'Terlambat: '.(string)intdiv((int)$Absen->absen_lmbt, 60).' Jam, '.(string)((int)$Absen->absen_lmbt % 60).' Menit');
                            }else{
                                if ($Absen->absen_masukk=="0"&&$Absen->absen_masuk!="00:00:00") {
                                    $activeSheet->setCellValue('H'.(string)$i+6, 'Masuk Diluar Jadwal');
                                }else{
                                    $activeSheet->setCellValue('H'.(string)$i+6, 'Masuk Tepat Waktu');
                                }
                            }
        
                            if ($Absen->absen_tipe=="D") {
                                $activeSheet->setCellValue('I'.(string)$i+6, 'DINAS');
                            }elseif ($Absen->absen_tipe=="O"){
                                $activeSheet->setCellValue('I'.(string)$i+6, 'ON-SITE');
                            }else{
                                $activeSheet->setCellValue('I'.(string)$i+6, $Absen->absen_tipe);
        
                            }
        
                        }else{
                            $activeSheet->setCellValue('B'.(string)$i+6, $Sisp->sisp_nm);
                            $activeSheet->setCellValue('C'.(string)$i+6, date('Y-m-d', strtotime($year.'-'.$month.'-'.$i+1)));
                            $spreadsheet->setActiveSheetIndex(0)->mergeCells('D'.(string)($i+6).':L'.(string)$i+6);
                            $activeSheet->setCellValue('D'.(string)$i+6, 'Tanpa Keterangan');
                            $activeSheet->getStyle('D'.(string)($i+6).':L'.(string)$i+6)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('C6E0B4');
        
                        }
                        $activeSheet->getStyle('B'.(string)($i+6).':L'.(string)$i+6)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('FF000000'));
                        $activeSheet->getStyle('B'.(string)($i+6).':L'.(string)$i+6)->getBorders()->getVertical()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('FF000000'));
        
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
                    
                    // $activeSheet->setCellValue('A3', 'Tes'); // write to column 5, row $i+1 (one based index)
                    $Label = AbsenController::labelFilter($data);
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment; filename="'. urlencode($Label['labelTanggal'].'-'.$Label['labelBagian'].'.xlsx.xlsx').'"');
                    $writer->save('php://output');
                }
            }
        }
        // $writer->save('Testing.xlsx'); // save to the same file
    }

    static function excelFilter(Request $request)
    {
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
        }else{
            $Satker = DB::table('bag')->where('bag_str', '2')->select(['bag_id', 'bag_nm'])->orderBy('bag_nm')->get();
        }

        if ($Satker==null) {
            echo "<script>window.close();</script>";
        }

        // if ($data['filtert_tgl'] == '') {
        //     echo "<script>window.close();</script>";
        // }

        $Label = AbsenController::labelFilter($data);

        $spreadsheet = new Spreadsheet;
        for ($i=0; $i < count($Satker); $i++) { 
            if ($i!=0) {
                $spreadsheet->createSheet();
            }
            $activeSheet = $spreadsheet->setActiveSheetIndex($i);
            $activeSheet->setTitle('SATKER '.((string)($i+1)));
            // $activeSheet->setCellValue('A1', $Satker[$i]->bag_nm);
            $Ppk = DB::table('bag');
            
            if ($data['filters_ppk'] !='') {
                $Ppk = $Ppk->where('bag_id', $data['filters_ppk']);
            }else{
                $Ppk = $Ppk->where('bag_prnt', $Satker[$i]->bag_id);
            }

            $Ppk = $Ppk->select(['bag_id', 'bag_nm'])->orderBy('bag_nm')->get();

            $activeSheet->getColumnDimension('A')->setWidth(27);
            $activeSheet->getColumnDimension('B')->setWidth(13);
            $activeSheet->getColumnDimension('C')->setWidth(11);
            $activeSheet->getColumnDimension('D')->setWidth(17);
            $activeSheet->getColumnDimension('E')->setWidth(11);
            $activeSheet->getColumnDimension('F')->setWidth(17);
            $activeSheet->getColumnDimension('G')->setWidth(25);
            $activeSheet->getColumnDimension('H')->setWidth(9);
            $activeSheet->getColumnDimension('I')->setWidth(18);
            $activeSheet->getColumnDimension('J')->setWidth(26);
            $activeSheet->getColumnDimension('K')->setWidth(26);
            if ($Ppk!=null) {
                $array = 0;
                $arrayNext = 0;
                for ($j=0; $j < count($Ppk); $j++) {
                    $Label['labelBagian'] = $Ppk[$j]->bag_nm;
                    $DataAbsen = AbsenPdfController::loadDataCtk($data, $Ppk[$j]->bag_id);
                    if ($j!=0) {
                        if (count($DataAbsen)==0) {
                            $array = $array + count($DataAbsen)+2+5;
                        }else{
                            $array = $array + count($DataAbsen)+2+4;
                        }
                        $arrayNext = $array;
                    }else{
                        if (count($DataAbsen)==0) {
                            $array = $array + count($DataAbsen);
                        }else{
                            $array = $array + count($DataAbsen);
                        }
                        $arrayNext = 0;
                    }
                    $activeSheet->setCellValue('A'.((string)($arrayNext+1)), strtoupper($Label['labelTanggal']));
                    $activeSheet->mergeCells('A'.((string)($arrayNext+1)).':K'.((string)$arrayNext+1));

                    $activeSheet->setCellValue('A'.((string)($arrayNext+2)), strtoupper($Satker[$i]->bag_nm));
                    $activeSheet->mergeCells('A'.((string)($arrayNext+2)).':K'.((string)$arrayNext+2));

                    $activeSheet->setCellValue('A'.((string)($arrayNext+3)), strtoupper($Ppk[$j]->bag_nm));
                    $activeSheet->mergeCells('A'.((string)($arrayNext+3)).':K'.((string)$arrayNext+3));

                    $activeSheet->setCellValue('A'.((string)($arrayNext+4)), 'Nama');
                    $activeSheet->setCellValue('B'.((string)($arrayNext+4)), 'Tanggal');
                    $activeSheet->setCellValue('C'.((string)($arrayNext+4)), 'Masuk');
                    $activeSheet->setCellValue('D'.((string)($arrayNext+4)), 'M Lokasi');
                    $activeSheet->setCellValue('E'.((string)($arrayNext+4)), 'Pulang');
                    $activeSheet->setCellValue('F'.((string)($arrayNext+4)), 'P Lokasi');
                    $activeSheet->setCellValue('G'.((string)($arrayNext+4)), 'Jam Kerja');
                    $activeSheet->setCellValue('H'.((string)($arrayNext+4)), 'Status');
                    $activeSheet->setCellValue('I'.((string)($arrayNext+4)), 'Keterangan');
                    $activeSheet->setCellValue('J'.((string)($arrayNext+4)), 'Pengecekan');
                    $activeSheet->setCellValue('K'.((string)($arrayNext+4)), 'Evaluasi');

                    $activeSheet->getStyle('A'.((string)($arrayNext+1)).':K'.(string)$arrayNext+4)->getAlignment()->setHorizontal('center');
                    $activeSheet->getStyle('A'.((string)($arrayNext+1)).':K'.(string)$arrayNext+4)->getFont()->setBold(true);
                    $activeSheet->getStyle('A'.((string)($arrayNext+1)).':K'.(string)$arrayNext+3)->getFont()->setSize(20);

                    if (count($DataAbsen)==0) {
                        $activeSheet->setCellValue('A'.((string)($arrayNext+5)), 'TIDAK ADA DATA');
                        $activeSheet->mergeCells('A'.((string)($arrayNext+5)).':K'.((string)$arrayNext+5));
                        $activeSheet->getStyle('A'.((string)($arrayNext+5)).':K'.(string)$arrayNext+5)->getAlignment()->setHorizontal('center');

                        $activeSheet->getStyle('A'.(string)($arrayNext+4).':K'.(string)$arrayNext+5)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('FF000000'));
                        $activeSheet->getStyle('A'.(string)($arrayNext+4).':K'.(string)$arrayNext+5)->getBorders()->getVertical()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('FF000000'));
                        $activeSheet->getStyle('A'.(string)($arrayNext+4).':K'.(string)$arrayNext+5)->getBorders()->getHorizontal()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('FF000000'));
                    }else{
                        for ($k=0; $k < count($DataAbsen); $k++) { 
                            $activeSheet->setCellValue('A'.((string)($arrayNext+$k+6)), $DataAbsen[$k]->sisp_nm);
                            $activeSheet->setCellValue('B'.((string)($arrayNext+$k+6)), $DataAbsen[$k]->absen_tgl);
                            $activeSheet->setCellValue('C'.((string)($arrayNext+$k+6)), $DataAbsen[$k]->absen_masuk);
                            if ($DataAbsen[$k]->absen_masuklok=="1") {
                                $activeSheet->setCellValue('D'.((string)($arrayNext+$k+6)), 'Di area kantor');
                            }else{
                                $activeSheet->setCellValue('D'.((string)($arrayNext+$k+6)), 'Di luar area kantor');
                            }
                            $activeSheet->setCellValue('E'.((string)($arrayNext+$k+6)), $DataAbsen[$k]->absen_keluar);
                            if ($DataAbsen[$k]->absen_keluarlok=="1") {
                                $activeSheet->setCellValue('F'.((string)($arrayNext+$k+6)), 'Di area kantor');
                            }else{
                                $activeSheet->setCellValue('F'.((string)($arrayNext+$k+6)), 'Di luar area kantor');
                            }
                            if ((int)$DataAbsen[$k]->absen_lmbt>0) {
                                $activeSheet->setCellValue('G'.((string)($arrayNext+$k+6)), 'Terlambat: '.(string)intdiv((int)$DataAbsen[$k]->absen_lmbt, 60).' Jam, '.(string)((int)$DataAbsen[$k]->absen_lmbt % 60). ' Menit');
                            }else{
                                if ($DataAbsen[$k]->absen_masukk=="0"&&$DataAbsen[$k]->absen_masuk!="00:00:00") {
                                    $activeSheet->setCellValue('G'.((string)($arrayNext+$k+6)), 'Masuk Diluar Jadwal');
                                }else{
                                    $activeSheet->setCellValue('G'.((string)($arrayNext+$k+6)), 'Masuk Tepat Waktu');
                                }
                            }

                            if ($DataAbsen[$k]->absen_tipe=="D") {
                                $activeSheet->setCellValue('H'.((string)($arrayNext+$k+6)), 'Dinas');
                            }elseif ($DataAbsen[$k]->absen_tipe=="O") {
                                $activeSheet->setCellValue('H'.((string)($arrayNext+$k+6)), 'ON-SITE');
                            }else{
                                $activeSheet->setCellValue('H'.((string)($arrayNext+$k+6)), $DataAbsen[$k]->absen_tipe);
                            }
                        }
                        $activeSheet->getStyle('A'.(string)($arrayNext+4).':K'.(string)$arrayNext+$k+6)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('FF000000'));
                        $activeSheet->getStyle('A'.(string)($arrayNext+4).':K'.(string)$arrayNext+$k+6)->getBorders()->getVertical()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('FF000000'));
                        $activeSheet->getStyle('A'.(string)($arrayNext+4).':K'.(string)$arrayNext+$k+6)->getBorders()->getHorizontal()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('FF000000'));
                    }
                    

                    // $activeSheet->setCellValue('B1', strtoupper($Sisp->bag_nm.' '.$Bag->bag_nm));
                }
            }
        }

        $Label = AbsenController::labelFilter($data);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($Label['labelTanggal'].'-'.$Label['labelBagian'].'.xlsx.xlsx').'"');
        $writer->save('php://output');
        
    }
}
