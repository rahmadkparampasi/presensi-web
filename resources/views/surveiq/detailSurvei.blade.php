<div class="card">
    <div class="card-header">
        <div class="card-title mb-0">
            <div class="row">
                <div class="col-6 col-lg-8">
                    <h5>Daftar Pertanyaan</h5>
                </div>
                <div class="col-6 col-lg-4">
                    <button class='btn btn-info float-right' onclick="{{$Survei->survei_kuis == '0' ? 'removeKuis()' : 'addKuis()'}}" data-target="#modalAddSurveiQ" data-toggle="modal" onclick=""><i class="fa fa-plus"></i> TAMBAH</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function removeKuis(){
        $('.surveiqa_v_val').removeAttr('required');

        $('.surveiqa_v_val').removeAttr('max');
        $('.surveiqa_v_val').removeAttr('min');
        $('.surveiqa_v_val').val('0');

        $('.surveiqa_v_class').removeClass('d-block');
        $('.surveiqa_v_class').addClass('d-none');
        
        $('.surveiqa_a_class').removeClass('col-7');
        $('.surveiqa_a_class').addClass('col-8');

        $('.surveiqa_b_class').removeClass('col-2');
        $('.surveiqa_b_class').addClass('col-4');
    }
    function addKuis(){
        $('.surveiqa_v_val').attr('required', 'required');

        $('.surveiqa_v_val').attr('max', '5');
        $('.surveiqa_v_val').attr('min', '1');

        $('.surveiqa_v_class').removeClass('d-none');
        $('.surveiqa_v_class').addClass('d-block');

        $('.surveiqa_a_class').removeClass('col-8');
        $('.surveiqa_a_class').addClass('col-7');
    
        $('.surveiqa_b_class').removeClass('col-4');
        $('.surveiqa_b_class').addClass('col-2');
    }
</script>
@if (count($Surveiq)!=0)
    @foreach ($Surveiq as $tk)
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <div class="card-title mb-0"><h5>{{$tk->surveiq_lbl}}</h5></div>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-danger float-right" onclick="callOtherTWLoad('Menghapus Pertanyaan Survei','{{route('surveiq.delete', [$tk->surveiq_id])}}', '{{route('surveiq.detailSurvei', [$tk->surveiq_survei])}}', 'modalAddSurveiQForm', 'surveiDetailSurveiq')"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (count($tk->a)!=0)
                    <h6>Jawaban</h6>
                    <ul>
                        @foreach ($tk->a as $tkd)
                            <li>{{$Survei->survei_kuis == '0' ? '' : '('.$tkd->surveiqa_v.') '}}{{$tkd->surveiqa_a}}</li>
                        @endforeach
                    </ul>
                @else    
                    <h6>Tidak Ada Daftar Jawaban</h6>
                @endif
            </div>
        </div>
    @endforeach
        
@else
    <div class="card">
        <div class="card-header">
            <div class="card-title mb-0">
                <h6>Belum Ada Daftar Pertanyaan</h6>
            </div>
        </div>
    </div>
@endif
@include('surveiq.modalAddSurveiQ', ['countModalBody' => 'sQ', 'countModalFooter' => 'sQ'])
