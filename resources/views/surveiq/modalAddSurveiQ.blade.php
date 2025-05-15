@extends('layouts.modalAll', ['idModalAll' => 'modalAddSurveiQ', 'sizeModalAll' => '', 'divLoadModalAll' => 'surveiDetailSurveiq', 'urlLoadModalAll' =>route('surveiq.detailSurvei', [$surveiq_survei]), 'dataLoadModalAll'=>'true', 'urlModalAll'=>route('surveiq.insert'), 'titleModalAll' => 'TAMBAH PERTANYAAN SURVEI'])

@section('contentInputHidden')
    <input type="hidden" id="surveiq_survei" name="surveiq_survei" value="{{$surveiq_survei}}" />
    <input type="hidden" id="kuis" name="kuis" value="{{$Survei->survei_kuis}}" />
    
@endsection
@section('contentModalBody'.$countModalBody)
    <h6>Pertanyaan</h6>
    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="surveiq_lbl">Judul Pertanyaan</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="surveiq_lbl" name="surveiq_lbl"  required>
    </div>

    <div class="form-group p-2 mb-0 pb-0">
        <label class="control-label" for="surveiq_desk">Deskripsi</label>
        <textarea name="surveiq_desk" id="surveiq_desk" class="form-control border rounded border-dark" cols="30" rows="2"></textarea>
    </div>
    <hr/>
    <h6>Jawaban</h6>
    <div id="dynamic_form" class="w-100">
        <div class="form-group p-2 mb-0 pb-0 required baru-data row">
            <div class="col-3 surveiqa_v_class">
                <label class="control-label">Nilai</label>
                <input type="number" max="5" min="1" class="form-control border rounded border-dark surveiqa_v_val" placeholder="Ketik Disini" name="surveiqa_v[]" value="1" required>
            </div>
            <div class="col-7 surveiqa_a_class">
                <label class="control-label">Pertanyaan</label>
                <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" name="surveiqa_a[]" required>
            </div>
            <div class="col-2 surveiqa_b_class">
                <button type="button" class="btn btn-success btn-tambah"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-danger btn-hapus" style="display:none;"><i class="fa fa-times"></i></button>
            </div>
        </div>
    </div>
    
    <script>
        function addForm(kuis = 0){
            var addrow = '<div class="form-group p-2 mb-0 pb-0 required baru-data row">\
                    <div class="col-3 surveiqa_v_class '+(kuis==0 ? 'd-none' : 'd-block')+'">\
                        <label class="control-label">Nilai</label>\
                        <input type="number" '+(kuis==0 ? '' : 'max="5" min="1" required')+' class="form-control border rounded border-dark surveiqa_v_val" placeholder="Ketik Disini" name="surveiqa_v[]" value="'+(kuis==0 ? '0' : '1')+'">\
                    </div>\
                    <div class="'+(kuis==0 ? 'col-8' : 'col-7')+' surveiqa_a_class">\
                        <label class="control-label">Pertanyaan</label>\
                        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" name="surveiqa_a[]" required>\
                    </div>\
                    <div class="'+(kuis==0 ? 'col-4' : 'col-2')+' surveiqa_b_class">\
                        <button type="button" class="btn btn-success btn-tambah"><i class="fa fa-plus"></i></button>\
                        <button type="button" class="btn btn-danger btn-hapus"><i class="fa fa-times"></i></button>\
                    </div>\
            </div>'
            $("#dynamic_form").append(addrow);
        }
        $("#dynamic_form").on("click", ".btn-tambah", function(){
            if ($('#kuis').val()=="1") {
                addForm(1)
            }else{
                addForm(0);
            }
            $(this).css("display","none")     
            var valtes = $(this).parent().find(".btn-hapus").css("display","");
        });
        $("#dynamic_form").on("click", ".btn-hapus", function(){
            $(this).parent().parent('.baru-data').remove();
            var bykrow = $(".baru-data").length;
            if(bykrow==1){
                $(".btn-hapus").css("display","none")
                $(".btn-tambah").css("display","");
            }else{
                $('.baru-data').last().find('.btn-tambah').css("display","");
            }
        });
    </script>
@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group">
        <button type="submit" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
@endsection
