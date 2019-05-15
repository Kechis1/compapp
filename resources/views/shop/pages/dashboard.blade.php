@extends('layout')

@section('content')
    <div id="wrapper">
        @include('shop.includes.sidebar')
        <div id="content-wrapper">
            <div class="container-fluid">
                @include('breadcrumbs')
                <div class="card">
                    <div class="card-header">
                        {{__('titles.company_information')}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{__('inputs.company_name')}}</th>
                                        <th>{{__('label.last_import')}}</th>
                                        <th>{{__('label.product_count')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{\Illuminate\Support\Facades\Auth::user()->ete_name}}</td>
                                        @if(isset($feed))
                                            <td class="@if(strcmp($feed->fhy_status,'SCS')==0) text-success
                                            @elseif(strcmp($feed->fhy_status,'RCD')==0) text-muted
                                            @elseif(strcmp($feed->fhy_status,'ERR')==0) text-danger
                                            @else text-primary @endif">{{$feed->fhy_date}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td><a href="{{action('ShopController@products')}}">{{$products}}</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @if(strcmp($feed->fhy_status,'SCS')==0)
                            <div class="alert alert-success">
                                {{__('alerts.feed_success')}}
                            </div>
                        @elseif(strcmp($feed->fhy_status,'RCD')==0)
                            <div class="alert alert-secondary">
                                {{__('alerts.feed_received')}}
                            </div>
                        @elseif(strcmp($feed->fhy_status,'ERR')==0)
                            <div class="alert alert-danger">
                                {{__('alerts.feed_error')}}
                                @if($feed->fhy_message !== null && strlen($feed->fhy_message) > 0)
                                    <br>
                                    {{$feed->fhy_message}}.
                                @endif
                            </div>
                        @else
                            <div class="alert alert-primary">
                                {{__('alerts.feed_pending')}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection