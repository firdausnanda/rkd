 <!--  BEGIN NAVBAR  -->
 <div class="header-container fixed-top">
     <header class="header navbar navbar-expand-sm">

         <ul class="navbar-item theme-brand flex-row  text-center">
             <li class="nav-item theme-logo">
                 <a href="">
                     <img src="{{ asset('img/logo 90x90.png') }}" class="navbar-logo" alt="logo">
                 </a>
             </li>
             <li class="nav-item theme-text">
                 <a href="#" class="nav-link"> ITSK SOEPRAOEN </a>
             </li>
         </ul>

         <ul class="navbar-item flex-row ml-md-auto">

             <li class="nav-item dropdown user-profile-dropdown">
                 <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown"
                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">

                     <img src="{{ asset('img/anonim.png') }}" alt="avatar">
                 </a>
                 <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                     <div class="">
                         <div class="dropdown-item">
                             <a style="text-decoration: none;" href="#"><svg xmlns="http://www.w3.org/2000/svg"
                                     width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-user">
                                     <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                     <circle cx="12" cy="7" r="4"></circle>
                                 </svg> My Profile</a>
                         </div>
                         <div class="dropdown-item">
                             <a style="text-decoration: none;" href="{{ route('logout') }}"
                                 onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out">
                                     <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                     <polyline points="16 17 21 12 16 7"></polyline>
                                     <line x1="21" y1="12" x2="9" y2="12"></line>
                                 </svg>
                                 Sign Out
                             </a>

                             <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                 style="display: none;">
                                 @csrf
                             </form>
                         </div>
                     </div>
                 </div>
             </li>

         </ul>
     </header>
 </div>
 <!--  END NAVBAR  -->
