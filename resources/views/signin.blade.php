<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" id="app">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ Config::get('app.name') }}</title>

    <link href="{{ asset('css/backend.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
</head>
<body class="bg-light {{ $page_name }}">

    <div id="admin">
        @include('header')

        <div class="container">
            <div class="card card-login mx-auto mt-5">
                <div class="card-header">{{__('titles.signin')}}</div>
                <div class="card-body">
                    <form id="signup" action="{{ strcmp($type, 'AMR') == 0 ? action('Auth\LoginController@adminLogin') : action('Auth\LoginController@shopLogin') }}" method="post" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-group">
                            <div class="form-label-group">
                                <input type="email" name="act_email" class="form-control @error('act_email') is-invalid @enderror" id="act_email" value="{{ old('act_email') }}" minlength="1" maxlength="100" autocomplete="email" autofocus required>
                                <label for="act_email">{{__('inputs.email')}}: <span class="text-danger">*</span></label>
                                @error('act_email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-label-group">
                                <input type="password" name="amr_password" class="form-control @error('amr_password') is-invalid @enderror" id="amr_password" minlength="6" required>
                                <label for="amr_password">{{__('inputs.password')}}: <span class="text-danger">*</span></label>
                                @error('amr_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-block btn-primary">{{__('buttons.signin')}}</button>
                    </form>
                    <div class="text-center">
                        <a class="d-block small mt-3" href="{{action('PagesController@shopSignUp')}}">{{__('buttons.not_registered_yet')}}</a>
                        <a class="d-block small" href="{{action('PagesController@shopForgotPassword')}}">{{__('buttons.forgot_password')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if(\Illuminate\Support\Facades\Auth::check())
        let csActive = '{{\App\Models\Language::where('lge_id', \Illuminate\Support\Facades\Auth::user()->act_lge_id)->first()->lge_abbreviation}}' == 'cs';
                @else
        let csActive = VueCookie.get('lang') !== null && VueCookie.get('lang') == 'cs';
        @endif

        new Vue({
            el: '#admin',
            data: {
                isToggleSidebarActive: false,
                langItems: [
                    {lge_abbreviation: 'en', lge_name: 'english', active: !csActive},
                    {lge_abbreviation: 'cs', lge_name: 'čeština', active: csActive},
                ]
            },
            methods: {
                toggleSidebar: function () {
                    this.isToggleSidebarActive = !this.isToggleSidebarActive;
                },
                onLangClick: function(ab) {
                    VueCookie.set('lang', ab);
                    @if(\Illuminate\Support\Facades\Auth::check())
                        this.axios.get('/set/locale/' + ab).then(function () {
                        window.location.reload();
                    });
                    @else
                    window.location.reload();
                    @endif
                }
            }
        });
    </script>
</body>
</html>