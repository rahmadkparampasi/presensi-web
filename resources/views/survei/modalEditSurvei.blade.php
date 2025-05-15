@extends('layouts.modalAll', ['idModalAll' => 'modalEditSurvei', 'sizeModalAll' => '', 'divLoadModalAll' => 'surveiDetailSurvei', 'urlLoadModalAll' =>route('survei.detail', [$Survei->survei_id]), 'dataLoadModalAll'=>'true', 'urlModalAll'=>route('survei.update'), 'titleModalAll' => 'UBAH DATA SURVEI'])

@section('contentInputHidden')
    <input type="hidden" id="survei_idSurvei" name="survei_id" value="{{$Survei->survei_id}}" />
@endsection
@section('contentModalBody'.$countModalBody)
   
    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="survei_thn">Tahun Survei</label>
        <select class="form-control border rounded border-dark" id="survei_thn" name="survei_thn" required >
            <option value="" hidden>Pilih Salah Satu</option>
            @for ($i = (int)date("Y")-3; $i < (int)date("Y")+1; $i++)
                <option value="{{$i}}" {{(string)$Survei->survei_thn == (string)$i ? 'selected' : ''}}>{{$i}}</option>
            @endfor
        </select>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="survei_kuis">Kuis</label>
        <select class="form-control border rounded border-dark" id="survei_kuis" name="survei_kuis" required >
            <option value="" hidden>Pilih Salah Satu</option>
            <option value="1" {{(string)$Survei->survei_kuis == '1' ? 'selected' : ''}}>Ya</option>
            <option value="2" {{(string)$Survei->survei_kuis == '0' ? 'selected' : ''}}>Tidak</option>
            
        </select>
    </div>

@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group">
        <button type="submit" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
@endsection
