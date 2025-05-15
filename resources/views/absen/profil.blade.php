<div class="col-12" >
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-8 col-lg-8 my-auto">
                    <h6>Data Absensi</h6>
                </div>
                {{-- triger get location bermasalah --}}
                <div class="col-4 col-lg-4">
                    <button class='btn btn-primary' onclick="getLocation(); cActForm('absenAddDataProfilModalForm', '{{route('absen.insertM')}}'); showFormNS('divTipe', 'block'); showCam();" data-toggle="modal" data-target="#absenAddDataProfilModal" onclick=""><i class="fa fa-fingerprint" ></i> TAMBAH</button>
                    <button class='btn btn-success' style="float: right;" onclick="showForm('{{$IdForm}}filterForm', 'block'); cActForm('{{$IdForm}}filterForm', '{{route('absen.profilFilter', [$absen_sisp])}}'); resetForm('{{$IdForm}}filterForm')"><i class="fa fa-sliders-h"></i></button>
                </div>
            </div>
        </div>
        <div class="card-body" id="{{$IdForm}}filterData">
            @include('absen.filterProfil')
        </div>
        <div class="card-body" id="{{$IdForm}}data">
            @include('absen.dataProfil')
            
            
        </div>
    </div>
</div>
<script>
    function getLocation() {
        // alert('Bisa di get');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
            // alert(navigator.geolocation.getCurrentPosition(showPosition));
            
        } else { 
            alert("Geolocation is not supported by this browser.");
        }
    }
    function showPosition(position) {
        // alert(position.coords.latitude, position.coords.longitude);
        $('#lat').val(position.coords.latitude.toFixed(6));
        $('#long').val(position.coords.longitude.toFixed(6));
    }
</script>
@include('absen.modalAddProfil', ['countModalBody' => 'AddAbsen', 'countModalFooter' => 'AddAbsen'])
@include('absen.modalViewDetail', ['countModalBody' => 'Absen', 'countModalFooter' => 'Absen'])


