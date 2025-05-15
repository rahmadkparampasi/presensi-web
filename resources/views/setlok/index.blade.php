@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
@include('setlok.addData')
<div class="col-sm-12" >
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 col-lg-8 my-auto">
                    <h6>Data {{$PageTitle}}</h6>
                </div>
            </div>
        </div>
        <div class="card-body" id="{{$IdForm}}data">
            @include('setlok.data')
        </div>
    </div>
</div>

@include('includes.anotherscript')
@include('includes.ajaxinsertTV')
@endsection