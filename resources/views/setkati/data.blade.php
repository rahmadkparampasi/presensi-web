
<table id="{{$IdForm}}dT" class=" display table align-items-centertable-striped table-hover w-100" >
    <thead>
        <tr>
            <th>No</th>
            <th class="text-wrap">Nama Kategori Izin</th>
            <th class="text-wrap">Kode Kategori Izin</th>
            <th class="text-wrap">Status</th>
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
            ajax: "{{route('setkati.index')}}",
            columns: [
                { data: 'rownum', name: 'rownum' },
                { data: 'setkati_nm', name: 'setkati_nm' },
                { data: 'setkati_kd', name: 'setkati_kd' },
                { data: 'aksiStatus', name: 'aksiStatus' },
                { data: 'aksiEdit', name: 'aksiEdit' },
            ]
        });
     });
</script>