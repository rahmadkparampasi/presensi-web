@extends('layouts.mainlayoutHome')

@section('title', $WebTitle)
@section('content')
<!--? Hero Start -->
<div class="slider-area2" style="background-image: url({{url('assets/img/hero2.png')}}) !important;background-size: cover;">
    <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 text-center">
                            <h4 style="color: #102039; font-size: 30px; font-weight: 800; text-transform: capitalize; line-height: 1;">Regulasi Dan Informasi</h4><br/><br/>
                        </div>
                </div>
                </div>
            </div>
    </div>
</div>
<!-- Hero End -->
<!--================Blog Area =================-->
<section class="blog_area single-post-area section-padding">
    <div class="container">
        <table id="detailIndikatordT" data-responsive='true' class="display table align-items-centertable-striped table-hover responsive">
            <thead>
                <tr>
                    <th><p>No</p></th>
                    <th><p>Judul Regulasi Atau Informasi</p></th>
                    <th><p>Berkas</p></th>
                    
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 0;
                @endphp
                @foreach ($Reg as $tk) @php $no++ @endphp 

                <tr>
                    <td><p>{{$no}}</p></td>
                    <td><p>{{stripslashes($tk['reg_jdl'])}}</p></td>
                    <td>
                        <a href="{{url('uploads/'.$tk['reg_fl'])}}" target="_blank" class="button button-contactForm btn_1 boxed-btn p-3"><i class="fa fa-external-link-alt"></i></a>
                    </td>
                    
                </tr>
                @endforeach
            </tbody>
        </table>
        <script>
                $(document).ready(function() {
                dTD('table#detailIndikatordT');
            });
        </script>
        
    </div>
</section>
@include('includes.anotherscript')


<!--================ Blog Area end =================-->
@endsection
