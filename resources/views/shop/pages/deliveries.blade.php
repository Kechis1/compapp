@extends('layout')

@section('content')
    <div id="wrapper">
        @include('shop.includes.sidebar')
        <div id="content-wrapper">
            <div class="container-fluid">
                @include('breadcrumbs')

                <div class="alert alert-info">
                    {{__('alerts.feed_deliveries_table')}}.<br>
                    {{__('alerts.feed_deliveries_tag', ['tag' => __('alerts.tag_delivery')])}}.<br>
                    <br>
                    <b>{{__('alerts.example')}}:</b>
                    <code class="text-dark">
                        {{__('alerts.feed_deliveries_example')}}
                    </code>
                    <br><br>
                    {{__('label.feed_detail')}} <a class="alert-link" href="{{action('ShopController@feed')}}">{{__('label.here')}}</a>.
                </div>

                <div class="card">
                    <div class="card-header">
                        {{__('pages.deliveries')}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if($count>0)
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>{{__('label.name')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($deliveries as $dly)
                                        <tr>
                                            <td>{{$dly->dly_name}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-primary">
                                    {{__('pages.deliveries').' '.__('alerts.not_found')}}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <span class="items-count text-muted">{{$offset+1}}-{{$offset+$deliveries->count()}} {{__('label.of')}} {{$count}} {{__('label.entries')}}</span>
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