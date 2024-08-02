<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">

            <li class="menu">
                <a href="{{ route('prodi.index') }}" data-active="{{ request()->routeIs('prodi.index') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('prodi.index') ? 'true' : 'false' }}" class="dropdown-toggle"
                    style="text-decoration: none;">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="{{ route('prodi.matakuliah.index') }}"
                    data-active="{{ request()->routeIs('prodi.matakuliah.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('prodi.matakuliah.*') ? 'true' : 'false' }}"
                    class="dropdown-toggle" style="text-decoration: none;">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-box">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                            </path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span>Mata Kuliah</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading" style="margin-left: 3px;;"><span>Transaction</span></div>
            </li>

            <li class="menu">
                <a href="{{ route('prodi.pengajaran.index') }}"
                    data-active="{{ request()->routeIs('prodi.pengajaran.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('prodi.pengajaran.*') ? 'true' : 'false' }}"
                    class="dropdown-toggle" style="text-decoration: none;">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-cpu">
                            <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                            <rect x="9" y="9" width="6" height="6"></rect>
                            <line x1="9" y1="1" x2="9" y2="4"></line>
                            <line x1="15" y1="1" x2="15" y2="4"></line>
                            <line x1="9" y1="20" x2="9" y2="23"></line>
                            <line x1="15" y1="20" x2="15" y2="23"></line>
                            <line x1="20" y1="9" x2="23" y2="9"></line>
                            <line x1="20" y1="14" x2="23" y2="14"></line>
                            <line x1="1" y1="9" x2="4" y2="9"></line>
                            <line x1="1" y1="14" x2="4" y2="14"></line>
                        </svg>
                        <span>Pengajaran</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="{{ route('prodi.pa.index') }}"
                    data-active="{{ request()->routeIs('prodi.pa.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('prodi.pa.*') ? 'true' : 'false' }}" class="dropdown-toggle"
                    style="text-decoration: none;">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-file-text">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <span style="font-size: 11px">Bimbingan Akademik</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="{{ route('prodi.tugas-akhir.index') }}"
                    data-active="{{ request()->routeIs('prodi.tugas-akhir.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('prodi.tugas-akhir.*') ? 'true' : 'false' }}"
                    class="dropdown-toggle" style="text-decoration: none;">
                    <div class="">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                            stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            class="css-i6dzq1">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                            <line x1="6" y1="1" x2="6" y2="4"></line>
                            <line x1="10" y1="1" x2="10" y2="4"></line>
                            <line x1="14" y1="1" x2="14" y2="4"></line>
                        </svg>
                        <span style="font-size: 10px">Penguji Tugas Akhir</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="{{ route('prodi.pkl.index') }}"
                    data-active="{{ request()->routeIs('prodi.pkl.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('prodi.pkl.*') ? 'true' : 'false' }}"
                    class="dropdown-toggle" style="text-decoration: none;">
                    <div class="">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                            stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            class="css-i6dzq1">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path
                                d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32">
                            </path>
                        </svg>
                        <span style="font-size: 10px">Bimbingan PKL</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading" style="margin-left: 3px;;"><span>Report</span></div>
            </li>

            <li class="menu">
                <a href="#elements" data-toggle="collapse"
                    data-active="{{ request()->is('report/*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->is('report/*') ? 'true' : 'false' }}" class="dropdown-toggle"
                    style="text-decoration: none;">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-layers">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        <span>Report</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="submenu list-unstyled collapse {{ request()->is('report/*') ? 'show' : '' }}"
                    id="elements" data-parent="#accordionExample" style="">
                    <li class="{{ request()->is('report/matakuliah') ? 'active' : '' }}">
                        <a href="#" style="text-decoration: none;">Rekap Matakuliah</a>
                    </li>
                    <li class="{{ request()->is('report/dosen') ? 'active' : '' }}">
                        <a href="{{ route('report.dosen') }}" style="text-decoration: none;">Rekap Dosen</a>
                    </li>
                    <li class="{{ request()->is('report/rekap-pembimbingan') ? 'active' : '' }}">
                        <a href="#" style="text-decoration: none; font-size: 12px;">Rekap
                            Pembimbingan</a>
                    </li>
                    <li class="{{ request()->is('report/rekap-penunjang') ? 'active' : '' }}">
                        <a href="#" style="text-decoration: none;">Rekap Penunjang</a>
                    </li>
                    <li class="{{ request()->is('report/dosen-total') ? 'active' : '' }}">
                        <a href="#" style="text-decoration: none;">Rekap Dosen Total</a>
                    </li>
                </ul>
            </li>

        </ul>

    </nav>

</div>
