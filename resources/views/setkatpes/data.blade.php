
<table id="{{$IdForm}}dT" class=" display table align-items-centertable-striped table-hover w-100" >
    <thead>
        <tr>
            <th>No</th>
            <th class="text-wrap">Nama Kategori Peserta</th>
            <th class="text-wrap">Jam Kerja</th>
            <th class="text-wrap">Sistem Shift</th>
            <th class="text-wrap">Aksi</th>
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
            ajax: "{{route('setkatpes.index')}}",
            columns: [
                { data: 'rownum', name: 'rownum' },
                { data: 'setkatpes_nm', name: 'setkatpes_nm' },
                { data: 'aksiJamKerja', name: 'aksiJamKerja' },
                { data: 'aksiShift', name: 'aksiShift' },
                { data: 'aksiEdit', name: 'aksiEdit' },
            ]
        });
     });
</script>