@extends('layout')

@section('content')
    <div id="wrapper">
        @include('shop.includes.sidebar')
        <div id="content-wrapper">
            <div class="container-fluid">
                @include('breadcrumbs')

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
                                        <th>{{__('label.name')}}</th>
                                        <th>{{__('label.price')}} [Kč]</th>
                                        <th>{{__('label.active')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                @foreach($product->product()->first()->languages()->get() as $lang)
                                                    <div>
                                                    {{$lang->lge_abbreviation}}: <a href="{{action('PagesController@offers', $lang->pivot->ple_url)}}">{{$lang->pivot->ple_name}}</a>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>{{$product->pee_price}}</td>
                                            <td>
                                                @if($product->pee_active)
                                                    <span class="badge badge-success">{{__('label.yes')}}</span>
                                                @else
                                                    <span class="badge badge-danger">{{__('label.no')}}</span>
                                                @endif
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
                                <span class="items-count text-muted">{{$offset+1}}-{{$offset+$products->count()}} {{__('label.of')}} {{$count}} {{__('label.entries')}}</span>
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