@extends('portal.layout')

@section('content')
    @include('portal.includes.breadcrumbs')

    <div id="main">
        <div class="container">
            @include('portal.includes.messages')

            @if($company!==null)
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body pb-2 text-center border-bottom">
                                @if(isset($company->image))
                                    <img alt="{{$company->image->iae_name}}" src="{{ asset('storage/'.$company->image->iae_path.'.'.$company->image->iae_type) }}" data-holder-rendered="true" style="max-width: 60px; max-height: 60px;">
                                @else
                                    <i class="fas text-secondary fa-3x fa-image"></i>
                                @endif
                                <h2 class="mt-3 mb-0"><a href="{{$company->ete_url_web}}">{{$company->ete_name}}</a></h2>
                                @if(!isset($reviews) || $reviews->count() == 0)
                                    <p class="mb-0 text-muted">{{ucfirst(__('label.no_rating'))}}</p>
                                @else
                                    <p class="mb-0 @if($rating < 50) text-danger @elseif($rating<70) text-dark @else text-success @endif">{{intval($rating)}}%</p>
                                @endif
                                <p class="mb-0 text-dark">{{$reviews_count}} {{$reviews_count>4||$reviews_count==0?__('label.reviews'):__('label.reviews_sa')}}</p>

                            </div>
                            <div class="card-body pt-3 text-left">
                                <h5 class="font-weight-bold">{{__('titles.contacts')}}</h5>
                                <p class="font-weight-bold mb-0">{{__('titles.contact_person')}}</p>
                                <p>{{ $company->amr_first_name.' '.$company->amr_last_name }}</p>
                                <p class="font-weight-bold mb-0">{{__('titles.email')}}</p>
                                <p>{{ $company->act_email }}</p>
                                <p class="font-weight-bold mb-0">{{__('titles.cellnumber')}}</p>
                                <p>{{ $company->ete_cellnumber }}</p>
                                <p class="font-weight-bold mb-0">{{__('titles.web')}}</p>
                                <a class="d-block mb-3" href="{{$company->ete_url_web}}">{{ $company->ete_url_web }}</a>
                                <p class="font-weight-bold mb-0">{{__('titles.headquarters')}}</p>
                                <p class="mb-0">{{ $company->ete_street }}<br>
                                {{ $company->ete_city . ' ' . $company->ete_zip }}<br>
                                {{ $company->ete_country }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="panel">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-contact-tab" data-toggle="tab" href="#nav-reviews" role="tab" aria-controls="nav-contact" aria-selected="false">{{ __('label.list_reviews') }}</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-reviews" role="tabpanel" aria-labelledby="nav-contact-tab">
                                    @include('reviews.create')

                                    @if(isset($reviews) && $reviews->count() > 0)
                                        @foreach($reviews as $review)
                                            <div class="card card-product flex-md-row mb-0 h-md-250">
                                                <div class="card-body flex-column flex-0">
                                                    <b class="text-dark">{{ $review->user()->first()->act_email }}</b>
                                                    <p>{{intval($review->rvw_rating*20)}}%</p>
                                                    <p class="text-muted">{{$review->rvw_date_created}}</p>
                                                </div>
                                                <div class="card-body flex-column text-left">
                                                    <h3>{{$review->rvw_title}}</h3>

                                                    @if($review->rvw_pros !== null && strlen(trim($review->rvw_pros))>0)
                                                        @php
                                                            $pros = json_decode($review->rvw_pros, 1);
                                                        @endphp
                                                        <ul class="list-pros">
                                                            @foreach($pros as $pro)
                                                                <li>{{$pro["item"]}}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif

                                                    @if($review->rvw_cons !== null && strlen(trim($review->rvw_cons))>0)
                                                        @php
                                                            $cons = json_decode($review->rvw_cons, 1);
                                                        @endphp
                                                        <ul class="list-cons">
                                                            @foreach($cons as $con)
                                                                <li>{{$con["item"]}}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                    <p>{{$review->rvw_message}}</p>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div id="shopPagination" class="pagination-wrapper">
                                            @if($pages > 1)
                                                <nav aria-label="Page navigation example" class="pages">
                                                    <ul class="pagination">
                                                        @for($i = 1; $i <= $pages; $i++)
                                                            @if($i==$page)
                                                                <li class="page-item active"><a class="page-link" href="#">{{ $i }}</a></li>
                                                            @else
                                                                <li class="page-item"><a class="page-link" href="/companies/{{$company->act_id}}?page={{$i}}">{{ $i }}</a></li>
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
            @else
                <div class="alert alert-primary" role="alert">
                    {{ ucfirst(__('alerts.company')) . ' ' . __('alerts.was_not_found') }}
                </div>
            @endif
        </div>
    </div>
@endsection