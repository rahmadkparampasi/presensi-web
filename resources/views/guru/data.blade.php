<table id="{{$IdForm}}dT" class="table table-striped" >
    <thead>
        <tr>
            <th>No</th>
            <th class="text-wrap">Nama Lengkap</th>
            <th class="text-wrap">SATKET/PPK</th>
            
            <th class="text-wrap">TTL</th>
            <th class="text-wrap">Jenis Kelamin</th>
            <th class="text-wrap">Alamat</th>
            <th class="text-wrap">Foto</th>
            <th class="text-wrap">Status</th>
            <th class="text-wrap">Hapus</th>
        </tr>
    </thead>
</table>
<br/>
@if (isset($dataAjaxDT))
    <div class="d-flex align-items-center justify-content-center">
        <button type="button" onclick="callBlank('{{route('guruCtk.index', $paramCtk)}}')" class="btn btn-info mx-1"><i class="fa fa-print"></i> CETAK</button>
        <button type="button" onclick="closeForm('<?= $IdForm ?>filterForm', '<?= $IdForm ?>filterForm', '{{route('sisp.filter')}}'); cancelSearch(); " class="btn btn-danger mx-1"><i class="fa fa-ban"></i> TUTUP HASIL PENCARIAN</button>
    </div>
    <script>
        function cancelSearch() {
            $.ajax({
                @if ($act=='1')
                    url:"{{route('sisp.load')}}",
                @elseif ($act=='0')
                    url:"{{route('sisp.load', ['0'])}}",
                @endif
                success: function(data1) {
                    $('#{{$IdForm}}data').html(data1);
                },
                error:function(xhr) {
                    window.location = "{{url($UrlForm)}}";
                }
            });
        }
    </script>
@else
    <div class="d-flex justify-content-center">
        <button class="btn btn-info" onclick="callBlank('{{route('guruCtk.index', ['all'])}}')"><i class="fa fa-print"></i> CETAK</button>
    </div>
@endif
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
            ajax: {
                @if ($act=='1')
                    url: "{{route('sisp.index')}}",
                @elseif ($act=='0')
                    url: "{{route('sisp.na', ['0'])}}",
                @endif
                @if (isset($dataAjaxDT))
                    {!!$dataAjaxDT!!}
                @endif
            },
            columns: [
                { data: 'rownum', name: 'rownum' },
                { data: 'aksiDetail', name: 'aksiDetail' },
                { data: 'satker', name: 'satker' },
                { data: 'dataTTL', name: 'dataTTL' },
                { data: 'sisp_jkAltT', name: 'sisp_jkAltT' },
                { data: 'dataAlt', name: 'dataAlt' },
                { data: 'aksiFoto', name: 'aksiFoto' },
                { data: 'aksiStatus', name: 'aksiStatus' },
                { data: 'aksiHapus', name: 'aksiHapus' },
            ],
            columnDefs: [
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets: 3
                }
            ]
            
        })
     });
</script>