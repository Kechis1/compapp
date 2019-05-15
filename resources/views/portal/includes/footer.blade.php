<footer class="text-muted">
    <div class="container">
        <p class="float-right">
            <a href="#">{{__('label.back_to_top')}}</a>
        </p>
        <ul class="list-unstyled m-0">
            <li><a href="/companies">{{__('pages.companies')}}</a></li>
            @if(!Auth::check())
                <li class="mt-2 mb-2"><a href="{{action('PagesController@shopSignUp')}}">{{__('pages.signup')}}</a></li>
                <li><a href="{{action('Auth\LoginController@showShopLoginForm')}}">{{__('pages.signin')}}</a></li>
            @endif
        </ul>
    </div>
</footer>