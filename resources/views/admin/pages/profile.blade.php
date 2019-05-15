@extends('layout')

@section('content')
    <div id="wrapper">
        @include('admin.includes.sidebar')
        <div id="content-wrapper">
            <div class="container-fluid">
                @include('breadcrumbs')
                @include('portal.includes.messages')
                <div class="card">
                    <div class="card-header">
                        {{__('titles.settings')}}
                    </div>
                    <div class="card-body">
                        <form action="{{ action('ProfileController@update', $user) }}" method="post" class="needs-validation" novalidate>
                            @csrf
                            {{ method_field('PUT') }}
                            <section class="mb-4">
                                <h2>{{__('titles.contact_information')}}</h2>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="first_name">{{__('inputs.first_name')}}: <span class="text-danger">*</span></label>
                                        <input type="text" name="amr_first_name" class="form-control @error('amr_first_name') is-invalid @enderror" value="{{ $user->amr_first_name }}" id="first_name" required autofocus>
                                    </div>
                                    <div class="form-group col">
                                        <label for="last_name">{{__('inputs.last_name')}}: <span class="text-danger">*</span></label>
                                        <input type="text" name="amr_last_name" class="form-control @error('amr_last_name') is-invalid @enderror" value="{{ $user->amr_last_name }}" id="last_name" required>
                                    </div>
                                </div>
                            </section>
                            <section>
                                <h2>{{__('titles.credentials')}}</h2>
                                <div class="form-group">
                                    <label for="email">{{__('inputs.email')}}: <span class="text-danger">*</span></label>
                                    <input type="email" name="act_email" class="form-control @error('act_email') is-invalid @enderror" value="{{ $user->act_email }}" id="email" required>
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