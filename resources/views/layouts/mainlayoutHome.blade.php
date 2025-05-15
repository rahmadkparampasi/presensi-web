<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title> SIMETRI </title>
        <meta name="description" content="Sistem Informasi Manajemen Terpadu Riset Dan Inovasi"/>
        <meta name="copyright"content="Badan Perencanaan Pembangunan Penelitian Dan Pengembangan Daerah Kabupaten Parigi Moutong">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="manifest" href="site.webmanifest">
        <link rel="icon" href="/logo.png" type="image/png" />
        <meta name="og:image" content="/logo.png"/>
        <meta name="author" content="Badan Perencanaan Pembangunan Penelitian Dan Pengembangan Daerah Kabupaten Parigi Moutong" />

        <!-- CSS here -->
        <link rel="stylesheet" href="{{url('vendors/include/css/home/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{url('vendors/include/css/home/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{url('vendors/include/css/home/slicknav.css')}}">
        <link rel="stylesheet" href="{{url('vendors/include/css/home/flaticon.css')}}">
        <link rel="stylesheet" href="{{url('vendors/include/css/home/gijgo.css')}}">
        <link rel="stylesheet" href="{{url('vendors/include/css/home/animate.min.css')}}">
        <link rel="stylesheet" href="{{url('vendors/include/css/home/animated-headline.css')}}">
        <link rel="stylesheet" href="{{url('vendors/include/css/home/magnific-popup.css')}}">
        <!-- fontawesome -->
        <link rel="stylesheet" href="/vendors/script/fontawesome/css/all.min.css" media="all">
        {{-- <link rel="stylesheet" href="{{url('vendors/include/css/home/fontawesome-all.min.css')}}"> --}}
        <link rel="stylesheet" href="{{url('vendors/include/css/home/themify-icons.css')}}">
        <link rel="stylesheet" href="{{url('vendors/include/css/home/slick.css')}}">
        <link rel="stylesheet" href="{{url('vendors/include/css/home/nice-select.css')}}">
        <link rel="stylesheet" href="{{url('vendors/include/css/home/style.css')}}">

        <link rel="stylesheet" href="{{url('vendors/script/jfMagnify-master/jfMagnify.css')}}">

        <link rel="stylesheet" href="/vendors/include/css/lightbox.min.css" media="all">
        <link rel="stylesheet" href="/vendors/include/css/ekko-lightbox.css" media="all">

        <!-- DataTables -->

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.11.5/af-2.3.7/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/datatables.min.css" />

        <script src="/vendors/script/jquery/3.1.1/jquery.min.js"></script>
        <style>
            .department_area .depart_ment_tab .nav li a.active{
                height: 120px;
            }
            .department_area .depart_ment_tab .nav li a{
                justify-content: center;
            }
            .nice-select, .nice-select .list{
                width: 100% !important;
            }
        </style>
    </head>
    <body>
        <div class="magnify">
            <div class="magnify_glass"></div>
            <div class="element_to_magnify">
                <!-- ? Preloader Start -->
                <div id="preloader-active">
                    <div class="preloader d-flex align-items-center justify-content-center">
                        <div class="preloader-inner position-relative">
                            <div class="preloader-circle"></div>
                            <div class="preloader-img pere-text">
                                <img src="{{url('assets/img/favicon.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Preloader Start -->
                <header>
                    <!--? Header Start -->
                    <div class="header-area">
                        <div class="main-header header-sticky">
                            <div class="container-fluid">
                                <div class="row align-items-center">
                                    <!-- Logo -->
                                    <div class="col-xl-2 col-lg-2 col-md-1">
                                        <div class="logo">
                                            <a href="{{url('/')}}"><img width="150" src="{{url('assets/img/logo-t.png')}}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-xl-10 col-lg-10 col-md-10">
                                        <div class="menu-main d-flex align-items-center justify-content-end">
                                            <!-- Main-menu -->
                                            <div class="main-menu f-right d-none d-lg-block">
                                                <nav>
                                                    <ul id="navigation">
                                                        <li><a href="{{url('/')}}">Beranda</a></li>
                                                        {{-- <li><a href="{{url('/about')}}">Tentang</a></li> --}}
                                                        <li><a href="{{route('iid.viewHome')}}">Indeks Inovasi Daerah</a></li>
                                                        <li><a href="{{route('reg.viewHome')}}">Regulasi Dan Informasi</a></li>
                                                        <li><a href="{{route('haki.viewHome')}}">HAKI</a></li>
                                                        
                                                    </ul>
                                                </nav>
                                            </div>
                                            <div class="header-right-btn f-right d-none d-lg-block ml-30">
                                                <a href="{{url('masuk')}}" class="btn header-btn btn-green">MASUK</a>
                                                <a href="{{url('register')}}" class="btn header-btn btn-danger">DAFTAR</a>

                                                <a href="#" class="mx-1 d-none d-lg-inline" data-step="1" data-intro="Pembesar Konten" id="toogle_magnify" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Magnify"><i class="fa fa-search-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>   
                                    <!-- Mobile Menu -->
                                    <div class="col-12">
                                        <div class="mobile_menu d-block d-lg-none"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Header End -->
                </header>
                <main>
                    @yield('content')
                </main>
                <footer>
                    <!--? Footer Start-->
                    <div class="footer-area section-bg" data-background="">
                        <div class="container">
                            <div class="footer-top footer-padding">
                                <div class="row d-flex justify-content-between">
                                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-8">
                                        <div class="single-footer-caption mb-50">
                                            <!-- logo -->
                                            <div class="footer-logo">
                                                <a href="index.html"><img src="{{url('assets/img/favicon.png')}}" width="100" alt=""></a>
                                
            
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-5">
                                        <div class="single-footer-caption mb-50">
                                            <div class="footer-tittle">
                                                <h4>Tentang Kami</h4>
                                                <div class="footer-pera">
                                                    <p class="info1">BAPPLITBANGDA</p>
                                                    <p class="info1">Badan Perencanaan Pembangunan Penelitian Dan Pengembangan Daerah Kabupaten Parigi Moutong.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8">
                                        <div class="single-footer-caption mb-50">
                                            <div class="footer-number mb-50">
                                                <h4><span>+62 </span>7885 3222</h4>
                                                <p>bapplitbandaparigimoutongkab@gmail.com</p>
                                            </div>
                                            <!-- Form -->
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="footer-bottom">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-12">
                                        <div class="footer-copy-right">
                                            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                                Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | <a href="https://parigimoutongkab.go.id" target="_blank">Bappelitbangda Kabupaten Parigi Moutong</a>
                                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer End-->
                </footer>
                <!-- Scroll Up -->
                <div id="back-top" >
                    <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                console.log(localStorage.getItem("magnify"));
                if(localStorage.getItem("magnify") && localStorage.getItem('magnify') == 'aktif'){
                    magnifyContent();
                }else{
                    localStorage.setItem("magnify",'non-aktif');
                    $(".magnify_glass").css({"visibility":"hidden"});
                }
                $("#toogle_magnify").click(function(e){
                    setMagnifyActive()
                });

                
                function setMagnifyActive()
                {
                    if(localStorage.getItem('magnify') && localStorage.getItem('magnify') == 'non-aktif'){
                        localStorage.setItem("magnify",'aktif');
                        magnifyContent();
                    }else{
                        $(".magnify_glass").css({"visibility":"hidden"});
                        localStorage.setItem("magnify",'non-aktif');
                    }

                    console.log(localStorage.getItem('magnify'));
                }

                function magnifyContent(){
                    $(".magnify_glass").css({"visibility":"visible","background":"white"});
                    $(".magnify_glass").html("<p class='magnify_glass_loading'>Loading Magnify.....</p>");

                    setTimeout(function(){
                        $(".magnify").jfMagnify({
                            center: true,
                            scale:2,
                        });
                    }, 3000);
                }

            });
        </script>
        <!-- JS here -->
        <script src="{{url('vendors/include/js/home/vendor/modernizr-3.5.0.min.js')}}"></script>
        <!-- Jquery, Popper, Bootstrap -->
        <script src="{{url('vendors/include/js/home/vendor/jquery-1.12.4.min.js')}}"></script>
        <script src="{{url('vendors/include/js/home/popper.min.js')}}"></script>
        <script src="{{url('vendors/include/js/home/bootstrap.min.js')}}"></script>
        <!-- Jquery Mobile Menu -->
        <script src="{{url('vendors/include/js/home/jquery.slicknav.min.js')}}"></script>
        
        <!-- Jquery Slick , Owl-Carousel Plugins -->
        <script src="{{url('vendors/include/js/home/owl.carousel.min.js')}}"></script>
        <script src="{{url('vendors/include/js/home/slick.min.js')}}"></script>
        <!-- One Page, Animated-HeadLin -->
        <script src="{{url('vendors/include/js/home/wow.min.js')}}"></script>
        <script src="{{url('vendors/include/js/home/animated.headline.js')}}"></script>
        <script src="{{url('vendors/include/js/home/jquery.magnific-popup.js')}}"></script>

        <!-- DataTables -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.11.5/af-2.3.7/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/datatables.min.js"></script>
        <script src="//cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
        <script src="//cdn.datatables.net/plug-ins/2.1.8/pagination/full_numbers_no_ellipses.js"></script>
        <script src="//cdn.datatables.net/plug-ins/2.1.8/pagination/simple_numbers_no_ellipses.js"></script>

        <!-- jquery.magnify.js -->
        <script src="{{url('vendors/script/jfMagnify-master/jquery.jfMagnify.min.js')}}"></script>
        
        <!-- Date Picker -->
        <script src="{{url('vendors/include/js/home/gijgo.min.js')}}"></script>
        <!-- Nice-select, sticky -->
        <script src="{{url('vendors/include/js/home/jquery.nice-select.min.js')}}"></script>
        <script src="{{url('vendors/include/js/home/jquery.sticky.js')}}"></script>

        <script src="{{url('vendors/include/js/ekko-lightbox.min.js')}}" defer type="text/javascript"></script>
        <script src="{{url('vendors/include/js/lightbox.min.js')}}" defer type="text/javascript"></script>
        <script src="{{url('vendors/include/js/ac-lightbox.js')}}" defer type="text/javascript"></script>
        
        <!-- counter , waypoint -->
        <script src="{{url('vendors/include/js/home/jquery.counterup.min.js')}}"></script>
        <script src="{{url('vendors/include/js/home/waypoints.min.js')}}"></script>
        <script src="{{url('vendors/include/js/home/jquery.countdown.min.js')}}"></script>
        <!-- contact js -->
        <script src="{{url('vendors/include/js/home/contact.js')}}"></script>
        <script src="{{url('vendors/include/js/home/jquery.form.js')}}"></script>
        <script src="{{url('vendors/include/js/home/jquery.validate.min.js')}}"></script>
        <script src="{{url('vendors/include/js/home/mail-script.js')}}"></script>
        <script src="{{url('vendors/include/js/home/jquery.ajaxchimp.min.js')}}"></script>
    
        <!-- fontawesome -->
        <script src="/vendors/script/fontawesome/js/all.min.js"></script>
        @if (isset($Agent))
            @if ($Agent->isMobile())
                <script>
                    $.fn.DataTable.ext.pager.numbers_length = 3;

                    $.fn.DataTable.ext.pager.numbers_no_ellipses = function(page, pages){
                        var numbers = [];
                        var buttons = $.fn.DataTable.ext.pager.numbers_length;
                        var half = Math.floor( buttons / 2 );

                        var _range = function ( len, start ){
                            var end;
                            
                            if ( typeof start === "undefined" ){
                                start = 0;
                                end = len;

                            } else {
                                end = start;
                                start = len;
                            }

                            var out = [];
                            for ( var i = start ; i < end; i++ ){ out.push(i); }
                            
                            return out;
                        };
                            

                        if ( pages <= buttons ) {
                            numbers = _range( 0, pages );

                        } else if ( page <= half ) {
                            numbers = _range( 0, buttons);

                        } else if ( page >= pages - 1 - half ) {
                            numbers = _range( pages - buttons, pages );

                        } else {
                            numbers = _range( page - half, page + half + 1);
                        }

                        numbers.DT_el = 'span';

                        return [ numbers ];
                    };
                </script>
            @endif
        @endif
        <!-- Jquery Plugins, main Jquery -->	
        <script src="{{url('vendors/include/js/home/plugins.js')}}"></script>
        <script src="{{url('vendors/include/js/home/main.js')}}"></script>
    </body>
</html>