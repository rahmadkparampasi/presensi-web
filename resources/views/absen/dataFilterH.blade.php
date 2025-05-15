<h5 class="text-center">{{$Label['labelTanggal']}}</h5>
{{-- <h5 class="text-center">{{$Label['labelKategori']}}</h5> --}}
<h5 class="text-center">{{$Label['labelBagian']}}</h5>
<div class="d-flex justify-content-start">
    @if ($filtert_bagk!='')
        @if ($filtert_bagk=='S')
            <button class='btn btn-primary m-1 btn-sm' data-toggle="modal" data-target="#modalFilterAbsen" onclick="cActForm('modalFilterAbsenForm', '{{route('absen.filter')}}'); resetForm('modalFilterAbsenForm');"><i class="fa fa-filter"></i> Pencarian Spesifik</button>
        @else
            <button class='btn btn-primary m-1 btn-sm' data-toggle="modal" data-target="#modalFilterAbsen" onclick="cActForm('modalFilterAbsenForm', '{{route('absen.filter')}}'); resetForm('modalFilterAbsenForm');"><i class="fa fa-filter"></i> Pencarian Spesifik</button>
        @endif
    @else
        <button class="btn btn-primary m-1 btn-sm" onclick="showForm('{{$IdForm}}filterForm', 'block'); cActForm('{{$IdForm}}filterForm', '{{route('absen.filter')}}'); resetForm('{{$IdForm}}filterForm')"><i class="fa fa-filter"></i> Pencarian Spesifik</button>
    @endif
    {{-- <a href="{{route('absen.print', $paramCtk)}}" class='btn btn-info m-1 btn-sm' ><i class="fa fa-print"></i> Cetak</a> --}}

    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle btn-sm my-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i> Ekspor Data</button>
        <div class="dropdown-menu">
            <a class="dropdown-item" target="_blank" href="{{route('absen.pdfF', $paramCtk)}}"><i class="fa fa-file-pdf"></i> PDF Detail</a>
            <a class="dropdown-item" target="_blank" href="{{route('absen.excelF', $paramCtk)}}"><i class="fa fa-file-excel"></i> Excel Detail</a>
        </div>
    </div>

    @if ($filtert_bagk!='')
        @if ($filtert_bagk=='S')
            <button type="button" onclick="showAbsenSatker(); " class="btn btn-sm btn-danger m-1 "><i class="fa fa-ban"></i> TUTUP HASIL PENCARIAN</button>
        @else
            <button type="button" onclick="showAbsenPpk(); " class="btn btn-sm btn-danger m-1 "><i class="fa fa-ban"></i> TUTUP HASIL PENCARIAN</button>
        @endif
    @else
        <button type="button" onclick="closeForm('<?= $IdForm ?>filterForm', '<?= $IdForm ?>filterForm', '{{route('absen.filter')}}'); cancelSearch(); " class="btn btn-sm btn-danger m-1 "><i class="fa fa-ban"></i> TUTUP HASIL PENCARIAN</button>
        
    @endif
</div>

<table id="{{$IdForm}}dT" class="table table-striped" >
    <thead>
        <tr>
            <th>No</th>
            <th class="text-wrap">NIK / NIP</th>
            <th class="text-wrap">Nama Lengkap</th>
            <th class="text-wrap">Tanggal</th>
            <th class="text-wrap">Masuk</th>
            <th class="text-wrap">Keluar</th>
        </tr>
    </thead>
</table>
<script>
    function cancelSearch() {
        $.ajax({
            url:"{{route('absen.load')}}",
            
            success: function(data1) {
                $('#{{$IdForm}}data').html(data1);
            },
            error:function(xhr) {
                // window.location = "{{url($UrlForm)}}";
            }
        });
    }
</script>
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
            searching:true,
            ordering:true,
            ajax: {
                url: "{{route('absen.filterData')}}",
                @if (isset($dataAjaxDT))
                    {!!$dataAjaxDT!!}
                @endif
            },
            columns: [
                { data: 'rownum', name: 'rownum' },
                { data: 'sisp_idsp', name: 'sisp_idsp' },
                { data: 'sisp_nm', name: 'sisp_nm' },
                { data: 'absen_tglAltT', name: 'absen_tglAltT' },
                { data: 'dataMasuk', name: 'dataMasuk' },
                { data: 'dataKeluar', name: 'dataKeluar' },
            ],
            
            columnDefs: [
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets: 3
                },
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets: 4
                },
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets: 5
                },
            ]
        })
     });
</script>