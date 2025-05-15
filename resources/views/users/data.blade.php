
<table id="{{$IdForm}}dT" class=" display table align-items-centertable-striped table-hover w-100" >
    <thead>
        <tr>
            <th>No</th>
            <th class="text-wrap">Nama Pengguna</th>
            <th class="text-wrap">Username</th>
            <th class="text-wrap">Kategori</th>
            <th class="text-wrap">Kata Sandi</th>
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
                { data: 'users_nm', name: 'users_nm' },
                { data: 'username', name: 'username' },
                { data: 'users_tipeAltT', name: 'users_tipeAltT' },
                { data: 'aksiSandi', name: 'aksiSandi' },
                { data: 'aksiStatus', name: 'aksiStatus' },
                { data: 'aksiEdit', name: 'aksiEdit' },
            ]
        });
     });
</script>