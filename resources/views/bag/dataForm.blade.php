<div class="card-header">
    <h5 id="dataFromTitle">Pengaturan Ruangan {{$Bag->bag_nm}}</h5>
</div>
<div class="card-body">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tabINM-tab" data-toggle="tab" data-target="#tabINM" type="button" role="tab" aria-controls="tabINM" aria-selected="true">INM</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tabUsersBag-tab" data-toggle="tab" data-target="#tabUsersBag" type="button" role="tab" aria-controls="tabUsersBag" aria-selected="false" onclick="loadTabFormUser('{{$Bag->bag_id}}')">Pengguna</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tabINM" role="tabpanel" aria-labelledby="tabINM-tab">
            @include('bag.dataFormBag')
        </div>
        <div class="tab-pane fade" id="tabUsersBag" role="tabpanel" aria-labelledby="tabUsersBag-tab">

        </div>
    </div>
</div>