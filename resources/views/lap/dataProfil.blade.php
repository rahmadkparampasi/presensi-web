<table id="{{$IdForm}}dT" class="table table-striped" >
    <thead>
        <tr>
            <th>No</th>
            <th class="text-wrap">Bulan & Tahun</th>
            <th class="text-wrap">Nilai</th>
            <th class="text-wrap">Keterangan</th>
            <th class="text-wrap">Berkas</th>
            <th class="text-wrap">Aksi</th>
        </tr>
    </thead>
</table>
<script>
    $(document).ready( function () {
        $('#{{$IdForm}}dT').DataTable({
            // dom: 'Bfrtip',
            // buttons: [
            //     'copy', 'excel', 'pdf', 'print'
            // ],
            // pageLength : 5,
            processing:true,
            pagination:true,
            responsive:false,
            serverSide:true,
            searching:false,
            ordering:true,
            scrollX: true,
            ajax: {
                url:"{{route('lap.profil', [$lap_sisp])}}",
                data:{
                    jns:'loadDataProfil'
                },
            },
            columns: [
                { data: 'rownum', name: 'rownum' },
                { data: 'dataBln', name: 'dataBln' },
                { data: 'aksiStatus', name: 'aksiStatus' },
                { data: 'aksiKomen', name: 'aksiKomen' },
                { data: 'aksiFile', name: 'aksiFile' },
                { data: 'aksiHapus', name: 'aksiHapus' },
            ],
            
        })
     });
</script>