<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Faker;

class FakeController extends Controller
{
    public function comp()
    {
        $Faker= Faker\Factory::create('id_ID');
        $name = $Faker->company();
        echo "Nama: ".$name."<br/>";
        $date =$Faker->dateTimeThisDecade('-1 years');
        $result = $date->format('Y-m-d H:i:s');
        echo "Tanggal Terbentuk: ".$result."<br/>";
        echo "EMail: ".strtolower(str_replace(['(', ')', ' '], ['','',''],$name))."@".$Faker->freeEmailDomain()."<br/>";
        echo "Telepon: ".str_replace(['(', ')', ' '], ['','',''],$Faker->phoneNumber())."<br/>";
    }

    public function person()
    {
        $Faker= Faker\Factory::create('id_ID');
        $name = $Faker->name();
        echo "Nama Lengkap: ".$name."<br/>";
        echo "NIK: ".$Faker->nik()."<br/>";
        $date = $Faker->dateTimeInInterval('-35 years', '+20 years');
        $result = $date->format('Y-m-d H:i:s');
        echo "Tempat Lahir: ".$Faker->city()."<br/>";
        echo "Tanggal Lahir: ".$result."<br/>";
        echo "Alamat: ".$Faker->address()."<br/>";
        echo "EMail: ".strtolower(str_replace(['(', ')', ' '], ['','',''],$name))."@".$Faker->freeEmailDomain()."<br/>";
        echo "Telepon: ".str_replace(['(', ')', ' '], ['','',''],$Faker->phoneNumber())."<br/>";

    }
}
