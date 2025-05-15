@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href=" https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css " rel="stylesheet">
<div class="col-md-4 order-md-1">
    @include('bag.dataTree')
</div>
<div class="col-md-8 order-md-2">
    <div class="card" id="cardButton">
        @include('bag.dataButton')
    </div>
    <div class="w-100" id="cardPpk">

    </div>
    <div class="w-100" id="cardSatker">

    </div>
</div>
{{-- <div class="col-sm-12" >
    <div class="card">
        <div class="card-header">
            <h6>Data {{$PageTitle}}</h6>
        </div>
        <div class="card-body" id="{{$IdForm}}data">
            @include('bag.data')
        </div>
    </div>
</div> --}}
@include('bag.modalAddBag')
@include('bag.modalAddRs')
@include('bag.modalChangeBag')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src=" https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/Gruntfile.min.js "></script>
@include('includes.anotherscript')
@include('includes.ajaxinsertTV')
@endsection