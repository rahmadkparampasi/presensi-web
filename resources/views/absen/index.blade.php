@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
{{-- @include('absen.addData') --}}
<div class="col-sm-12" >
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 col-lg-8 my-auto">
                    <h6>Data {{$PageTitle}}</h6>
                </div>
                <div class="col-6 col-lg-4">
                    
                </div>
            </div>
        </div>
        <div class="card-body" id="{{$IdForm}}filterData">
            @include('absen.filterData')
        </div>
        <div class="card-body" id="{{$IdForm}}data">
            @include('absen.data')
        </div>
    </div>
</div>

@include('includes.anotherscript')
<script>
    $(function() {
        $(document).ready(function() {
            $('#{{$IdForm}}').parsley();
            var {{$IdForm}} = $('#{{$IdForm}}');
            {{$IdForm}}.submit(function(e) {
                showAnimated();
                e.preventDefault();
                if ($('#{{$IdForm}}').parsley().isValid) {
                    $('#{{$IdForm}} :input').prop("disabled", false);
                    $(this).attr('disabled', 'disabled');
                    e.stopPropagation();
                    $.ajax({
                        type: {{$IdForm}}.attr('method'),
                        url: {{$IdForm}}.attr('action'),
                        enctype: 'multipart/form-data',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(data) {
                            hideAnimated();
                            
                            if(typeof {{$IdForm}}.attr('data-load')!=='undefined'){
                                if ({{$IdForm}}.attr('data-load')==='true') {
                                    $.ajax({
                                        url:"{{url($BasePage.'/load')}}",
                                        success: function(data1) {
                                            $('#{{$IdForm}}data').html(data1);
                                            $('#kartu').focus();
                                            $('#kartu').val('');
                                            showToast(data.response.message, 'success');
                                        },
                                        error:function(xhr) {
                                            window.location = "{{url($UrlForm)}}";
                                        }
                                    });
                                }else{
                                    swal.fire({
                                    title: "Terima Kasih",
                                    text: data.response.message,
                                    icon: data.response.response
                                    }).then(function() {
                                        window.location = "{{url($UrlForm)}}";
                                    });
                                }
                            }else{
                                swal.fire({
                                title: "Terima Kasih",
                                text: data.response.message,
                                icon: data.response.response
                                }).then(function() {
                                    window.location = "{{url($UrlForm)}}";
                                });
                            }
                        },
                        error: function(xhr) {
                            hideAnimated();                        
                            $('#kartu').focus();
                            $('#kartu').val('');
                            showToast(xhr.responseJSON.response.message, 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection