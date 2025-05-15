<div class="col-12" >
    <div class="card">
        <div class="card-header">
            
            <div class="card-title mb-0">Data Survei</div>
        </div>
        <div class="card-body">
            <table id="{{$IdForm}}dT" class=" display table align-items-centertable-striped table-hover w-100" >
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-wrap">Tahun</th>
                        <th class="text-wrap">Penilai</th>
                        <th class="text-wrap">Lihat</th>
                    </tr>
                </thead>
            </table>
            <script>
                $(document).ready( function () {
                    $('#{{$IdForm}}dT').DataTable({
                        processing:true,
                        pagination:true,
                        responsive:true,
                        serverSide:true,
                        searching:true,
                        ordering:true,
                        ajax: {
                            url:"{{route('surveis.profil', [$sisp])}}",
                            data:{
                                jns:'loadDataProfil'
                            },
                        },
                        columns: [
                            { data: 'rownum', name: 'rownum' },
                            { data: 'dataThn', name: 'dataThn' },
                            { data: 'sisp_nm', name: 'sisp_nm' },
                            { data: 'aksiLihat', name: 'aksiLihat' },
                        ]
                    });
                 });
            </script>
        </div>
    </div>
</div>
<script>
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
</script>