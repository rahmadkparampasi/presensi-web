<div class="accordion" id="accordionExample">
    @php
        $no = 0;
    @endphp
    @foreach ($Sisp as $tk)
        @php
            $no++;
        @endphp
        <div class="card my-1">
            <div class="card-header py-1" id="heading{{$no}}" onclick="loadSurveis('{{$tk->sisp_id}}', 'bodySurvei{{$no}}')">
                <div class="row">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse{{$no}}" aria-expanded="true" aria-controls="collapse{{$no}}">
                            {{ $tk->sisp_nm }}
                        </button>
                    </h2>
                    
                </div>
            </div>

            <div id="collapse{{$no}}" class="collapse" aria-labelledby="heading{{$no}}" data-parent="#accordionExample">
                <div class="row">
                    <div class="col-12 m-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle btn-sm my-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-clipboard-list"></i> Tambah Survei</button>
                            <div class="dropdown-menu" >
                                @foreach ($Survei as $tks)
                                    <button class="dropdown-item" data-toggle="modal" data-target="#modalSurvei" onclick="$('#modalSurveiTitle').html('Survei Pegawai: {{$tk->sisp_nm}}'); loadSurveiForm('{{$tks->survei_id}}', '{{$tk->sisp_id}}'); $('#modalSurveiForm').attr('data-div', 'bagkPegawaiKooTab'); $('#modalSurveiForm').attr('data-urlload', '{{route('sisp.dataKoo', [$Bagk->bagk_id])}}'); $('#modalSurveiForm').attr('action', '{{route('surveis.insert')}}');">{{$tks->survei_thn}}</button>
                                @endforeach
                                {{-- <a class="dropdown-item" target="_blank" href="{{route('absen.excelD', $paramCtk)}}"><i class="fa fa-file-excel"></i> Excel Detail</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="bodySurvei{{$no}}">
                    
                </div>
            </div>
        </div>
    @endforeach
</div>
<script>
    function loadSurveiForm(id = '', sisp = ''){
        myUrl = "{{route('survei.loadForm')}}"+"/"+id+"/"+sisp;
        $.ajax({
            url:myUrl,
            success: function(data1) {
                $('#detailSurveiForm').html(data1);
            },
            error:function(xhr) {
                // window.location.reload();
            }
        });
    }
    function loadSurveiFormA(id = '', sisp = ''){
        myUrl = "{{route('survei.loadFormA')}}"+"/"+id+"/"+sisp;
        $.ajax({
            url:myUrl,
            success: function(data1) {
                $('#detailSurveiForm').html(data1);
            },
            error:function(xhr) {
                // window.location.reload();
            }
        });
    }
    function loadSurveis(sisp = '', div = ''){
        myUrl = "{{route('surveis.detail')}}"+"/"+sisp;
        $.ajax({
            url:myUrl,
            success: function(data1) {
                $('#'+div).html(data1);
            },
            error:function(xhr) {
                // window.location.reload();
            }
        });
    }
</script>