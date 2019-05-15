@extends('portal.layout')

@section('content')
    @include('portal.includes.breadcrumbs')

    <div id="main">
        <div class="container container-sm">
            @include('portal.includes.messages')
            <form id="signup" action="{{ action('AuthController@refreshPassword', ["actId" => $act_id, "amrCodeRefresh" => $amr_code_refresh]) }}" method="post" class="needs-validation" novalidate>
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <h2>{{__('titles.refresh_password')}}</h2>
                <div class="form-group">
                    <label for="amr_password">{{__('inputs.password')}}: <span class="text-danger">*</span></label>
                    <input type="password" name="amr_password" class="form-control @error('amr_password') is-invalid @enderror" id="amr_password" minlength="6" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password_again">{{__('inputs.password_again')}}: <span class="text-danger">*</span></label>
                    <input type="password" name="password_again" class="form-control @error('password_again') is-invalid @enderror" id="password_again" minlength="6" required>
                </div>
                <button type="submit" class="btn btn-primary">{{__('buttons.accept')}}</button>
            </form>
        </div>
    </div>
@endsection