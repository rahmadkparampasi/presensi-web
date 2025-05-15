@extends('layouts.modalAll', ['idModalAll' => 'absenAddDataProfilModal', 'sizeModalAll' => 'modal-lg', 'divLoadModalAll' => 'absenProfile', 'urlLoadModalAll' => route('absen.profil', [$absen_sisp]), 'dataLoadModalAll'=>'true','urlModalAll'=>route('absen.insertM'), 'titleModalAll' => 'ABSENSI'])

@section('contentInputHidden')
    
    
    
@endsection
@section('contentModalBody'.$countModalBody)
    
    <input type="hidden" id="absen_id" name="absen_id" value="">
    <input type="hidden" id="absen_sisp" name="absen_sisp" value="{{$absen_sisp}}" required>
    {{-- <input type="text" id="long" name="long" required>
    <input type="text" id="lat" name="lat" required> --}}

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="long">Koordinat Longitude</label>
        <input type="text" class="form-control" id="long" name="long" placeholder="" required readonly>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="lat">Koordinat Latitude</label>
        <input type="text" class="form-control" id="lat" name="lat" placeholder="" required readonly>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required" id="divTipe">
        <label class="control-label" for="tipe">LOKASI ABSEN</label>
        <select class="form-control border rounded border-dark" id="tipe" name="tipe"  required>
            <option value="" hidden>Pilih Salah Satu</option>
            <option value="WFO">WFO</option>
            <option value="WFH">WFH</option>
            <option value="D">DINAS</option>
            <option value="O">ON-SITE</option>
        </select>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="pic">Foto Absensi</label>
        {{-- <input type="file" accept="image/*" capture="" class="form-control" id="pic" name="pic" placeholder="" required> --}}
        <div class="row">
            <div class="col-12 align-items-center text-center center-block overflow-x-hidden" id="picCam">
                <div id="my_camera" class="w-100"></div>
                <button type="button" class="btn btn-info" onclick="take_snapshot()"><i class="fa fa-camera"></i> AMBIL GAMBAR</button>
                
                <input type="hidden" name="image" class="image-tag">
            </div>
            <div class="col-12 align-items-center text-center center-block overflow-x-hidden" id="picRes">
                <div id="results" class="w-100"></div>
                <br/>
                <button type="button" class="btn btn-info" onclick="showCam()"><i class="fa fa-sync"></i> AMBIL KEMBALI GAMBAR</button>
            </div>
            
        </div>
    </div>
    <script>
        function showCam(){
            $('#picCam').removeClass('d-none');
            $('#picRes').addClass('d-none');
        }
        function showRes(){
            $('#picRes').removeClass('d-none');
            $('#picCam').addClass('d-none');
        }
    </script>
    <script language="JavaScript">
            Webcam.set({
                width: 350,
                height: 400,

                image_format: 'jpeg',

                jpeg_quality: 90,

            });
        
        Webcam.attach( '#my_camera' );
        
        function take_snapshot() {
            Webcam.snap( function(data_uri) {
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
                showRes();
            });

        }
        
    </script>
    
@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group">
        <button type="submit" class="btn btn-success">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">TUTUP</button>
    </div>
@endsection
