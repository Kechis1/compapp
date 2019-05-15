<ul class="sidebar navbar-nav" v-bind:class="{toggled: isToggleSidebarActive}">
    <li class="nav-item @if(strcmp(Route::currentRouteName(), 'dashboard')==0) active @endif">
        <a class="nav-link" href="{{action('ShopController@index')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{__('titles.dashboard')}}</span>
        </a>
    </li>
    <li class="nav-item @if(strcmp(Route::currentRouteName(), 'manufacturers')==0) active @endif">
        <a class="nav-link" href="{{action('ShopController@manufacturers')}}">
            <i class="fas fa-fw fa-tools"></i>
            <span>{{__('pages.manufacturers')}}</span></a>
    </li>
    <li class="nav-item @if(strcmp(Route::currentRouteName(), 'categories')==0) active @endif">
        <a class="nav-link" href="{{action('ShopController@categories')}}">
            <i class="fas fa-fw fa-list"></i>
            <span>{{__('pages.category')}}</span></a>
    </li>
    <li class="nav-item @if(strcmp(Route::currentRouteName(), 'deliveries')==0) active @endif">
        <a class="nav-link" href="{{action('ShopController@deliveries')}}">
            <i class="fas fa-fw fa-truck"></i>
            <span>{{__('pages.deliveries')}}</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" target="_blank" href="{{action('PagesController@company', Auth::id())}}">
            <i class="fas fa-fw fa-star"></i>
            <span>{{__('pages.shop_reviews')}}</span></a>
    </li>
    <li class="nav-item @if(strcmp(Route::currentRouteName(), 'parameters')==0) active @endif">
        <a class="nav-link" href="{{action('ShopController@parameters')}}">
            <i class="fas fa-fw fa-cog"></i>
            <span>{{__('pages.parameters')}}</span></a>
    </li>
    <li class="nav-item @if(strcmp(Route::currentRouteName(), 'products')==0) active @endif">
        <a class="nav-link" href="{{action('ShopController@products')}}">
            <i class="fas fa-fw fa-boxes"></i>
            <span>{{__('pages.products')}}</span></a>
    </li>
    <li class="nav-item @if(strcmp(Route::currentRouteName(), 'feed')==0) active @endif">
        <a class="nav-link" href="{{action('ShopController@feed')}}">
            <i class="fas fa-fw fa-rss"></i>
            <span>{{__('pages.feed')}}</span></a>
    </li>
</ul>