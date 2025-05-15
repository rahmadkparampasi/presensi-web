<div class="col-sm-12">
    <div class="card" id="<?= $IdForm ?>card">
        <div class="card-header">
            <h5 id="<?= $IdForm ?>title">Form </h5><h5>{{$PageTitle}}</h5>
        </div>
        <form class="" action="{{route('absen.insertM')}}" data-load="true" id="<?= $IdForm ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
            <div class="card-body">
                @csrf
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="tipe">LOKASI ABSEN</label>
                    <select class="form-control border rounded border-dark" id="tipe" name="tipe"  required>
                        <option value="" hidden>Pilih Salah Satu</option>
                        <option value="WFO">WFO</option>
                        <option value="WFH">WFH</option>
                        <option value="D">DINAS</option>
                        <option value="O">ON-SITE</option>
                    </select>
                </div>

                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="long">Koordinat Longitude</label>
                    <input type="text" class="form-control" id="long" name="long" placeholder="" required>
                </div>
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="lat">Koordinat Latitude</label>
                    <input type="text" class="form-control" id="lat" name="lat" placeholder="" required>
                </div>

                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="pic">Foto Absensi</label>
                    <input type="file" accept="image/*" capture="" class="form-control" id="pic" name="pic" placeholder="" required>
                </div>
                
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
            </div>
        </form>
    </div>
</div>