@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
    <div class="col-12" >
        @if ($Agent->isMobile())
            <br/>
            <br/>
        @endif
        <h5>Rekapan Data</h5>
        <hr>
    </div>
    {{-- <div class="col-12">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="text-c-yellow">{{(int)$Siswa['countAll']+(int)$Guru['countAll']}}</h4>
                                <h6 class="text-muted m-b-0">Pegawai</h6>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fa fa-graduation-cap f-28"></i>
                                <i class="fa fa-user-tie f-28"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="text-c-yellow">{{$Siswa['countAll']}}</h4>
                                <h6 class="text-muted m-b-0">Siswa</h6>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fa fa-graduation-cap f-28"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="text-c-red">{{$Guru['countAll']}}</h4>
                                <h6 class="text-muted m-b-0">Pegawai</h6>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fa fa-user-tie f-28"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="col-12" >
        <h5>Rekapan Siswa</h5>
        <hr>
    </div>
    <div class="col-12">
        
        <div class="row">
            
            <div class="col-4 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h4 class="text-c-green">{{$Siswa['count10']}}</h4>
                                <h6 class="text-muted m-b-0">Tingkat 10</h6>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h4 class="text-c-red">{{$Siswa['count11']}}</h4>
                                <h6 class="text-muted m-b-0">Tingkat 11</h6>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h4 class="text-c-blue">{{$Siswa['count12']}}</h4>
                                <h6 class="text-muted m-b-0">Tingkat 12</h6>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h6>Data Siswa Terlambat 10 Teratas</h6>
                    </div>
                    <div class="card-body">
                        <table id="{{$IdForm}}dTSiswa" class="table table-striped" >
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th class="text-wrap">NIS/NISN</th>
                                    <th class="text-wrap">Nama Lengkap</th>
                                    <th class="text-wrap">Kelas</th>
                                    <th class="text-wrap">TTL</th>
                                    <th class="text-wrap">Jenis Kelamin</th>
                                </tr>
                            </thead>
                            
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h6>Data Siswa Berdasarkan Jenis Kelamin</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartSiswaJk" class="w-100 " ></canvas>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                homeChart()
            }, 100);
            $('#{{$IdForm}}dTSiswa').DataTable({
                processing:true,
                pagination:false,
                bPaginate:false,
                responsive:true,
                serverSide:true,
                searching:false,
                ordering:false,
                lengthChange: false,
                ajax: "{{route('home')}}",
                columns: [
                    { data: 'rownum', name: 'rownum' },
                    { data: 'sisp_idsp', name: 'sisp_idsp' },
                    { data: 'sisp_nm', name: 'sisp_nm' },
                    { data: 'bag_nm', name: 'bag_nm' },
                    { data: 'dataTTL', name: 'dataTTL' },
                    { data: 'sisp_jkAltT', name: 'sisp_jkAltT' },
                ],
                pageLength: 5,
            });
        });
        function homeChart() {
            $(function() {
                const dataSiswaJk = {
                    labels: [
                        'Laki-Laki',
                        'Perempuan',
                    ],
                    datasets: [{
                        label: 'Data Siswa Berdasarkan Jenis Kelamin',
                        data: [{{$Siswa['countJKL']}}, {{$Siswa['countJKP']}}],
                        backgroundColor: [
                        'rgb(255, 66, 66)',
                        'rgb(71, 171, 216)',
                        ],
                        hoverOffset: 4
                    }]
                };
                new Chart('chartSiswaJk', {
                    type: 'doughnut', 
                    data: dataSiswaJk, 
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            labels: {
                                render: (args) => {
                                    return " "+args.label+": "+args.value+" "
                                },
                                fontSize: 14,
                                fontColor: '#2b2b2b',
                                position: 'default',
                                overlap: true,
                                outsidePadding: 4,
                            }
                        }
                    }
                });
            });
        }
    </script> --}}
    <!-- apexcharts-bundle -->
    @include('includes.anotherscript')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>
    <script src="/vendors/script/apexcharts-bundle/dist/apexcharts.min.js"></script>
@endsection
