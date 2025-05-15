@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
<!-- quill-1.3.6 -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />


{{-- @include('guru.addData') --}}
<div class="col-sm-12" >
    
    <div class="card">
        <div class="card-body pb-0 " style="padding: 10px 20px;">
            <ul class="nav nav-tabs border-bottom-0" id="myTab" role="tablist">
                @if ($Pgn->users_tipe!="M"&&$Pgn->users_tipe!="G")
                    <li class="nav-item mx-2">
                        <a href="{{route('survei.index')}}" class="nav-link pb-3 text-danger" ><i class=" fas fa-reply"></i> Kembali</a>
                    </li>
                @endif
                <li class="nav-item mx-2" onclick="showPertanyaan()">
                    <a class="nav-link active pb-3" id="surveiq-tab" data-toggle="tab" href="#surveiq" role="tab" aria-controls="surveiq" aria-selected="true"><i class="fas fa-list"></i> Pertanyaan</a>
                </li>
                {{-- <li class="nav-item mx-2" onclick="showAbsen()">
                    <a class="nav-link pb-3" id="absensi-tab" data-toggle="tab" href="#absensi" role="tab" aria-controls="absensi" aria-selected="false"><i class="fas fa-fingerprint"></i> Absensi</a>
                </li>
                <li class="nav-item mx-2" onclick="showLap()">
                    <a class="nav-link pb-3" id="lap-tab" data-toggle="tab" href="#lap" role="tab" aria-controls="lap" aria-selected="false"><i class="fas fa-check"></i> Laporan</a>
                </li> --}}
            </ul>
        </div>
    </div>
    <div class="row">
        @if (!$Agent->isMobile())
            <div class="col-lg-4 col-xxl-3" id="surveiDetailSurvei">
                @include('survei.detailSurvei')
            </div>
        @endif
        
        <div class="col-lg-8 col-xxl-9" >
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="surveiq" role="tabpanel" aria-labelledby="surveiq-tab">
                    @if ($Agent->isMobile())
                        <div class="row">
                            <div class="col-lg-4 col-xxl-3" id="surveiDetailSurvei">
                                @include('survei.detailSurvei')
                            </div>
                    @endif
                    @if ($Agent->isMobile())
                            <div class="col-lg-8 col-xxl-9" >

                    @endif
                    <div class="w-100" id="surveiDetailSurveiq">
                        {!!$Surveiq!!}
                    </div>
                    
                    @if ($Agent->isMobile())
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="absensi" role="tabpanel" aria-labelledby="absensi-tab">
                    <div class="row" id="absenProfile">
                    </div>
                </div>
                <div class="tab-pane fade" id="lap" role="tabpanel" aria-labelledby="lap-tab">
                    <div class="row" id="lapProfile">
                        
                    </div>
                    
                </div>
                
               
            </div>
        </div>
    </div>
</div>
@include('survei.modalEditSurvei', ['countModalBody' => '2', 'countModalFooter' => '2'])

@include('layouts.modalChangeImg')
@include('layouts.modalViewLabel')
@include('layouts.modalViewPdf')
@include('layouts.modalViewImg')
@include('includes.anotherscript')
@include('includes.ajaxinsertTV')
<script>
    
    function showLap(){
        $('#surveiDetailSurveiq').html('');
        
        $('#absenProfile').html('');
        @if ($Agent->isMobile())
            $('#surveiDetailSurvei').html('');
        @endif
        $.ajax({
            url:"{{route('lap.profil', [$Survei->survei_id, 'M'])}}",
            data:{
                jns:'loadProfil'
            },
            success: function(data1) {
                $('#lapProfile').append(data1);
            },
            error:function(xhr) {
                console.log(xhr);
                // window.location.reload();
            }
        });
    }
    function showPertanyaan(){
        $('#lapProfile').html('');
        $('#absenProfile').html('');
        $.ajax({
            url:"{{route('surveiq.detailSurvei', [$Survei->survei_id])}}",
            success: function(data1) {
                $('#surveiDetailSurveiq').html(data1);
            },
            error:function(xhr) {
                window.location.reload();
            }
        });
        @if ($Agent->isMobile())
            $.ajax({
                url:"{{route('survei.detail', [$Survei->survei_id])}}",
                success: function(data1) {
                    $('#surveiDetailSurvei').html(data1);
                },
                error:function(xhr) {
                    window.location.reload();
                }
            });
        @endif
    }
    function showAbsen(){
        $('#surveiDetailSurveiq').html('');
        $('#lapProfile').html('');
        $.ajax({
            url:"{{route('absen.profil', [$Survei->survei_id])}}",
            success: function(data1) {
                $('#absenProfile').html(data1);
            },
            error:function(xhr) {
                window.location.reload();
            }
        });
    }
</script>
@endsection