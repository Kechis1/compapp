<ul class="sidebar navbar-nav" v-bind:class="{toggled: isToggleSidebarActive}">
    <li class="nav-item @if(strcmp(Route::currentRouteName(), 'dashboard')==0) active @endif">
        <a class="nav-link" href="{{action('AdminController@index')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{__('titles.dashboard')}}</span>
        </a>
    </li>
    <li class="nav-item @if(strpos(Route::currentRouteName(), 'parameters')!==false && strpos(Route::currentRouteName(), 'parameters')==0) active @endif">
        <a class="nav-link" href="{{action('ParameterController@index')}}">
            <i class="fas fa-fw fa-cog"></i>
            <span>{{__('pages.parameters')}}</span>
        </a>
    </li>
    <li class="nav-item @if(strpos(Route::currentRouteName(), 'categories')!==false && strpos(Route::currentRouteName(), 'categories')==0) active @endif">
        <a class="nav-link" href="{{action('CategoryController@index')}}">
            <i class="fas fa-fw fa-list"></i>
            <span>{{__('pages.category')}}</span>
        </a>
    </li>
    <li class="nav-item @if(strpos(Route::currentRouteName(), 'languages')!==false && strpos(Route::currentRouteName(), 'languages')==0) active @endif">
        <a class="nav-link" href="{{action('LanguageController@index')}}">
            <i class="fas fa-fw fa-language"></i>
            <span>{{__('label.languages')}}</span>
        </a>
    </li>
    <li class="nav-item @if(strpos(Route::currentRouteName(), 'guides')!==false && strpos(Route::currentRouteName(), 'guides')==0) active @endif">
        <a class="nav-link" href="{{action('GuideController@index')}}">
            <i class="fas fa-fw fa-magic"></i>
            <span>{{__('pages.guides')}}</span>
        </a>
    </li>
    <li class="nav-item @if(strpos(Route::currentRouteName(), 'manufacturers')!==false && strpos(Route::currentRouteName(), 'manufacturers')==0) active @endif">
        <a class="nav-link" href="{{action('ManufacturerController@index')}}">
            <i class="fas fa-fw fa-tools"></i>
            <span>{{__('pages.manufacturers')}}</span>
        </a>
    </li>
    <li class="nav-item @if(strpos(Route::currentRouteName(), 'companies')!==false && strpos(Route::currentRouteName(), 'companies')==0) active @endif">
        <a class="nav-link" href="{{action('CompanyController@index')}}">
            <i class="fas fa-fw fa-home"></i>
            <span>{{__('pages.companies')}}</span>
        </a>
    </li>
    <li class="nav-item @if(strpos(Route::currentRouteName(), 'products')!==false && strpos(Route::currentRouteName(), 'products')==0) active @endif">
        <a class="nav-link" href="{{action('ProductController@index')}}">
            <i class="fas fa-fw fa-boxes"></i>
            <span>{{__('pages.products')}}</span>
        </a>
    </li>
    <li class="nav-item @if(strpos(Route::currentRouteName(), 'deliveries')!==false && strpos(Route::currentRouteName(), 'deliveries')==0) active @endif">
        <a class="nav-link" href="{{action('DeliveryController@index')}}">
            <i class="fas fa-fw fa-truck"></i>
            <span>{{__('pages.deliveries')}}</span>
        </a>
    </li>
    <li class="nav-item @if(strpos(Route::currentRouteName(), 'images')!==false && strpos(Route::currentRouteName(), 'images')==0) active @endif">
        <a class="nav-link" href="{{action('ImageController@index')}}">
            <i class="fas fa-fw fa-image"></i>
            <span>{{__('pages.images')}}</span>
        </a>
    </li>
</ul>