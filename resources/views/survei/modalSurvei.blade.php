@extends('layouts.modalAll', ['idModalAll' => 'modalSurvei', 'sizeModalAll' => 'modal-lg', 'divLoadModalAll' => '', 'urlLoadModalAll' =>'', 'dataLoadModalAll'=>'true', 'urlModalAll'=>route('surveis.insert'), 'titleModalAll' => 'SURVEI'])

@section('contentInputHidden')
    
@endsection
@section('contentModalBody'.$countModalBody)
    <div class="row">
        <div class="col-12" id="detailSurveiForm"></div>
    </div>
@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group">
        <button type="submit" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
@endsection
