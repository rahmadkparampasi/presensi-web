<div class="col-sm-12">
    <div class="card" style="<?= $DisplayForm ?>" id="<?= $IdForm ?>card">
        <div class="card-header">
            <h5 id="<?= $IdForm ?>title">Form Tambah</h5><h5>{{$PageTitle}}</h5>
        </div>
        <form class="" action="{{route('setlok.insert')}}" data-load="true" id="<?= $IdForm ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
            <div class="card-body">
                @csrf
                <input type="hidden" class="form-control" id="setlok_id" name="setlok_id">
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="setlok_long">Longitude</label>
                    <input type="text" class="form-control" id="setlok_long" name="setlok_long" placeholder="" required>
                </div>
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="setlok_lat">Latitude</label>
                    <input type="text" class="form-control" id="setlok_lat" name="setlok_lat" placeholder="" required>
                </div>
                
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
                <button type="button" onclick="closeForm('<?= $IdForm ?>card', '<?= $IdForm ?>', '{{route('setlok.insert')}}')" class="btn btn-danger">BATAL</button>
            </div>
        </form>
    </div>
</div>