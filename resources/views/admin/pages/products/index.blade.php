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
                        {{__('pages.products')}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if($count>0)
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>EAN</th>
                                        <th>{{__('label.manufacturer')}}</th>
                                        <th>{{__('label.name')}}</th>
                                        <th>{{__('label.is_active')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($puts as $put)
                                        @php
                                            $putLang = $put->languages()->where('product_languages.lge_id', \Illuminate\Support\Facades\Auth::user()->act_lge_id)->first();
                                        @endphp
                                        <tr>
                                            <td>{{$put->put_id}}</td>
                                            <td>{{$put->put_ean}}</td>
                                            <td>{{$put->manufacturer()->first()->mur_name}}</td>
                                            <td>@if($putLang!==null) {{$putLang->pivot->ple_name}} @endif</td>
                                            <td>
                                                @foreach($put->languages()->where('product_languages.ple_active', true)->get() as $lang)
                                                    <span class="badge badge-success">{{$lang->lge_abbreviation}}</span>
                                                @endforeach
                                                @foreach($put->languages()->where('product_languages.ple_active', false)->get() as $lang)
                                                    <span class="badge badge-danger">{{$lang->lge_abbreviation}}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                 <form action="{{ action('ProductController@statusUpdate', $put) }}" method="post" class="d-inline">
                                                    @csrf
                                                    {{ method_field('PATCH') }}
                                                    <button type="submit" name="statusBtn" value="1" class="btn btn-sm btn-success">{{__('buttons.activate')}}</button>
                                                    <button type="submit" name="statusBtn" value="2" class="btn btn-sm btn-danger">{{__('buttons.deactivate')}}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-primary">
                                    {{__('pages.products').' '.__('alerts.not_found')}}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <span class="items-count text-muted">{{$offset+1}}-{{$offset+$puts->count()}} {{__('label.of')}} {{$count}} {{__('label.entries')}}</span>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                @include('pagination')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection