@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
@include('reg.addData')
<div class="col-sm-12" >
    <div class="card">
        <div class="card-header">
            <h6>Data {{$PageTitle}}</h6>
            <div class="card-header-right">
                <button class='btn btn-primary' style="float: right;" onclick="showForm('{{$IdForm}}card', 'flex'); cActForm('{{$IdForm}}', '{{route('reg.insert')}}'); resetForm('{{$IdForm}}')"><i class="fa fa-plus"></i> TAMBAH</button>
            </div>
        </div>
        <div class="card-body" id="{{$IdForm}}data">
            @include('reg.data')
        </div>
    </div>
</div>
@include('layouts.modalViewPdf')
@include('includes.anotherscript')
@include('includes.ajaxinsertTV')
@endsection