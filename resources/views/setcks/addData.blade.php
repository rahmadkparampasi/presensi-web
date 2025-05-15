<div class="col-sm-12">
    <div class="card" style="<?= $DisplayForm ?>" id="<?= $IdForm ?>card">
        <div class="card-header">
            <h5 id="<?= $IdForm ?>title">Form Tambah</h5><h5>{{$PageTitle}}</h5>
        </div>
        <form class="" action="{{route('setcks.insert')}}" data-load="true" id="<?= $IdForm ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
            <div class="card-body">
                @csrf
                <input type="hidden" class="form-control" id="setcks_id" name="setcks_id">
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="setcks_nm">Cara Ke Sekolah</label>
                    <input type="text" class="form-control" id="setcks_nm" name="setcks_nm" placeholder="" required>
                </div>
                
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
                <button type="button" onclick="closeForm('<?= $IdForm ?>card', '<?= $IdForm ?>', '{{route('setcks.insert')}}')" class="btn btn-danger">BATAL</button>
            </div>
        </form>
    </div>
</div>