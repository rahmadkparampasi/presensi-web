@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
@include('setcks.addData')
<div class="col-sm-12" >
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 col-lg-8 my-auto">
                    <h6>Data {{$PageTitle}}</h6>
                </div>
                <div class="col-6 col-lg-4">
                    <button class='btn btn-primary mx-1' style="float: right;" onclick="showForm('{{$IdForm}}card', 'flex'); cActForm('{{$IdForm}}', '{{route('setcks.insert')}}'); resetForm('{{$IdForm}}'); "><i class="fa fa-plus"></i> TAMBAH</button>
                </div>
            </div>
        </div>
        <div class="card-body" id="{{$IdForm}}data">
            @include('setcks.data')
        </div>
    </div>
</div>

@include('includes.anotherscript')
@include('includes.ajaxinsertTV')
@endsection