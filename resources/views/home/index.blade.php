@extends('layouts.mainlayoutHome')

@section('title', $WebTitle)
@section('content')
    <!--? slider Area Start-->
    <div class="slider-area position-relative" style="background-image: url({{url('assets/img/h1_hero_1.png')}}) !important;background-size: cover;">
        <div class="slider-active">
            <!-- Single Slider -->
            <div class="single-slider slider-height d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-7 col-lg-9 col-md-8 col-sm-9">
                            <div class="hero__caption">
                                <span>SIMETRI</span>
                                <h1 class="cd-headline letters scale">Ayo Segera Daftarkan 
                                    <strong class="cd-words-wrapper">
                                        <b class="is-visible">Riset</b>
                                        <b>Inovasi</b>
                                        <b>Pengembangan</b>
                                    </strong>
                                </h1>
                                <p data-animation="fadeInLeft" data-delay="0.1s">Sistem Informasi Manajemen Terpadu Riset Dan Inovasi.</p>
                                {{-- <a href="#" class="btn hero-btn" data-animation="fadeInLeft" data-delay="0.5s">Appointment <i class="ti-arrow-right"></i></a> --}}
                            </div>
                        </div>
                    </div>
                </div>          
            </div>
            <!-- Single Slider -->
            {{-- <div class="single-slider slider-height d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-7 col-lg-9 col-md-8 col-sm-9">
                            <div class="hero__caption">
                                <span>SIMETRI</span>
                                <h1 class="cd-headline letters scale">Ayo Segera Daftarkan 
                                    <strong class="cd-words-wrapper">
                                        <b class="is-visible">Riset</b>
                                        <b>Inovasi</b>
                                        <b>Pengembangan</b>
                                    </strong>
                                </h1>
                                <p data-animation="fadeInLeft" data-delay="0.1s">Meningkatkan kualitas sumberdaya manusia yang berdaya saing berdasarkan keimanan dan ketaqwaan.</p>
                            </div>
                        </div>
                    </div>
                </div>          
            </div> --}}
        </div>
    </div>
    <!-- slider Area End-->
    <!--? About Start-->
    <div class="about-area section-padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-10">
                    <div class="about-caption mb-50">
                        <!-- Section Tittle -->
                        <div class="section-tittle section-tittle2 mb-35">
                            <span>Tentang SIMETRI</span>
                            <h2>Selamat Datang</h2>
                        </div>
                        <p>Sistem Informasi Manajemen Terpadu Riset Dan Inovasi Sebagai Wadah Pengembangan Sumber Daya Manusia Dan Produk-Produk Kabupaten Parigi Moutong</p>
                        {{-- <div class="about-btn1 mb-30">
                            <a href="about.html" class="btn about-btn">Indeks Inovasi Daerah <i class="ti-arrow-right"></i></a>
                        </div>
                        <div class="about-btn1 mb-30">
                            <a href="about.html" class="btn about-btn2">Kreasi Dan Inovasi <i class="ti-arrow-right"></i></a>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <!-- about-img -->
                    <div class="about-img ">
                        <div class="about-font-img d-none d-lg-block">
                            <img src="{{url('assets/img/nick-agus-arya-5i3oyOrojvk-unsplash.jpg')}}" width="500" alt="">
                        </div>
                        <div class="about-back-img ">
                            <img src="{{url('assets/img/the-ian-vwzkqMQxsR4-unsplash.jpg')}}" width="400" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About  End-->
    <div class="department_area section-padding2" style="background-image: url({{url('assets/img/department.png')}}) !important; padding-top:100px !important">
        <div class="container">
            <!-- Section Tittle -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-tittle text-center mb-10">
                        <span>INDEKS INOVASI DAERAH</span>
                    </div>
                </div>
            </div>
            @if ($IidTime)
                <div class="row pb-5">
                    @php
                        $NowIid = date("Y-m-d H:i:s");
                        $DateNowIid = strtotime($NowIid);
                        $BatasIid = strtotime($IidTime['iid_tglsN']);
                        
                        $NewBatasIid = date("Y-m-d H:i:s", $BatasIid);
            
                        // if ($DateNowIid>$BatasIid) {
                        //     continue;
                        // }
                    @endphp
                    <div class="col-12">
                        <h2 class="text-center" id="" style="font-size: 40px; display: block; color: #030431; font-weight: 800;">Hitung Mundur Indeks Inovasi Daerah</h2>
                        <h3 class="text-center" id="" style="font-size: 25px; display: block; color: #030431; font-weight: 800;">Tahun IID {{$IidTime['thn_nm']}} - {{$IidTime['iid_proAlt']}}</h3>
                        <h1 class="text-center text-danger" id="timeCountIid" style="font-size: 40px; display: block;  font-weight: bold;"></h1>
                        <div class="about-btn1 mb-30 mt-20 justify-content-center text-center">
                            <a href="{{route('iidd.index', [$IidTime['iid_id']])}}" class="btn about-btn">Ajukan Inovasi <i class="ti-arrow-right"></i></a>
                        </div>
                    </div>
                    <script>
                        // Set the date we're counting down to
                        var countDownDateIid = new Date("{{$NewBatasIid}}").getTime();
                        
                        // Update the count down every 1 second
                        var xIid = setInterval(function() {
                        
                            var nowIid = new Date().getTime();
                        
                            var distanceIid = countDownDateIid - nowIid;
                        
                            var daysIid = Math.floor(distanceIid / (1000 * 60 * 60 * 24));
                            var hoursIid = Math.floor((distanceIid % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutesIid = Math.floor((distanceIid % (1000 * 60 * 60)) / (1000 * 60));
                            var secondsIid = Math.floor((distanceIid % (1000 * 60)) / 1000);
                        
                            document.getElementById("timeCountIid").innerHTML = daysIid + "Hari " + hoursIid + "Jam "+ minutesIid + "Menit " + secondsIid + "Detik ";
            
                            if(distanceIid <= 0){
                                clearInterval(xIid);
                                document.getElementById("timeCountIid").innerHTML = "WAKTU TELAH HABIS";
                            }
                        }, 1000);
                    </script>
                </div>
                
            @endif
            <div class="dept_main_info white-bg">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <!-- single_content  -->
                        <div class="row align-items-center no-gutters">
                            <div class="col-lg-7">
                                <div class="dept_info" style="padding-top: 70px !important; padding-bottom: 70px !important">
                                    <h3>PENILAIAN INOVASI DAERAH DAN PEMBERIAN PENGHARGAAN<br/>INNOVATIVE GOVERNMENT AWARD (IGA)</h3>
                                    <p>Undang-undang Nomor 23 Tahun 2014 tentang Pemerintahan Daerah sebagaimana yang tertuang dalam pasal 388 ayat (9) dan ayat (11) menyatakan bahwa “pemerintah pusat memberikan penilaian terhadap inovasi yang dilaksanakan oleh pemerintah daerah” dan “pemerintah pusat memberikan penghargaan dan/atau insentif kepada pemerintah daerah yang berhasil melaksanakan inovasi”. Sebagai bentuk penjabaran dari perundangan tersebut maka diterbitkanlah Peraturan Pemerintah Nomor 38 Tahun 2017 tentang Inovasi Daerah adalah sebagai petunjuk pelaksanaan bagi pemerintah daerah dalam melaksanakan praktik-praktik inovatif dalam penyelenggaraan pemerintahan daerah.</p>
                                    <a href="{{route('iid.viewHome')}}" class="dep-btn">Detail<i class="ti-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="dept_thumb">
                                    <img src="{{url('assets/img/muhammad-azzam-o5UIXNRVjlc-unsplash.jpg')}}" alt="">
                                </div>
                            </div>
                        </div>
                        <!-- single_content  -->
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
    <!--? department_area_start  -->
    <div class="department_area section-padding2" style="background-image: url({{url('assets/img/department.png')}}) !important;">
        <div class="container">
            <!-- Section Tittle -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-tittle text-center mb-100">
                        {{-- <span>Inovasi</span> --}}
                        <h2>Kategori Kreasi Dan Inovasi</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="depart_ment_tab mb-30">
                        <!-- Tabs Buttons -->
                        <ul class="nav" id="myTab" role="tablist">
                            @foreach ($Katkre as $tk)
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">
                                        
                                        <h4 class="fw-bold">{{$tk['katkre_nm']}}</h4>
                                    </a>
                                </li>
                                
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="container">
            <!-- Section Tittle -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-tittle text-center mb-100">
                        {{-- <span>Inovasi</span> --}}
                        <h2>Kategori Pengembangan</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="depart_ment_tab mb-30">
                        <!-- Tabs Buttons -->
                        <ul class="nav justify-content-evenly" id="myTab" role="tablist" style="justify-content: space-evenly !important;">
                            @foreach ($Katp as $tk)
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">
                                        
                                        <h4 class="fw-bold">{{$tk['katp_nm']}}</h4>
                                    </a>
                                </li>
                                
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!-- depertment area end  -->
@endsection