
<table id="{{$IdForm}}dT" class=" display table align-items-centertable-striped table-hover w-100" >
    <thead>
        <tr>
            <th>No</th>
            <th class="text-wrap">Longitude</th>
            <th class="text-wrap">Latitude</th>
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
            ajax: "{{route('setlok.index')}}",
            columns: [
                { data: 'rownum', name: 'rownum' },
                { data: 'setlok_long', name: 'setlok_long' },
                { data: 'setlok_lat', name: 'setlok_lat' },
                { data: 'aksiEdit', name: 'aksiEdit' },
            ]
        });
     });
</script>