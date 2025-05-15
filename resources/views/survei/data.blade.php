
<table id="{{$IdForm}}dT" class=" display table align-items-centertable-striped table-hover w-100" >
    <thead>
        <tr>
            <th>No</th>
            <th class="text-wrap">Tahun</th>
            <th class="text-wrap">Kuis</th>
            <th class="text-wrap">Detail</th>
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
            ajax: "{{$url}}",
            columns: [
                { data: 'rownum', name: 'rownum' },
                { data: 'dataThn', name: 'dataThn' },
                { data: 'survei_kuisAltT', name: 'survei_kuisAltT' },
                { data: 'aksiDetail', name: 'aksiDetail' },
                { data: 'aksiStatus', name: 'aksiStatus' },
                { data: 'aksiHapus', name: 'aksiHapus' },
            ]
        });
     });
</script>