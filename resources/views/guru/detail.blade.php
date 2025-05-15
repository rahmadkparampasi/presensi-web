@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
<!-- quill-1.3.6 -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

{{-- @include('guru.addData') --}}
<div class="col-sm-12" >
    
    <div class="card">
        <div class="card-body pb-0 " style="padding: 10px 20px;">
            <ul class="nav nav-tabs border-bottom-0" id="myTab" role="tablist">
                @if ($Pgn->users_tipe=="A")
                    <li class="nav-item mx-2">
                        @if ($Guru->sisp_act=='1')
                            <a href="{{route('sisp.index')}}" class="nav-link pb-3 text-danger" ><i class=" fas fa-reply"></i> Kembali</a>
                        @elseif ($Guru->sisp_act=='2')
                            <a href="{{route('sisp.alumni')}}" class="nav-link pb-3 text-danger" ><i class=" fas fa-reply"></i> Kembali</a>
                        @elseif ($Guru->sisp_act=='0')
                            <a href="{{route('sisp.na')}}" class="nav-link pb-3 text-danger" ><i class=" fas fa-reply"></i> Kembali</a>
                        @endif
                    </li>
                @endif
                <li class="nav-item mx-2" onclick="showProfil()">
                    <a class="nav-link active pb-3" id="profil-tab" data-toggle="tab" href="#profil" role="tab" aria-controls="profil" aria-selected="true"><i class="fas fa-user"></i> Profil</a>
                </li>
                <li class="nav-item mx-2" onclick="showAbsen();">
                    <a class="nav-link pb-3" id="absensi-tab" data-toggle="tab" href="#absensi" role="tab" aria-controls="absensi" aria-selected="false"><i class="fas fa-fingerprint"></i> Absensi</a>
                </li>
                <li class="nav-item mx-2" onclick="showLap()">
                    <a class="nav-link pb-3" id="lap-tab" data-toggle="tab" href="#lap" role="tab" aria-controls="lap" aria-selected="false"><i class="fas fa-check"></i> Laporan</a>
                </li>
                <li class="nav-item mx-2" onclick="showSurveiProf()">
                    <a class="nav-link pb-3" id="survei-tab" data-toggle="tab" href="#survei" role="tab" aria-controls="survei" aria-selected="false"><i class="fas fa-clipboard-check"></i> Survei</a>
                </li>
                @if ($Satker!=null)
                    <li class="nav-item mx-2" onclick="showSatker()">
                        <a class="nav-link pb-3" id="Satker-tab" data-toggle="tab" href="#Satker" role="tab" aria-controls="Satker" aria-selected="false"><i class="fas fa-sitemap"></i> SATKER</a>
                    </li>
                @endif
                @if ($Ppk!=null)
                    <li class="nav-item mx-2" onclick="showPpk()">
                        <a class="nav-link pb-3" id="ppk-tab" data-toggle="tab" href="#ppk" role="tab" aria-controls="ppk" aria-selected="false"><i class="fas fa-sitemap"></i> PPK</a>
                    </li>
                @endif
                
            </ul>
        </div>
    </div>
    <div class="row">
        @if (!$Agent->isMobile())
            <div class="col-lg-4 col-xxl-3" id="guruDetailGuru">
                @include('guru.detailGuru')
            </div>
        @endif
        
        <div class="col-lg-8 col-xxl-9" >
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="profil" role="tabpanel" aria-labelledby="profil-tab">
                    @if ($Agent->isMobile())
                        <div class="row">
                            <div class="col-lg-4 col-xxl-3" id="guruDetailGuru">
                                @include('guru.detailGuru')
                            </div>
                    @endif
                    @if ($Agent->isMobile())
                            <div class="col-lg-8 col-xxl-9" >

                    @endif
                    <div class="card" id="userDetailProfil">
                        {!!$User!!}
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
                <div class="tab-pane fade" id="Satker" role="tabpanel" aria-labelledby="Satker-tab">
                    <div class="row" id="SatkerProfile">
                        
                    </div>
                </div>
                <div class="tab-pane fade" id="ppk" role="tabpanel" aria-labelledby="ppk-tab">
                    <div class="row" id="ppkProfile">
                        
                    </div>
                </div>
                <div class="tab-pane fade" id="survei" role="tabpanel" aria-labelledby="survei-tab">
                    <div class="row" id="surveiProfile">
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@include('guru.modalEditGuru', ['countModalBody' => '2', 'countModalFooter' => '2'])
@include('survei.modalSurvei', ['countModalBody' => '10', 'countModalFooter' => '10'])
@include('guru.modalChangePwd', ['countModalBody' => '11', 'countModalFooter' => '11'])


@include('layouts.modalChangeImg')
@include('layouts.modalViewLabel')
@include('layouts.modalViewPdf')
@include('layouts.modalViewImg')
@include('includes.anotherscript')
@include('includes.ajaxinsertTV')
<script>

    
</script>
<script>
    @if ($Satker!=null)
        function showSatker(){
            $('#userDetailProfil').html('');
            
            $('#absenProfile').html('');
            $('#lapProfile').html('');
            $('#ppkProfile').html('');
            $('#surveiProfile').html('');

            @if ($Agent->isMobile())
                $('#guruDetailGuru').html('');
            @endif
            $.ajax({
                url:"{{route('bagk.satker', [$Satker->bagk_id])}}",
                success: function(data1) {
                    $('#SatkerProfile').append(data1);
                },
                error:function(xhr) {
                    // window.location.reload();
                }
            });
        }
    @endif
    @if ($Ppk!=null)
        function showPpk(){
            $('#userDetailProfil').html('');
            
            $('#absenProfile').html('');
            $('#lapProfile').html('');
            $('#SatkerProfile').html('');
            $('#surveiProfile').html('');
            @if ($Agent->isMobile())
                $('#guruDetailGuru').html('');
            @endif
            $.ajax({
                url:"{{route('bagk.ppk', [$Ppk->bagk_id])}}",
                success: function(data1) {
                    $('#ppkProfile').append(data1);
                },
                error:function(xhr) {
                    // window.location.reload();
                }
            });
        }
    @endif
    function showLap(){
        $('#userDetailProfil').html('');
        $('#ppkProfile').html('');
        $('#SatkerProfile').html('');
        $('#absenProfile').html('');
        $('#surveiProfile').html('');
        @if ($Agent->isMobile())
            $('#guruDetailGuru').html('');
        @endif
        $.ajax({
            url:"{{route('lap.profil', [$Guru->sisp_id, 'M'])}}",
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
    function showProfil(){
        $('#lapProfile').html('');
        $('#absenProfile').html('');
        $('#ppkProfile').html('');
        $('#SatkerProfile').html('');
        $('#surveiProfile').html('');
        $.ajax({
            url:"{{route('user.detailProfil', [$Guru->sisp_id, 'M'])}}",
            success: function(data1) {
                $('#userDetailProfil').html(data1);
            },
            error:function(xhr) {
                window.location.reload();
            }
        });
        @if ($Agent->isMobile())
            $.ajax({
                url:"{{route('sisp.detailSisp', [$Guru->sisp_id])}}",
                success: function(data1) {
                    $('#guruDetailGuru').html(data1);
                },
                error:function(xhr) {
                    window.location.reload();
                }
            });
        @endif
    }
    function showAbsen(){
        $('#userDetailProfil').html('');
        
        $('#lapProfile').html('');
        $('#ppkProfile').html('');
        $('#SatkerProfile').html('');
        $('#surveiProfile').html('');
        $.ajax({
            url:"{{route('absen.profil', [$Guru->sisp_id])}}",
            success: function(data1) {
                $('#absenProfile').html(data1);
            },
            error:function(xhr) {
                window.location.reload();
            }
        });
    }
    function showSurveiProf(){
        $('#userDetailProfil').html('');
        $('#lapProfile').html('');
        $('#ppkProfile').html('');
        $('#SatkerProfile').html('');
        $('#absenProfile').html('');
        $.ajax({
            url:"{{route('surveis.profil', [$Guru->sisp_id])}}",
            success: function(data1) {
                $('#surveiProfile').html(data1);
            },
            error:function(xhr) {
                // window.location.reload();
            }
        });
    }
</script>
@endsection