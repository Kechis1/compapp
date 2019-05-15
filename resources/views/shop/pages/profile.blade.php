@extends('layout')

@section('content')
    <div id="wrapper">
        @include('shop.includes.sidebar')
        <div id="content-wrapper">
            <div class="container-fluid">
                @include('breadcrumbs')
                @include('portal.includes.messages')
                <div class="card">
                    <div class="card-header">
                        {{__('titles.settings')}}
                    </div>
                    <div class="card-body">
                        <form action="{{ action('ShopController@profileUpdate') }}" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
                            @csrf
                            {{ method_field('PUT') }}
                            <section class="mb-4 text-center">
                                @if(Auth::user()->act_iae_id!==null)
                                    <img alt="{{Auth::user()->image()->first()->iae_name}}" src="{{ asset('storage/'.Auth::user()->image()->first()->iae_path.'.'.Auth::user()->image()->first()->iae_type) }}" data-holder-rendered="true" style="max-width: 100px; max-height: 100px;">
                                @else
                                    <i class="fas text-secondary fa-3x fa-image"></i>
                                @endif
                            </section>
                            <section class="mb-5">
                                <h2>{{__('titles.contact_information')}}</h2>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="first_name">{{__('inputs.first_name')}}: <span class="text-danger">*</span></label>
                                        <input type="text" name="amr_first_name" class="form-control @error('amr_first_name') is-invalid @enderror" value="{{ Auth::user()->amr_first_name }}" id="first_name" required autofocus>
                                    </div>
                                    <div class="form-group col">
                                        <label for="last_name">{{__('inputs.last_name')}}: <span class="text-danger">*</span></label>
                                        <input type="text" name="amr_last_name" class="form-control @error('amr_last_name') is-invalid @enderror" value="{{ Auth::user()->amr_last_name }}" id="last_name" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="telephone_number">{{__('inputs.telephone_number')}}: <span class="text-danger">*</span></label>
                                    <input type="text" name="ete_cellnumber" class="form-control @error('ete_cellnumber') is-invalid @enderror" value="{{ Auth::user()->ete_cellnumber }}" id="telephone_number" required>
                                </div>
                            </section>
                            <section class="mb-4">
                                <h2>{{__('titles.company_information')}}</h2>
                                <div class="form-group">
                                    <label for="company_name">{{__('inputs.company_name')}}: <span class="text-danger">*</span></label>
                                    <input type="text" name="ete_name" class="form-control @error('ete_name') is-invalid @enderror" value="{{ Auth::user()->ete_name }}" id="company_name" required>
                                </div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="company_url">{{__('inputs.company_url')}}: <span class="text-danger">*</span></label>
                                        <input type="text" name="ete_url_web" class="form-control @error('ete_url_web') is-invalid @enderror" value="{{ Auth::user()->ete_url_web }}" id="company_url" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="company_feed_url">{{__('inputs.company_feed_url')}}: <span class="text-danger">*</span></label>
                                        <input type="text" name="ete_url_feed" class="form-control @error('ete_url_feed') is-invalid @enderror" value="{{ Auth::user()->ete_url_feed }}" id="company_feed_url" required>
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
                                        <input type="text" name="ete_tin" class="form-control @error('ete_tin') is-invalid @enderror" value="{{ Auth::user()->ete_tin }}" id="tin" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="vatin">{{__('inputs.vatin')}}:</label>
                                        <input type="text" name="ete_vatin" class="form-control @error('ete_vatin') is-invalid @enderror" value="{{ Auth::user()->ete_vatin }}" id="vatin">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="country">{{__('inputs.country')}}: <span class="text-danger">*</span></label>
                                    <input type="text" name="ete_country" class="form-control @error('ete_country') is-invalid @enderror" value="{{ Auth::user()->ete_country }}" id="country" required>
                                </div>
                                <div class="form-group">
                                    <label for="street">{{__('inputs.street')}}: <span class="text-danger">*</span></label>
                                    <input type="text" name="ete_street" class="form-control @error('ete_street') is-invalid @enderror" value="{{ Auth::user()->ete_street }}" id="street" required>
                                </div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="city">{{__('inputs.city')}}: <span class="text-danger">*</span></label>
                                        <input type="text" name="ete_city" class="form-control @error('ete_city') is-invalid @enderror" value="{{ Auth::user()->ete_city }}" id="city" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="zip">{{__('inputs.zip')}}: <span class="text-danger">*</span></label>
                                        <input type="text" name="ete_zip" class="form-control @error('ete_zip') is-invalid @enderror" value="{{ Auth::user()->ete_zip }}" id="zip" required>
                                    </div>
                                </div>
                            </section>
                            <section>
                                <h2>{{__('titles.credentials')}}</h2>
                                <div class="form-group">
                                    <label for="email">{{__('inputs.email')}}: <span class="text-danger">*</span></label>
                                    <input type="email" name="act_email" class="form-control @error('act_email') is-invalid @enderror" value="{{ Auth::user()->act_email }}" id="email" required>
                                </div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="newPassword">{{__('inputs.new_password')}}:</label>
                                        <input type="password" name="new_amr_password" class="form-control @error('new_amr_password') is-invalid @enderror" id="newPassword">
                                    </div>
                                    <div class="form-group col">
                                        <label for="newPasswordAgain">{{__('inputs.new_password_again')}}:</label>
                                        <input type="password" name="new_password_again" class="form-control @error('new_password_again') is-invalid @enderror" id="newPasswordAgain">
                                    </div>
                                </div>
                            </section>
                            <button type="submit" class="btn btn-primary">{{__('buttons.update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection