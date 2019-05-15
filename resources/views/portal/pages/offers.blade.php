@extends('portal.layout')

@section('content')
    @include('portal.includes.breadcrumbs')

    <div id="main">
        <div class="container">
            @include('portal.includes.messages')
            <div class="row mt-30">
                <div class="col-md-4">
                    @if(isset($product->image))
                        <img alt="" style="max-width: 150px; max-height: 150px;" class="img-center" src="{{ asset('storage/'.$product->image) }}">
                    @else
                        <i class="fas text-secondary fa-3x fa-image"></i>
                    @endif
                </div>
                <div class="col-md-8">
                    <h1>{{$product->ple_name}}</h1>
                    <ul class="list-inline">
                        <li class="list-inline-item @if($product->rating < 50) text-danger @elseif($product->rating<70) text-dark @else text-success @endif}}">{{intval($product->rating)}}%</li>
                        <li class="list-inline-item "><a href="#nav-reviews" class="text-primary">{{$product->reviews . ' ' . __('label.reviews')}}</a></li>
                    </ul>
                    <p>
                        {{$product->ple_desc_short}}
                        <a href="#nav-info">{{__('label.product_details')}}</a>
                    </p>
                </div>
            </div>
            <div class="panel mt-30">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link @if(!Request::input('page_reviews'))active @endif" id="nav-home-tab" data-toggle="tab" href="#nav-compare" role="tab" aria-controls="nav-home" aria-selected="@if(!Request::input('page_reviews'))true @else false @endif">{{ __('label.compare_prices') }}</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-info" role="tab" aria-controls="nav-profile" aria-selected="false">{{ __('label.product_info') }}</a>
                        <a class="nav-item nav-link @if(Request::input('page_reviews'))active @endif" id="nav-contact-tab" data-toggle="tab" href="#nav-reviews" role="tab" aria-controls="nav-contact" aria-selected="@if(Request::input('page_reviews'))true @else false @endif">{{ __('label.list_reviews') }}</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade @if(!Request::input('page_reviews')) show active @endif" id="nav-compare" role="tabpanel" aria-labelledby="nav-home-tab">
                        @if(count($offers) > 0)
                            <div class="table-responsive">
                                <table class="table bg-white border">
                                    <tbody>
                                    @foreach($offers as $offer)
                                        <tr>
                                            <td class="text-center align-middle" style="width:80px;">
                                                @if(isset($offer->image))
                                                    <img alt="" src="{{ asset('storage/'.$offer->image) }}" data-holder-rendered="true" style="max-width: 60px; max-height: 60px;">
                                                @else
                                                    <i class="fas text-secondary fa-3x fa-image"></i>
                                                @endif
                                            </td>
                                            <th class="text-left align-middle">
                                                <a class="text-primary" href="/companies/{{$offer->act_id}}">{{ $offer->ete_name }}</a>
                                                <ul class="list-inline m-0">
                                                    @if($offer->rating === null)
                                                        <li class="list-inline-item text-muted">{{__('label.no_rating')}}</li>
                                                    @else
                                                        <li class="list-inline-item @if($offer->rating < 50) text-danger @elseif($offer->rating<70) text-dark @else text-success @endif">{{intval($offer->rating)}}%</li>
                                                    @endif
                                                    <li class="list-inline-item ">({{$offer->reviews}})</li>
                                                </ul>
                                            </th>
                                            <td class="text-center align-middle">
                                                <i class="fas fa-truck"></i>
                                                @if($offer->pey_price==0)
                                                    <p class="text-success m-0">{{__('label.free')}}</p>
                                                @else
                                                    <p class="text-dark m-0">{{__('label.from').' '.$offer->pey_price}} Kč</p>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($offer->pee_availability==0)
                                                    <p class="text-success m-0">{{__('label.in_stock')}}</p>
                                                @elseif($offer->pee_availability==-1)
                                                    <p class="text-muted m-0">{{__('label.out_of_stock')}}</p>
                                                @else
                                                    <p class="text-dark m-0">{{__('label.within_days', ["days" => $offer->pee_availability])}}</p>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                <b class="price">{{$offer->pee_price}} Kč</b>
                                            </td>
                                            <td class="text-right align-middle">
                                                <a class="btn btn-primary" role="button" href="{{$offer->pee_url}}">{{__('buttons.to_the_shop')}}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="pagination-wrapper">
                                @if($pages_offers > 1)
                                    <nav class="pages">
                                        <ul class="pagination">
                                            @for($i = 1; $i <= $pages_offers; $i++)
                                                @if($i==Request::input('page_offers', 1))
                                                    <li class="page-item active"><a class="page-link" href="#">{{ $i }}</a></li>
                                                @else
                                                    <li class="page-item"><a class="page-link" href="/offers/{{$product->ple_url}}?page_offers={{$i}}">{{ $i }}</a></li>
                                                @endif
                                            @endfor
                                        </ul>
                                    </nav>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-primary" role="alert">
                                {{ ucfirst(__('pages.offers')) . ' ' . __('alerts.not_found') }}
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="nav-info" role="tabpanel" aria-labelledby="nav-profile-tab">
                        @if(count($params) > 0)
                            <table class="table">
                                <tbody>
                                    @foreach($params as $param)
                                        <tr>
                                            <th>{{$param->pls_name}}@if($param->pls_unit!==null)[{{$param->pls_unit}}]@endif</th>
                                            <td>{{$param->pvs_value}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-primary" role="alert">
                                {{ ucfirst(__('alerts.params')) . ' ' . __('alerts.not_found') }}
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane fade @if(Request::input('page_reviews'))show active @endif" id="nav-reviews" role="tabpanel" aria-labelledby="nav-contact-tab">
                        @include('reviews.create')

                        @if(count($reviews) > 0)
                            @foreach($reviews as $review)
                                <div class="card card-product flex-md-row mb-0 h-md-250">
                                    <div class="card-body flex-column flex-0">
                                        <b class="text-dark">{{ $review->act_email }}</b>
                                        <p>{{intval($review->rvw_rating)}}%</p>
                                        <p class="text-muted">{{$review->rvw_date_created}}</p>
                                    </div>
                                    <div class="card-body flex-column text-left">
                                        <h3>{{$review->rvw_title}}</h3>

                                        @if($review->rvw_pros !==null && count($review->rvw_pros)>0)
                                            <ul class="list-pros">
                                                @foreach($review->rvw_pros as $pro)
                                                    <li>{{$pro["item"]}}</li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        @if($review->rvw_cons !==null && count($review->rvw_cons)>0)
                                            <ul class="list-cons">
                                                @foreach($review->rvw_cons as $con)
                                                    <li>{{$con["item"]}}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <p>{{$review->rvw_message}}</p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="pagination-wrapper">
                                @if($pages_reviews > 1)
                                    <nav class="pages">
                                        <ul class="pagination">
                                            @for($i = 1; $i <= $pages_reviews; $i++)
                                                @if($i==Request::input('page_reviews', 1))
                                                    <li class="page-item active"><a class="page-link" href="#">{{ $i }}</a></li>
                                                @else
                                                    <li class="page-item"><a class="page-link" href="/offers/{{$product->ple_url}}?page_reviews={{$i}}">{{ $i }}</a></li>
                                                @endif
                                            @endfor
                                        </ul>
                                    </nav>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-primary" role="alert">
                                {{ ucfirst(__('alerts.review')) . ' ' . __('alerts.not_found') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection