<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">

            <li class="menu">
                <a href="{{ route('bsdm.index') }}" data-active="{{ request()->routeIs('bsdm.index') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('bsdm.index') ? 'true' : 'false' }}" class="dropdown-toggle"
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

            <li class="menu menu-heading">
                <div class="heading" style="margin-left: 3px;;"><span>Master</span></div>
            </li>

            <li class="menu">
                <a href="{{ route('bsdm.dosen.index') }}"
                    data-active="{{ request()->routeIs('bsdm.dosen.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('bsdm.dosen.*') ? 'true' : 'false' }}" class="dropdown-toggle"
                    style="text-decoration: none;">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Dosen</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="{{ route('bsdm.matakuliah.index') }}"
                    data-active="{{ request()->routeIs('bsdm.matakuliah.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('bsdm.matakuliah.*') ? 'true' : 'false' }}"
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

            <li class="menu">
                <a href="{{ route('bsdm.prodi.index') }}"
                    data-active="{{ request()->routeIs('bsdm.prodi.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('bsdm.prodi.*') ? 'true' : 'false' }}" class="dropdown-toggle"
                    style="text-decoration: none">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-codesandbox">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                            </path>
                            <polyline points="7.5 4.21 12 6.81 16.5 4.21"></polyline>
                            <polyline points="7.5 19.79 7.5 14.6 3 12"></polyline>
                            <polyline points="21 12 16.5 14.6 16.5 19.79"></polyline>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span>Program Studi</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="{{ route('bsdm.ta.index') }}"
                    data-active="{{ request()->routeIs('bsdm.ta.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('bsdm.ta.*') ? 'true' : 'false' }}" class="dropdown-toggle"
                    style="text-decoration: none">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-pie-chart">
                            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                            <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                        </svg>
                        <span>Tahun Akademik</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading" style="margin-left: 3px;;"><span>Transaction</span></div>
            </li>

            <li class="menu">
                <a href="{{ route('bsdm.pengajaran.index') }}"
                    data-active="{{ request()->routeIs('bsdm.pengajaran.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('bsdm.pengajaran.*') ? 'true' : 'false' }}"
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
                <a href="{{ route('bsdm.pa.index') }}"
                    data-active="{{ request()->routeIs('bsdm.pa.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('bsdm.pa.*') ? 'true' : 'false' }}"
                    class="dropdown-toggle" style="text-decoration: none;">
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
                        <span>Akademik</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="{{ route('bsdm.validasi.index') }}"
                    data-active="{{ request()->routeIs('bsdm.validasi.*') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('bsdm.validasi.*') ? 'true' : '' }}" class="dropdown-toggle"
                    style="text-decoration: none;">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-check-circle">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>Validasi Data</span>
                    </div>
                </a>
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
