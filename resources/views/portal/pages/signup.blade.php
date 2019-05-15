@extends('portal.layout')

@section('content')
    @include('portal.includes.breadcrumbs')

    <div id="main">
        <div class="container">
            @include('portal.includes.messages')

            <form id="signup" action="{{ action('AuthController@signUp') }}" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
                @csrf
                <section class="mb-4">
                    <h2>{{__('titles.credentials')}}</h2>
                    <div class="form-group">
                        <label for="email">{{__('inputs.email')}}: <span class="text-danger">*</span></label>
                        <input type="email" name="act_email" class="form-control @error('act_email') is-invalid @enderror" value="{{ old('act_email') }}" id="email" required autofocus>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="password">{{__('inputs.password')}}: <span class="text-danger">*</span></label>
                            <input type="password" name="amr_password" class="form-control @error('amr_password') is-invalid @enderror" id="password" required>
                        </div>
                        <div class="form-group col">
                            <label for="passwordAgain">{{__('inputs.password_again')}}: <span class="text-danger">*</span></label>
                            <input type="password" name="password_again" class="form-control @error('password_again') is-invalid @enderror" id="passwordAgain" required>
                        </div>
                    </div>
                </section>
                <section class="mb-5">
                    <h2>{{__('titles.contact_information')}}</h2>
                    <div class="row">
                        <div class="form-group col">
                            <label for="first_name">{{__('inputs.first_name')}}: <span class="text-danger">*</span></label>
                            <input type="text" name="amr_first_name" class="form-control @error('amr_first_name') is-invalid @enderror" value="{{ old('amr_first_name') }}" id="first_name" required>
                        </div>
                        <div class="form-group col">
                            <label for="last_name">{{__('inputs.last_name')}}: <span class="text-danger">*</span></label>
                            <input type="text" name="amr_last_name" class="form-control @error('amr_last_name') is-invalid @enderror" value="{{ old('amr_last_name') }}" id="last_name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telephone_number">{{__('inputs.telephone_number')}}: <span class="text-danger">*</span></label>
                        <input type="text" name="ete_cellnumber" class="form-control @error('ete_cellnumber') is-invalid @enderror" value="{{ old('ete_cellnumber') }}" id="telephone_number" required>
                    </div>
                </section>
                <section>
                    <h2>{{__('titles.company_information')}}</h2>
                    <div class="form-group">
                        <label for="company_name">{{__('inputs.company_name')}}: <span class="text-danger">*</span></label>
                        <input type="text" name="ete_name" class="form-control @error('ete_name') is-invalid @enderror" value="{{ old('ete_name') }}" id="company_name" required>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="company_url">{{__('inputs.company_url')}}: <span class="text-danger">*</span></label>
                            <input type="text" name="ete_url_web" class="form-control @error('ete_url_web') is-invalid @enderror" value="{{ old('ete_url_web') }}" id="company_url" required>
                        </div>
                        <div class="form-group col">
                            <label for="company_feed_url">{{__('inputs.company_feed_url')}}: <span class="text-danger">*</span></label>
                            <input type="text" name="ete_url_feed" class="form-control @error('ete_url_feed') is-invalid @enderror" value="{{ old('ete_url_feed') }}" id="company_feed_url" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__('inputs.logo')}}</label>
                        <div class="custom-file">
                            <label class="custom-file-label" for="logo">{{__('inputs.logo')}}</label>
                            <input type="file" name="iae_image" class="custom-file-input" id="logo">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="tin">{{__('inputs.tin')}}: <span class="text-danger">*</span></label>
                            <input type="text" name="ete_tin" class="form-control @error('ete_tin') is-invalid @enderror" value="{{ old('ete_tin') }}" id="tin" required>
                        </div>
                        <div class="form-group col">
                            <label for="vatin">{{__('inputs.vatin')}}:</label>
                            <input type="text" name="ete_vatin" class="form-control @error('ete_vatin') is-invalid @enderror" value="{{ old('ete_vatin') }}" id="vatin">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="country">{{__('inputs.country')}}: <span class="text-danger">*</span></label>
                        <input type="text" name="ete_country" class="form-control @error('ete_country') is-invalid @enderror" value="{{ old('ete_country') }}" id="country" required>
                    </div>
                    <div class="form-group">
                        <label for="street">{{__('inputs.street')}}: <span class="text-danger">*</span></label>
                        <input type="text" name="ete_street" class="form-control @error('ete_street') is-invalid @enderror" value="{{ old('ete_street') }}" id="street" required>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="city">{{__('inputs.city')}}: <span class="text-danger">*</span></label>
                            <input type="text" name="ete_city" class="form-control @error('ete_city') is-invalid @enderror" value="{{ old('ete_city') }}" id="city" required>
                        </div>
                        <div class="form-group col">
                            <label for="zip">{{__('inputs.zip')}}: <span class="text-danger">*</span></label>
                            <input type="text" name="ete_zip" class="form-control @error('ete_zip') is-invalid @enderror" value="{{ old('ete_zip') }}" id="zip" required>
                        </div>
                    </div>
                </section>
                <button type="submit" class="btn btn-primary">{{__('buttons.accept')}}</button>
            </form>
        </div>
    </div>
@endsection