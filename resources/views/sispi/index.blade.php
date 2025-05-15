@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
<div class="col-sm-12" >
    <div class="card">
        <div class="card-header">
            <h6>Data {{$PageTitle}}</h6>
        </div>
        
        <div class="card-body" id="{{$IdForm}}data">
            @include('sispi.data')
        </div>
    </div>
</div>

@include('includes.anotherscript')
@include('includes.ajaxinsertTV')
@include('sispi.modalAddDataProfil', ['countModalBody' => 'Sispi', 'countModalFooter' => 'Sispi'])
@include('layouts.modalViewPdf')

@include('layouts.modalViewImg')
@endsection