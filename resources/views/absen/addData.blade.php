<div class="col-sm-12">
    <div class="card" id="<?= $IdForm ?>card">
        <div class="card-header">
            <h5 id="<?= $IdForm ?>title">Form </h5><h5>{{$PageTitle}}</h5>
        </div>
        <form class="" action="{{route('absen.insert')}}" data-load="true" id="<?= $IdForm ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
            <div class="card-body">
                @csrf
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="tipe">Jenis Absen</label>
                    <select class="form-control border rounded border-dark" id="tipe" name="tipe"  required>
                        <option value="" hidden>Pilih Salah Satu</option>
                        <option value="M">Masuk</option>
                        <option value="P">Pulang</option>
                    </select>
                </div>

                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="kartu">Kode Kartu</label>
                    <input type="text" class="form-control" id="kartu" name="kartu" autofocus placeholder="" required>
                </div>
                
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
            </div>
        </form>
    </div>
</div>