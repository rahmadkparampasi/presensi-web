<h5 class="text-center">{{$Now}}</h5>

<div class="d-flex justify-content-start">
    @if (isset($filtert_bagk))
        @if ($filtert_bagk=='S')
            <button class='btn btn-primary my-1 btn-sm mr-2' data-toggle="modal" data-target="#modalFilterAbsen" onclick="cActForm('modalFilterAbsenForm', '{{route('absen.filter')}}'); resetForm('modalFilterAbsenForm'); $('#filtert_div').val('bagkAbsenSatkerTab'); $('#filtert_bagk').val('{{$filtert_bagk}}');"><i class="fa fa-filter"></i> Pencarian Spesifik</button>
        @else
            <button class='btn btn-primary my-1 btn-sm mr-2' data-toggle="modal" data-target="#modalFilterAbsen" onclick="cActForm('modalFilterAbsenForm', '{{route('absen.filter')}}'); resetForm('modalFilterAbsenForm'); $('#filtert_div').val('bagkAbsenPpkTab'); $('#filtert_bagk').val('{{$filtert_bagk}}');"><i class="fa fa-filter"></i> Pencarian Spesifik</button>
        @endif
    @else
        <button class="btn btn-primary my-1 btn-sm mr-2" onclick="showForm('{{$IdForm}}filterForm', 'block'); cActForm('{{$IdForm}}filterForm', '{{route('absen.filter')}}'); resetForm('{{$IdForm}}filterForm')"><i class="fa fa-filter"></i> Pencarian Spesifik</button>
    @endif
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle btn-sm my-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i> Ekspor Data</button>
        <div class="dropdown-menu">
            <a class="dropdown-item" target="_blank" href="{{route('absen.pdfF', $paramCtk)}}"><i class="fa fa-file-pdf"></i> PDF Detail</a>
            <a class="dropdown-item" target="_blank" href="{{route('absen.excelF', $paramCtk)}}"><i class="fa fa-file-excel"></i> Excel Detail</a>
        </div>
    </div>
</div>
<table id="{{$IdForm}}dT" class="table table-striped" >
    <thead>
        <tr>
            <th>No</th>
            
            <th class="text-wrap">Nama Lengkap</th>
            <th class="text-wrap">PPK</th>
            <th class="text-wrap">Tanggal</th>
            <th class="text-wrap">Masuk</th>
            <th class="text-wrap">Keluar</th>
        </tr>
    </thead>
</table>
<br/>
<div class="d-flex justify-content-center">
    {{-- <button class="btn btn-info" onclick="callBlank('{{route('siswaCtk.index', ['all'])}}')"><i class="fa fa-print"></i> CETAK</button> --}}
</div>
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
            searching:true,
            ordering:true,
            scrollX: true,
            @if ($Agent->isMobile())
                pagingType: 'numbers_no_ellipses',
            @endif
            ajax: {
                url: "{{$url}}",
                @if (isset($dataAjaxDT))
                    {!!$dataAjaxDT!!}
                @endif
            },
            columns: [
                { data: 'rownum', name: 'rownum' },
                
                { data: 'sisp_nm', name: 'sisp_nm' },
                { data: 'bag_nm', name: 'bag_nm' },
                { data: 'absen_tglAltT', name: 'absen_tglAltT' },
                { data: 'dataMasuk', name: 'dataMasuk' },
                { data: 'dataKeluar', name: 'dataKeluar' },
            ],
            
            
        })
     });
</script>