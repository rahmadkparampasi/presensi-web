@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h6>Data {{$PageTitle}}</h6>
        </div>
        <div class="card-body">
            <table class="dtK display table align-items-centertable-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-wrap">Tahun Dan Tahapan</th>
                        <th class="text-wrap">Penilaian</th>
                        <th class="text-wrap">Progress</th>
                        <th class="text-wrap">Tanggal Mulai</th>
                        <th class="text-wrap">Tanggal Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 0;
                    @endphp
                    @foreach ($Nl as $tk) @php $no++ @endphp 
                    
                    <tr>
                        <td>{{$no}}</td>
                        <td class="text-wrap">{{$tk['nl_thn']."-".$tk['nl_thpAlt']}}</td>
                        <td class="text-wrap">
                            <a href="/nl/viewDataInst/{{$tk['nl_id']}}" role="button" class="btn btn-info"><i class="fa fa-sync"></i></a>
                        </td>
                        <td>{!!$tk['progress']!!}</td>
                        <td class="text-wrap">{{$tk['nl_tglmAlt']}}</td>
                        <td class="text-wrap">{{$tk['nl_tglsAlt']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('includes.anotherscript')

@endsection