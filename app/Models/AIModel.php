<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIModel extends Model
{
    public function getRandStr($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function getRandJStr($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function monthConv($month)
    {
        if ($month == "January") {
            $month = "Januari";
        } elseif ($month == "February") {
            $month = "Februari";
        } elseif ($month == "March") {
            $month = "Maret";
        } elseif ($month == "April") {
            $month = "April";
        } elseif ($month == "May") {
            $month = "Mei";
        } elseif ($month == "June") {
            $month = "Juni";
        } elseif ($month == "July") {
            $month = "Juli";
        } elseif ($month == "August") {
            $month = "Agustus";
        } elseif ($month == "September") {
            $month = "September";
        } elseif ($month == "October") {
            $month = "Oktober";
        } elseif ($month == "November") {
            $month = "November";
        } elseif ($month == "December") {
            $month = "Desember";
        } else {
            $month = $month;
        }

        return $month;
    }

    static function monthConvSt($month)
    {
        if ($month == "January") {
            $month = "Januari";
        } elseif ($month == "February") {
            $month = "Februari";
        } elseif ($month == "March") {
            $month = "Maret";
        } elseif ($month == "April") {
            $month = "April";
        } elseif ($month == "May") {
            $month = "Mei";
        } elseif ($month == "June") {
            $month = "Juni";
        } elseif ($month == "July") {
            $month = "Juli";
        } elseif ($month == "August") {
            $month = "Agustus";
        } elseif ($month == "September") {
            $month = "September";
        } elseif ($month == "October") {
            $month = "Oktober";
        } elseif ($month == "November") {
            $month = "November";
        } elseif ($month == "December") {
            $month = "Desember";
        } else {
            $month = $month;
        }

        return $month;
    }

    public function monthConvInt($month)
    {
        if ($month == 1) {
            $month = "Januari";
        } elseif ($month == 2) {
            $month = "Februari";
        } elseif ($month == 3) {
            $month = "Maret";
        } elseif ($month == 4) {
            $month = "April";
        } elseif ($month == 5) {
            $month = "Mei";
        } elseif ($month == 6) {
            $month = "Juni";
        } elseif ($month == 7) {
            $month = "Juli";
        } elseif ($month == 8) {
            $month = "Agustus";
        } elseif ($month == 9) {
            $month = "September";
        } elseif ($month == 10) {
            $month = "Oktober";
        } elseif ($month == 11) {
            $month = "November";
        } elseif ($month == 12) {
            $month = "Desember";
        } else {
            $month = $month;
        }

        return $month;
    }

    static function monthConvIntSt($month)
    {
        $month = (int)$month;
        if ($month == 1) {
            $month = "Januari";
        } elseif ($month == 2) {
            $month = "Februari";
        } elseif ($month == 3) {
            $month = "Maret";
        } elseif ($month == 4) {
            $month = "April";
        } elseif ($month == 5) {
            $month = "Mei";
        } elseif ($month == 6) {
            $month = "Juni";
        } elseif ($month == 7) {
            $month = "Juli";
        } elseif ($month == 8) {
            $month = "Agustus";
        } elseif ($month == 9) {
            $month = "September";
        } elseif ($month == 10) {
            $month = "Oktober";
        } elseif ($month == 11) {
            $month = "November";
        } elseif ($month == 12) {
            $month = "Desember";
        } else {
            $month = $month;
        }

        return $month;
    }

    static function dayConvIntStr($day)
    {
        $day = (int)$day;
        if ($day == 0) {
            $day = "Minggu";
        } elseif ($day == 1) {
            $day = "Senin";
        } elseif ($day == 2) {
            $day = "Selasa";
        } elseif ($day == 3) {
            $day = "Rabu";
        } elseif ($day == 4) {
            $day = "Kamis";
        } elseif ($day == 5) {
            $day = "Jumat";
        } elseif ($day == 6) {
            $day = "Sabtu";
        } else {
            $day = $day;
        }

        return $day;
    }

    public function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }

    static function rupiahStat($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }

    public function rupiahWF($data = [], $field)
    {
        for ($j = 0; $j < count($field); $j++) {
            for ($i = 0; $i < count($data); $i++) {
                $data[$i][$field[$j]] = $this->rupiah($data[$i][$field[$j]]);
            }
        }
        return $data;
    }

    function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->penyebut($nilai - 10) . " Belas";
        } else if ($nilai < 100) {
            $temp = $this->penyebut($nilai / 10) . " Puluh" . $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->penyebut($nilai / 100) . " Ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->penyebut($nilai / 1000) . " Ribu" . $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->penyebut($nilai / 1000000) . " Juta" . $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->penyebut($nilai / 1000000000) . " Milyar" . $this->penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->penyebut($nilai / 1000000000000) . " Trilyun" . $this->penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    static function penyebutStat($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = AIModel::penyebutStat($nilai - 10) . " Belas";
        } else if ($nilai < 100) {
            $temp = AIModel::penyebutStat($nilai / 10) . " Puluh" . AIModel::penyebutStat($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . AIModel::penyebutStat($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = AIModel::penyebutStat($nilai / 100) . " Ratus" . AIModel::penyebutStat($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . AIModel::penyebutStat($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = AIModel::penyebutStat($nilai / 1000) . " Ribu" . AIModel::penyebutStat($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = AIModel::penyebutStat($nilai / 1000000) . " Juta" . AIModel::penyebutStat($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = AIModel::penyebutStat($nilai / 1000000000) . " Milyar" . AIModel::penyebutStat(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = AIModel::penyebutStat($nilai / 1000000000000) . " Trilyun" . AIModel::penyebutStat(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim($this->penyebut($nilai));
        } else {
            $hasil = trim($this->penyebut($nilai));
        }
        return $hasil;
    }

    static function terbilangStat($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim(AIModel::penyebutStat($nilai));
        } else {
            $hasil = trim(AIModel::penyebutStat($nilai));
        }
        return $hasil;
    }

    function convertDayToRow($Day)
    {
        if ($Day == 0) {
            $Day = "A";
        } elseif ($Day == 1) {
            $Day = "B";
        } elseif ($Day == 2) {
            $Day = "C";
        } elseif ($Day == 3) {
            $Day = "D";
        } elseif ($Day == 4) {
            $Day = "E";
        } elseif ($Day == 5) {
            $Day = "F";
        } elseif ($Day == 6) {
            $Day = "G";
        } elseif ($Day == 7) {
            $Day = "H";
        } elseif ($Day == 8) {
            $Day = "I";
        } elseif ($Day == 9) {
            $Day = "J";
        } elseif ($Day == 10) {
            $Day = "K";
        } elseif ($Day == 11) {
            $Day = "L";
        } elseif ($Day == 12) {
            $Day = "M";
        } elseif ($Day == 13) {
            $Day = "N";
        } elseif ($Day == 14) {
            $Day = "O";
        } elseif ($Day == 15) {
            $Day = "P";
        } elseif ($Day == 16) {
            $Day = "Q";
        } elseif ($Day == 17) {
            $Day = "R";
        } elseif ($Day == 18) {
            $Day = "S";
        } elseif ($Day == 19) {
            $Day = "T";
        } elseif ($Day == 20) {
            $Day = "U";
        } elseif ($Day == 21) {
            $Day = "V";
        } elseif ($Day == 22) {
            $Day = "W";
        } elseif ($Day == 23) {
            $Day = "X";
        } elseif ($Day == 24) {
            $Day = "Y";
        } elseif ($Day == 25) {
            $Day = "Z";
        } elseif ($Day == 26) {
            $Day = "AA";
        } elseif ($Day == 27) {
            $Day = "AB";
        } elseif ($Day == 28) {
            $Day = "AC";
        } elseif ($Day == 29) {
            $Day = "AD";
        } else {
            $Day = "AE";
        }

        return $Day;
    }

    public function convertSekolah($skl)
    {
        if ($skl == "SD") {
            $skl = "SD/MI";
        } elseif ($skl == "SMP") {
            $skl = "SMP/MTs";
        } elseif ($skl == "SMA") {
            $skl = "SMA/SMK/Ma";
        } elseif ($skl == "D1") {
            $skl = "Diploma 1";
        } elseif ($skl == "D2") {
            $skl = "Diploma 2";
        } elseif ($skl == "D3") {
            $skl = "Diploma 3";
        } elseif ($skl == "D4") {
            $skl = "Diploma 4";
        } elseif ($skl == "S1") {
            $skl = "Sarjana";
        } elseif ($skl == "S2") {
            $skl = "Magister";
        } elseif ($skl == "S3") {
            $skl = "Doktor";
        } elseif ($skl == "Pr") {
            $skl = "Pendidikan Profesi";
        } elseif ($skl == "Vk") {
            $skl = "Pendidikan Vokasi";
        } elseif ($skl == "Pk") {
            $skl = "Pendidikan Khusus";
        } else {
            $skl = $skl;
        }

        return $skl;
    }

    public function convertSertifikat($ser)
    {
        if ($ser == "PH") {
            $ser = "Penghargaan";
        } elseif ($ser == "SM") {
            $ser = "Seminar";
        } elseif ($ser == "LM") {
            $ser = "Lomba";
        } elseif ($ser == "MG") {
            $ser = "Magang";
        } elseif ($ser == "PL") {
            $ser = "Pelatihan";
        } elseif ($ser == "KH") {
            $ser = "Keahlian";
        } elseif ($ser == "PR") {
            $ser = "Profesi";
        } else {
            $ser = $ser;
        }

        return $ser;
    }

    public function convertJKWF($data = [], $field = '')
    {
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i][$field] == "L") {
                $data[$i][$field] = "LAKI - LAKI";
            } elseif ($data[$i][$field] == "P") {
                $data[$i][$field] = "PEREMPUAN";
            }
        }
        return $data;
    }
    public function convertJKNF($data = [], $field = '')
    {
        if ($data[$field] == "L") {
            $data[$field] = "LAKI-LAKI";
        } elseif ($data[$field] == "P") {
            $data[$field] = "PEREMPUAN";
        }
        return $data;
    }

    public function convertJK($data = '')
    {
        if ($data == "L") {
            $data = "LAKI-LAKI";
        } elseif ($data == "P") {
            $data = "PEREMPUAN";
        }
        return $data;
    }

    public function replaceTextWF($data = [], $array = [], $search = '', $replace = '')
    {
        for ($i = 0; $i < count($array); $i++) {
            for ($j = 0; $j < count($data); $j++) {
                $data[$j][$array[$i]] = str_replace($search, $replace, $data[$j][$array[$i]]);
            }
        }
        return $data;
    }
    public function replaceTextNF($data, $search = [], $replace = [])
    {
        for ($i = 0; $i < count($search); $i++) {
            $data = str_replace($search[$i], $replace[$i], $data);
        }
        return $data;
    }

    public function changeDateNF($data = '')
    {
        $date = [];
        $date['d'] = (string)date('d', strtotime($data));
        $date['F'] = (string)date('F', strtotime($data));
        $date['F'] = $this->monthConv($date['F']);
        $date['Y'] = (string)date('Y', strtotime($data));
        return strtoupper($date['d'] . " " . $date['F'] . " " . $date['Y']);
    }

    static function changeDateNFSt($data = '')
    {
        $date = [];
        $date['d'] = (string)date('d', strtotime($data));
        $date['F'] = (string)date('F', strtotime($data));
        $date['F'] = AiModel::monthConvSt($date['F']);
        $date['Y'] = (string)date('Y', strtotime($data));
        return strtoupper($date['d'] . " " . $date['F'] . " " . $date['Y']);
    }

    static function changeDateSt($data, $date = [])
    {
        if (is_countable($data)) {
            for ($j = 0; $j < count($date); $j++) {
                for ($i = 0; $i < count($data); $i++) {
                    if ($data[$i][$date[$j]] == "0000-00-00") {
                        $data[$i][$date[$j]] = "BELUM ADA TANGGAL LAHIR";
                    } else {
    
                        $d = (string)date('d', strtotime($data[$i][$date[$j]]));
                        $F = (string)date('F', strtotime($data[$i][$date[$j]]));
                        $F = AIModel::monthConvSt($F);
                        $Y = (string)date('Y', strtotime($data[$i][$date[$j]]));
                        $data[$i][$date[$j]] =  strtoupper($d . " " . $F . " " . $Y);
                    }
                }
            }
            return $data;
        }else{
            $date = [];
            $date['d'] = (string)date('d', strtotime($data));
            $date['F'] = (string)date('F', strtotime($data));
            $date['F'] = AiModel::monthConvSt($date['F']);
            $date['Y'] = (string)date('Y', strtotime($data));
            return strtoupper($date['d'] . " " . $date['F'] . " " . $date['Y']);
        }
    }

    public function changeDateGaris($data)
    {
        $this->data['d'] = (string)date('d', strtotime($data));
        $this->data['m'] = (string)date('m', strtotime($data));
        $this->data['Y'] = (string)date('Y', strtotime($data));
        return strtoupper($this->data['d'] . "-" . $this->data['m'] . "-" . $this->data['Y']);
    }

    public function changeDateWF($data, $date)
    {
        for ($j = 0; $j < count($date); $j++) {
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i][$date[$j]] == "0000-00-00") {
                    $data[$i][$date[$j]] = "BELUM ADA TANGGAL LAHIR";
                } else {

                    $this->data['d'] = (string)date('d', strtotime($data[$i][$date[$j]]));
                    $this->data['F'] = (string)date('F', strtotime($data[$i][$date[$j]]));
                    $this->data['F'] = $this->monthConv($this->data['F']);
                    $this->data['Y'] = (string)date('Y', strtotime($data[$i][$date[$j]]));
                    $data[$i][$date[$j]] =  strtoupper($this->data['d'] . " " . $this->data['F'] . " " . $this->data['Y']);
                }
            }
        }
        return $data;
    }
    //change Badge
    public function cB($text, $badge = 'success')
    {
        return "<span class='badge bg-" . $badge . "'>" . $text . "</span>";
    }

    public function cBWF($data, $text, $badge = [])
    {
        for ($i = 0; $i < count($data); $i++) {
            $data[$i][$text] =  "<span class='badge bg-" . $badge[$i] . "'>" . $data[$i][$text] . "</span>";
        }
        return $data;
    }
    public function cBWFIF($data, $text, $replace, $badge = [], $if = [])
    {


        for ($i = 0; $i < count($data); $i++) {
            for ($j = 0; $j < count($if); $j++) {
                if ($if[$j] == $data[$i][$text]) {
                    $data[$i][$replace] =  "<span class='badge badge-" . $badge[$j] . "'>" . $data[$i][$replace] . "</span>";
                } else {
                    continue;
                }
            }
        }
        // dd($data, $text, $badge, $if);
        return $data;
    }
}