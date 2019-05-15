@extends('layout')

@section('content')
    <div id="wrapper">
        @include('admin.includes.sidebar')
        <div id="content-wrapper">
            <div class="container-fluid">
                @include('breadcrumbs')
                <div class="card">
                    <div class="card-header">
                        {{__('titles.stats')}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{__('label.product_count')}}</th>
                                        <th>{{__('label.shop_count')}}</th>
                                        <th>{{__('label.guide_count')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="{{action('ProductController@index')}}">{{$product_total}}</a></td>
                                        <td><a href="{{action('CompanyController@index')}}">{{$shop_total}}</a></td>
                                        <td><a href="{{action('GuideController@index')}}">{{$guide_total}}</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection