@extends('layout')

@section('content')
    <div id="wrapper">
        @include('shop.includes.sidebar')
        <div id="content-wrapper">
            <div class="container-fluid">
                @include('breadcrumbs')

                <div class="alert alert-info">
                    {{__('alerts.feed_categories_table')}}.<br>
                    {{__('alerts.feed_categories_tag', ['tag' => __('alerts.tag_category')])}}.<br>
                    <br>
                    <b>{{__('alerts.example')}}:</b>
                    <code class="text-dark">
                        {{__('alerts.feed_categories_example', ['cat_name' => __('alerts.cat_name')])}}
                    </code>
                    <br><br>
                    {{__('label.feed_detail')}} <a class="alert-link" href="{{action('ShopController@feed')}}">{{__('label.here')}}</a>.
                </div>

                <div class="card">
                    <div class="card-header">
                        {{__('pages.category')}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if($count>0)
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>{{__('label.name')}}</th>
                                        <th>{{__('label.full_path')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $catCount = $count;
                                    @endphp
                                        @foreach($categories as $category)
                                            @php
                                                $la = $category->languages()->where('cle_active', true);
                                                $langs = $la->get();
                                            @endphp
                                            @if ($langs->count() > 0)
                                            <tr>
                                                <td>
                                                    @foreach($langs as $lang)
                                                        <div>{{$lang->lge_abbreviation}}: <a href="{{action('PagesController@category', $lang->pivot->cle_url)}}">{{$lang->pivot->cle_name}}</a></div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @php
                                                        $parent = $category->category()->first();
                                                        $parents = [];
                                                    @endphp
                                                    @foreach($langs as $keyLang => $lang)
                                                        @php
                                                            array_push($parents, ['lang' => $lang->lge_abbreviation, 'active' => $lang->pivot->cle_name, 'items' => []]);
                                                        @endphp
                                                        @while($parent !== null)
                                                            @php
                                                                array_push($parents[$keyLang]['items'], $parent->languages()->where('category_languages.lge_id', $lang->lge_id)->first()->pivot->cle_name);
                                                                $parent = $parent->category()->first();
                                                            @endphp
                                                        @endwhile
                                                    @endforeach

                                                    @foreach($parents as $item)
                                                        <div>
                                                            {{$item['lang']}}:
                                                            @for($i = count($item['items'])-1; $i >= 0; $i--)
                                                                {{$item['items'][$i]}} |
                                                            @endfor
                                                            {{$item['active']}}
                                                        </div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @else
                                                @php
                                                    $catCount = $catCount-1;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-primary">
                                    {{__('pages.category').' '.__('alerts.not_found')}}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <span class="items-count text-muted">{{$categories->count() == 0 ? $offset : $offset+1}}-{{$offset+$categories->count()}} {{__('label.of')}} {{$catCount}} {{__('label.entries')}}</span>
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