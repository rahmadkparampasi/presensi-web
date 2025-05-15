<table id="{{$IdForm}}dT" class="table table-striped" >
    <thead>
        <tr>
            <th>No</th>
            <th class="text-wrap">Tiket</th>
            <th class="text-wrap">Nama Lengkap</th>
            <th class="text-wrap">Tanggal Mulai</th>
            <th class="text-wrap">Tanggal Selesai</th>
            @if ($Pgn->users_tipe=="A")
                <th class="text-wrap">Status</th>
            @endif
            <th class="text-wrap">Keterangan Izin</th>
            <th class="text-wrap">Surat Izin</th>
            <th class="text-wrap">Keterangan Administrator</th>
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
            responsive:true,
            serverSide:true,
            searching:false,
            ordering:true,
            ajax: {
                url:"{{$url}}",
            },
            columns: [
                { data: 'rownum', name: 'rownum' },
                { data: 'dataIDSts', name: 'dataIDSts' },
                { data: 'dataNm', name: 'dataNm' },
                { data: 'dataTglm', name: 'dataTglm' },
                { data: 'dataTgls', name: 'dataTgls' },
                @if ($Pgn->users_tipe=="A")
                    { data: 'aksiStatus', name: 'aksiStatus' },
                @endif
                { data: 'sispi_ket', name: 'sispi_ket' },
                { data: 'aksiFile', name: 'aksiFile' },
                { data: 'sispi_ketstj', name: 'sispi_ketstj' },
                { data: 'aksiHapus', name: 'aksiHapus' },
            ],
            
        })
     });
</script>