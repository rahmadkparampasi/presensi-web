<div class="w-100 list-inline d-flex align-items-center justify-content-end border-1 border-secondary">
    <ul class="nav nav-tabs nav-pills border border-secondary rounded-pill list-inline-item" style="width: max-content" id="myTabAsen" role="tablist">
        
        <li class="nav-item" onclick="showProfilCal()">
            <a class="nav-link pb-2" style="border-top-left-radius: 50rem !important; border-bottom-left-radius: 50rem !important" id="calendarAbsen-tab" data-toggle="tab" href="#calendarAbsen" role="tab" aria-controls="calendarAbsen" aria-selected="true"><i class="fas fa-calendar"></i></a>
        </li>
        <li class="nav-item" onclick="showProfilList()">
            <a class="nav-link pb-2 active" style="border-top-right-radius: 50rem !important; border-bottom-right-radius: 50rem !important" id="listAbsen-tab" data-toggle="tab" href="#listAbsen" role="tab" aria-controls="listAbsen" aria-selected="false"><i class="fas fa-list"></i> </a>
        </li>
    </ul>
</div>
<div class="tab-content mt-5 mb-2" id="myTabAbsenContent">
    
    <div class="tab-pane fade " id="calendarAbsen" role="tabpanel" aria-labelledby="calendarAbsen-tab">
        @include('absen.calProfil')
    </div>
    <div class="tab-pane fade show active" id="listAbsen" role="tabpanel" aria-labelledby="listAbsen-tab">
        @include('absen.listProfil')
        
    </div>
   
</div>
@if (isset($search))
    <div class="d-flex align-items-center justify-content-center pt-3">
        <button type="button" onclick="showAbsen(); " class="btn btn-danger mx-1"><i class="fa fa-ban"></i> TUTUP HASIL PENCARIAN</button>
        <button type="button" onclick="callBlank('{{route('excel.sisp', [$absen_sisp, $month, $year])}}')" class="btn btn-success mx-1"><i class="fa fa-file-excel"></i> EXCEL</button>
        <button type="button" onclick="callBlank('{{route('pdf.sisp', [$absen_sisp, $month, $year])}}')" class="btn btn-info mx-1"><i class="fa fa-file-pdf"></i> PDF</button>
    </div>
@else
    <div class="d-flex align-items-center justify-content-center pt-3">
        <button type="button" onclick="callBlank('{{route('excel.sisp', [$absen_sisp, $month, $year])}}')" class="btn btn-success mx-1"><i class="fa fa-file-excel"></i> EXCEL</button>
        <button type="button" onclick="callBlank('{{route('pdf.sisp', [$absen_sisp, $month, $year])}}')" class="btn btn-info mx-1"><i class="fa fa-file-pdf"></i> PDF</button>
    </div>
@endif
<script>
    function showProfilCal(){
        $('#listAbsen').html('');
        $.ajax({
            url:"{{route('absen.profilCal', [$absen_sisp, $month, $year])}}",
            success: function(data1) {
                $('#calendarAbsen').html(data1);
            },
            error:function(xhr) {
                window.location.reload();
            }
        });
    }
    function showProfilList(){
        $('#calendarAbsen').html('');
        $.ajax({
            url:"{{route('absen.profilList', [$absen_sisp, $month, $year])}}",
            success: function(data1) {
                $('#listAbsen').html(data1);
            },
            error:function(xhr) {
                window.location.reload();
            }
        });
    }
</script>