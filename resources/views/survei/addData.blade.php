<div class="col-sm-12">
    <div class="card" style="<?= $DisplayForm ?>" id="<?= $IdForm ?>card">
        <div class="card-header">
            <h5 id="<?= $IdForm ?>title">Form Tambah</h5><h5>{{$PageTitle}}</h5>
        </div>
        <form class="" action="{{route('survei.insert')}}" data-load="true" data-urlload="{{$urlLoad}}" id="<?= $IdForm ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
            <div class="card-body">
                @csrf
                <input type="hidden" class="form-control" id="survei_id" name="survei_id">
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="survei_thn">Tahun Survei</label>
                    <select class="form-control" id="survei_thn" name="survei_thn" required >
                        <option value="" hidden>Pilih Salah Satu</option>
                        @for ($i = (int)date("Y")-3; $i < (int)date("Y")+1; $i++)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="survei_kuis">Apakah Ini Kuis?</label>
                    <select class="form-control" id="survei_kuis" name="survei_kuis" required >
                        <option value="" hidden>Pilih Salah Satu</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
                <button type="button" onclick="closeForm('<?= $IdForm ?>card', '<?= $IdForm ?>', '{{route('survei.insert')}}')" class="btn btn-danger">BATAL</button>
            </div>
        </form>
    </div>
</div>