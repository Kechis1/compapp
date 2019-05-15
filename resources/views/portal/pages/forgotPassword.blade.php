@extends('portal.layout')

@section('content')
    @include('portal.includes.breadcrumbs')

    <div id="main">
        <div class="container container-sm">
            @include('portal.includes.messages')
            <form id="signup" action="{{ action('AuthController@forgotPassword') }}" method="post" class="needs-validation" novalidate>
                @csrf
                {{ method_field('PATCH') }}
                <h2>{{__('titles.forgot_password')}}</h2>
                <div class="form-group">
                    <label for="email">{{__('inputs.email')}}: <span class="text-danger">*</span></label>
                    <input type="email" name="act_email" class="form-control @error('act_email') is-invalid @enderror" id="email" minlength="1" required>
                </div>
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-full btn-primary">{{__('buttons.send')}}</button>
                    </div>
                    <div class="col">
                        <a href="{{action('Auth\LoginController@showShopLoginForm')}}" class="btn btn-full btn-link">{{__('buttons.back_to_signin')}}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection