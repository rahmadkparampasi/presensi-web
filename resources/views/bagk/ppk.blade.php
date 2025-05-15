<div class="col-12" >
    <div class="card">
        <div class="card-header">
            <h6>Data {{$Bagk->bag_nm}}</h6>
        </div>
        <div class="card-body" id="{{$IdForm}}data">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="absensik-tab" data-toggle="tab" href="#absensik" role="tab" aria-controls="absensik" aria-selected="true">Absensi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="showPegawai('bagkPegawaiKooTab')" id="pegawailist-tab" data-toggle="tab" href="#pegawailist" role="tab" aria-controls="pegawailist" aria-selected="true">Pegawai</a>
                </li>
                <li class="nav-item" onclick="showLaporan('bagkLaporanKooTab')">
                    <a class="nav-link" id="laporansatker-tab" data-toggle="tab" href="#laporansatker" role="tab" aria-controls="laporansatker" aria-selected="false">Laporan</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="absensik" role="tabpanel" aria-labelledby="absensik-tab">
                    <div class="row">
                        <div class="col-12">
                            {{-- <button class='btn btn-success m-1' style="float: right;" data-toggle="modal" data-target="#modalFilterAbsen" onclick="cActForm('modalFilterAbsenForm', '{{route('absen.filter')}}'); resetForm('modalFilterAbsenForm'); showFilterKesis()"><i class="fa fa-sliders-h"></i></button> --}}
                        </div>
                        <div id="bagkAbsenPpkTab" class="col-12">
                            <br/>
                            <br/>
                            @include('absen.data')
                        </div>
                    </div>
                    <script>
                        function showAbsenPpk(){
                            $.ajax({
                                url:"{{route('bagk.loadAbsenPpk', [$Bagk->bagk_id])}}",
                                success: function(data1) {
                                    $('#bagkAbsenPpkTab').html('<br/><br/>'+data1);
                                },
                                error:function(xhr) {
                                    // window.location.reload();
                                }
                            });
                        }
                    </script>

                </div>
                <div class="tab-pane fade" id="laporansatker" role="tabpanel" aria-labelledby="laporansatker-tab">
                
                    <div id="bagkLaporanKooTab" class="w-100">
                        
                    </div>
                    <script>
                        function showLaporan(div = ''){
                            $.ajax({
                                url:"{{route('lap.lapKoo', [$Bagk->bagk_id])}}",
                                success: function(data1) {
                                    $('#'+div).html('<br/><br/>'+data1);
                                },
                                error:function(xhr) {
                                    // window.location.reload();
                                }
                            });
                        }
                    </script>
                    
                </div>
                <div class="tab-pane fade" id="pegawailist" role="tabpanel" aria-labelledby="pegawailist-tab">
                    <div id="bagkPegawaiKooTab" class="w-100">
                        
                    </div>
                    <script>
                        function showPegawai(div = ''){
                            $.ajax({
                                url:"{{route('sisp.dataKoo', [$Bagk->bagk_id])}}",
                                success: function(data1) {
                                    $('#'+div).html('<br/><br/>'+data1);
                                },
                                error:function(xhr) {
                                    // window.location.reload();
                                }
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@include('bagk.modalFilterAbsen', ['countModalBody' => 'bagk', 'countModalFooter' => 'bagk'])
@include('lap.modalNilai', ['countModalBody' => 'lapKoo', 'countModalFooter' => 'lapKoo'])
<script>
    $(function() {
        $(document).ready(function() {
            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{'header': [1,2,3,4,5,6,false]}],
                [{'list': 'ordered'}, {'list':'bullet'}],
                [{'script': 'sub'}, {'script':'supper'}],
                [{'indent': '-1'}, {'indent':'+1'}],
                [{'direction': 'rtl'}],
                ['link'],
                [{'color': []}, {'background':[]}],
                [{'font': []}],
                [{'align': []}],
            ];    
            const lap_ket = new Quill('#lap_kete', {
                modules:{
                    toolbar: toolbarOptions,
                },
                theme:'snow'
            });
            lap_ket.on('text-change', function(delta, oldDelta, source) {
                var html = lap_ket.root.innerHTML;
                $('#lap_ket').val( html )
            });
        });
    });
    function disableQuill(){
        const sisp_conft = new Quill('#lap_kete', {
        }).enable(false);
    }
    
</script>
@include('layouts.modalViewPdf')
@include('layouts.modalViewImg')
@include('layouts.modalViewLabel')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>