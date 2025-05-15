@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
{{-- @include('guru.addData') --}}
<div class="col-sm-12" >
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 col-lg-8 my-auto">
                    <h6>Data {{$PageTitle}}</h6>
                </div>
                <div class="col-6 col-lg-4">
                    <button class='btn btn-success m-1' style="float: right;" onclick="showForm('{{$IdForm}}filterForm', 'block'); cActForm('{{$IdForm}}filterForm', '{{route('sisp.filter', [$act])}}'); resetForm('{{$IdForm}}filterForm')"><i class="fa fa-sliders-h"></i></button>
                    @if ($act=="1")
                        <button class='btn btn-primary m-1' style="float: right;" data-target="#modalAddData" data-toggle="modal" onclick="resetForm('modalAddDataForm');"><i class="fa fa-plus"></i> TAMBAH</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body" id="{{$IdForm}}filterData">
            @include('guru.filterData')
        </div>
        <div class="card-body" id="{{$IdForm}}data">
            @include('guru.data')
        </div>
    </div>
</div>
@if ($act=="1")
    @include('guru.modalAddData', ['countModalBody' => '1', 'countModalFooter' => '1'])
@endif
@include('layouts.modalViewLabel')
@include('layouts.modalViewImg')
@include('includes.anotherscript')
@include('includes.ajaxinsertTV')
@endsection