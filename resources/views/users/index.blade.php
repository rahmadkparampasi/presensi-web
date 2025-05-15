@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
@include('users.addData')
<div class="col-sm-12" >
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 col-lg-8 my-auto">
                    <h6>Data {{$PageTitle}}</h6>
                </div>
                <div class="col-6 col-lg-4">
                    @if ($tipe=='A')
                        <button class='btn btn-primary mx-1' style="float: right;" onclick="showForm('{{$IdForm}}card', 'flex'); cActForm('{{$IdForm}}', '{{route('user.insert')}}'); resetForm('{{$IdForm}}'); showFormUsersInsert();"><i class="fa fa-plus"></i> TAMBAH</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body" id="{{$IdForm}}data">
            @include('users.data')
        </div>
    </div>
</div>
<script>
    function showFormUsersInsert(){
        $('#{{$IdForm}}title').html('Tambah');
        $('#users_orgFormGroup').hide(); 
        $('#users_org').removeAttr('required');
        $('#passwordFormGroup').show(); 
        $('#password').attr('required', '');
        $('#password1FormGroup').show(); 
        $('#password_confirmation').attr('required', '');
    }
    function showFormUsersUpdate(){
        $('#{{$IdForm}}title').html('Ubah');
        $('#users_orgFormGroup').hide(); 
        $('#users_org').removeAttr('required');
        $('#passwordFormGroup').hide(); 
        $('#password').removeAttr('required');
        $('#password1FormGroup').hide(); 
        $('#password_confirmation').removeAttr('required');
    }
</script>
@include('users.modalChangeReset')
@include('users.modalChangePwd')
@include('includes.anotherscript')
@include('includes.ajaxinsertTV')
@endsection