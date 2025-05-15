<nav class="pcoded-navbar theme-horizontal menu-light brand-blue">
    <div class="navbar-wrapper container">
        <div class="navbar-content sidenav-horizontal" id="layout-sidenav">
            <ul class="nav pcoded-inner-navbar sidenav-inner">
                <li class="nav-item pcoded-menu-caption">
                    <label>Navigation</label>
                </li>
                
                <?php
                    if ($Pgn!=null) {
                        ?>
                    <?php
                        if ($Pgn['users_id']!=""||$Pgn['users_id']!=null) {
                        ?>
                        <?php
                            if ($Pgn['users_tipe']=="A") {
                        ?>
                            <li class="nav-item">
                                <a href="{{route('home')}}" class="nav-link "><span class="pcoded-micon"><i class="fa fa-tachometer-alt"></i></span><span class="pcoded-mtext">Beranda</span></a>
                            </li>
                            {{-- <li class="nav-item pcoded-hasmenu">
                                <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-graduation-cap"></i></span><span class="pcoded-mtext">Siswa</span></a>
                                <ul class="pcoded-submenu">
                                    <li><a href="{{route('siswa.index')}}">Aktif</a></li>
                                    <li><a href="{{route('siswa.na')}}">Tidak Aktif</a></li>
                                    <li><a href="{{route('siswa.alumni')}}">Alumni</a></li>
                                </ul>
                            </li> --}}
                            <li class="nav-item pcoded-hasmenu">
                                <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-user-tie"></i></span><span class="pcoded-mtext">Pegawai</span></a>
                                <ul class="pcoded-submenu">
                                    <li><a href="{{route('sisp.index')}}">Aktif</a></li>
                                    <li><a href="{{route('sisp.na')}}">Tidak Aktif</a></li>

                                </ul>
                            </li>
                            <li class="nav-item pcoded-hasmenu">
                                <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-envelope"></i></span><span class="pcoded-mtext">Laporan</span></a>
                                <ul class="pcoded-submenu">
                                    <li><a href="{{route('lap.index')}}">Belum Diproses</a></li>
                                    <li><a href="{{route('lap.approved')}}">Dinilai</a></li>

                                </ul>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{route('survei.index')}}" class="nav-link "><span class="pcoded-micon"><i class="fa fa-check-double"></i></span><span class="pcoded-mtext">Survei</span></a>
                            </li>
                            <li class="nav-item pcoded-hasmenu">
                                <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-user"></i></span><span class="pcoded-mtext">Pengguna</span></a>
                                <ul class="pcoded-submenu">
                                    <li><a href="{{route('user.index')}}">Administrator</a></li>
                                    {{-- <li><a href="{{route('user.guru')}}">Pegawai</a></li>
                                    <li><a href="{{route('user.siswa')}}">Siswa</a></li> --}}
                                    <li><a href="{{route('user.pegawai')}}">Pegawai</a></li>
                                    <li><a href="{{route('user.na')}}">Tidak Aktif</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('absen.index')}}" class="nav-link "><span class="pcoded-micon"><i class="fa fa-fingerprint"></i></span><span class="pcoded-mtext">Absen</span></a>
                            </li>
                            <li class="nav-item pcoded-hasmenu">
                                <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fa fa-cogs"></i></span><span class="pcoded-mtext">Pengaturan</span></a>
                                <ul class="pcoded-submenu">
                                    <li><a href="{{route('set.index')}}">Pengaturan Umum</a></li>
                                    <li><a href="{{route('bag.index')}}">Bagian</a></li>
                                </ul>
                            </li>
                            
                        <?php
                            }
                        ?>
                    <?php
                        }
                    ?>
                <?php
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>