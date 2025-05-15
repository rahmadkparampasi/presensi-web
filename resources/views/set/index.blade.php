@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')

<div class="col-12" >
    <h5>Pengaturan Absensi & Izin</h5>
    <hr>
</div>
<div class="col-sm-6 col-md-4" >
    <div class="card text-left">
        <div class="card-body">
            <h5 class="card-title">Data Kategori Peserta & Jadwal</h5>
            <a href="{{route('setkatpes.index')}}" class="btn btn-primary has-ripple">Buka<span class="ripple ripple-animate" style="height: 139.183px; width: 139.183px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(255, 255, 255); opacity: 0.4; top: -59.7915px; left: 25.4085px;"></span></a>
        </div>
    </div>
</div>
<div class="col-sm-6 col-md-4" >
    <div class="card text-left">
        <div class="card-body">
            <h5 class="card-title">Kategori Izin</h5>
            <a href="{{route('setkati.index')}}" class="btn btn-primary has-ripple">Buka<span class="ripple ripple-animate" style="height: 139.183px; width: 139.183px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(255, 255, 255); opacity: 0.4; top: -59.7915px; left: 25.4085px;"></span></a>
        </div>
    </div>
</div>
<div class="col-12" >
    <h5>Pengaturan Data Pendukung</h5>
    <hr>
</div>
<div class="col-sm-6 col-md-4" >
    <div class="card text-left">
        <div class="card-body">
            <h5 class="card-title">Data Lokasi</h5>
            <a href="{{route('setlok.index')}}" class="btn btn-primary has-ripple">Buka<span class="ripple ripple-animate" style="height: 139.183px; width: 139.183px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(255, 255, 255); opacity: 0.4; top: -59.7915px; left: 25.4085px;"></span></a>
        </div>
    </div>
</div>
<div class="col-sm-6 col-md-4" >
    <div class="card text-left">
        <div class="card-body">
            <h5 class="card-title">Data Status Pegawai</h5>
            <a href="{{route('setstspeg.index')}}" class="btn btn-primary has-ripple">Buka<span class="ripple ripple-animate" style="height: 139.183px; width: 139.183px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(255, 255, 255); opacity: 0.4; top: -59.7915px; left: 25.4085px;"></span></a>
        </div>
    </div>
</div>
<div class="col-sm-6 col-md-4" >
    <div class="card text-left">
        <div class="card-body">
            <h5 class="card-title">Data Tingkat Pendidikan</h5>
            <a href="{{route('setpd.index')}}" class="btn btn-primary has-ripple">Buka<span class="ripple ripple-animate" style="height: 139.183px; width: 139.183px; animation-duration: 0.7s; animation-timing-function: linear; background: rgb(255, 255, 255); opacity: 0.4; top: -59.7915px; left: 25.4085px;"></span></a>
        </div>
    </div>
</div>

@include('includes.anotherscript')
@endsection