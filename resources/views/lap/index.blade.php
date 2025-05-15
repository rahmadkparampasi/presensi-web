@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="col-sm-12" >
    <div class="card">
        <div class="card-header">
            <h6>Data {{$PageTitle}}</h6>
        </div>
        
        <div class="card-body" id="{{$IdForm}}data">
            @include('lap.data')
        </div>
    </div>
</div>
<script>
    $(function() {
        $(document).ready(function() {
            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{'header': [1,2,3,4,5,6,false]}],
                [{'list': 'ordered'}, {'list':'bullet'}],
                [{'script': 'sub'}, {'script':'supper'}],
                [{'indent': '-1'}, {'indent':'+1'}],
                [{'direction': 'rtl'}],
                ['link'],
                [{'color': []}, {'background':[]}],
                [{'font': []}],
                [{'align': []}],
            ];    
            const lap_ket = new Quill('#lap_kete', {
                modules:{
                    toolbar: toolbarOptions,
                },
                theme:'snow'
            });
            lap_ket.on('text-change', function(delta, oldDelta, source) {
                var html = lap_ket.root.innerHTML;
                $('#lap_ket').val( html )
            });
        });
    });
    function disableQuill(){
        const sisp_conft = new Quill('#lap_kete', {
        }).enable(false);
    }
    
</script>
@include('includes.anotherscript')
@include('includes.ajaxinsertTV')
@include('lap.modalNilai', ['countModalBody' => '3', 'countModalFooter' => '3'])
@include('layouts.modalViewPdf')
@include('layouts.modalViewImg')
@include('layouts.modalViewLabel')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

@endsection