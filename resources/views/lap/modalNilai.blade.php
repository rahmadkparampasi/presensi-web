@extends('layouts.modalAll', ['idModalAll' => 'modalNilai', 'sizeModalAll' => '', 'divLoadModalAll' => 'lapDetailSiswa', 'urlLoadModalAll' =>route('lap.load'), 'dataLoadModalAll'=>'true','urlModalAll'=>route('lap.update'), 'titleModalAll' => 'PENILAIAN LAPORAN'])

@section('contentInputHidden')
@endsection
@section('contentModalBody'.$countModalBody)
    <input type="hidden" id="lap_id" name="lap_id" value="" />
    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="lap_nl">Nilai</label>
        <select class="form-control border rounded border-dark" id="lap_nl" name="lap_nl" required >
            <option value="" hidden>Pilih Salah Satu</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>
    <div class="form-group">
        <label class="control-label" for="lap_ket">Komentar</label> 
        <div id="toolbar"></div>
        <div class="editor" name="lap_kete" id="lap_kete" style="height: 400px;" required></div>
        
        <input type="hidden" id="lap_ket" name="lap_ket" required value="" />
        
    </div>
    

@endsection
@section('contentModalFooter'.$countModalFooter)
<div class="item form-group">
        <button type="submit" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
@endsection
