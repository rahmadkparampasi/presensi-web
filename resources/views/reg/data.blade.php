<table id="{{$IdForm}}dT" class=" display table align-items-centertable-striped table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul Regulasi Atau Informasi</th>
            <th>Berkas</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($Reg as $tk) @php $no++ @endphp 
        
        <tr>
            <td>{{$no}}</td>
            <td>{{stripslashes($tk['reg_jdl'])}}</td>
            <td>
                <button type="button" class="btn btn-info" onclick="changeUrl('{{url('uploads/'.$tk['reg_fl'])}}', '{{$tk['reg_nm']}}');" data-target="#modalViewPdf" data-toggle="modal"><i class="fas fa-eye"></i></button>
                <a href="{{url('uploads/'.$tk['reg_fl'])}}" target="_blank" type="button" class="btn btn-success" ><i class="fas fa-download"></i></a>
            </td>
            <td>{!!$tk['reg_actAltBu']!!}</td>
            <td>

                <button type="button" class="btn btn-danger" onclick="callOtherTWLoad('Menghapus Data Regulasi Atau Informasi','{{url('reg/delete/'.$tk['reg_id'])}}', '{{url('reg/load')}}', '{{$IdForm}}', '{{$IdForm}}data', '{{$IdForm}}card')"><i class="fas fa-trash"></i></button>
                
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function() {
        dTD('table#{{$IdForm}}dT');
    });
</script>