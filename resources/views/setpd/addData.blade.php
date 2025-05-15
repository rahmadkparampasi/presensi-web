<div class="col-sm-12">
    <div class="card" style="<?= $DisplayForm ?>" id="<?= $IdForm ?>card">
        <div class="card-header">
            <h5 id="<?= $IdForm ?>title">Form Tambah</h5><h5>{{$PageTitle}}</h5>
        </div>
        <form class="" action="{{route('setpd.insert')}}" data-load="true" id="<?= $IdForm ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
            <div class="card-body">
                @csrf
                <input type="hidden" class="form-control" id="setpd_id" name="setpd_id">
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="setpd_nm">Nama Pendidikan</label>
                    <input type="text" class="form-control" id="setpd_nm" name="setpd_nm" placeholder="" required>
                </div>
                
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
                <button type="button" onclick="closeForm('<?= $IdForm ?>card', '<?= $IdForm ?>', '{{route('setpd.insert')}}')" class="btn btn-danger">BATAL</button>
            </div>
        </form>
    </div>
</div>