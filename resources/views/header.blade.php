<nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    @if(Auth::check())
        @if(Auth::guard('shop')->check())
            <a class="navbar-brand mr-1" href="{{action('ShopController@index')}}">{{ Auth::user()->ete_name }}</a>
        @else
            <a class="navbar-brand mr-1" href="{{action('AdminController@index')}}">{{ Auth::user()->amr_first_name . ' ' . Auth::user()->amr_last_name }}</a>
        @endif

        <button @click="toggleSidebar()" class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
            <i class="fas fa-bars"></i>
        </button>
    @endif
    <!-- Navbar -->
    <ul class="justify-content-end w-100 navbar-nav ml-auto ml-md-0">
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-language fa-fw"></i>
            </a>
            <div id="lang" class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
                <a v-for="item in langItems" @click="onLangClick(item.lge_abbreviation)" v-bind:class="{'active': item.active}" class="dropdown-item" href="#">@{{ item.lge_name }}</a>
            </div>
        </li>
        @if(Auth::check())
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle fa-fw"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    @if(Auth::guard('shop')->check())
                        <a class="dropdown-item @if(strcmp(Route::currentRouteName(), 'profile')==0) active @endif" href="{{ action('ShopController@profile') }}">{{__('pages.account_settings')}}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ action('ShopController@logout') }}">{{__('titles.logout')}}</a>
                    @else
                        <a class="dropdown-item @if(strcmp(Route::currentRouteName(), 'profile')==0) active @endif" href="{{ action('ProfileController@edit', Auth::user()) }}">{{__('pages.account_settings')}}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ action('AdminController@logout') }}">{{__('titles.logout')}}</a>
                    @endif
                </div>
            </li>
        @endif
    </ul>

</nav>