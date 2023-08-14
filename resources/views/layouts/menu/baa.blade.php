<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">

            <li class="menu">
                <a href="{{ route('baa.index') }}" data-active="{{ request()->routeIs('baa.index') ? 'true' : '' }}"
                    aria-expanded="{{ request()->routeIs('baa.index') ? 'true' : 'false' }}" class="dropdown-toggle"
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
                <div class="heading" style="margin-left: 3px;;"><span>Report</span></div>
            </li>

            <li class="menu">
                <a href="#elements" data-toggle="collapse" data-active="{{ request()->is('report/*') ? 'true' : '' }}"
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
