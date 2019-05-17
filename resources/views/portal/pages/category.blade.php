@extends('portal.layout')

@section('content')
    @include('portal.includes.breadcrumbs')
    <div id="main">
        <div class="container">
            @if(count($products) > 0)
                @if(count($categories) > 0)
                    <div class="mb-3 card-wrapper text-center">
                        @foreach($categories as $cat)
                            <div class="col-sm-15 card card-border m-0">
                                <div class="card-category card-body">
                                    @if($cat->iae_path !== null)
                                        <img style="max-width: 50px; max-height: 50px;" class="" src="{{ asset('storage/'.$cat->iae_path.'.'.$cat->iae_type) }}">
                                    @else
                                        <i class="fas text-secondary fa-3x fa-image"></i>
                                    @endif
                                </div>
                                <a class="card-title" href="/category/{{$cat->cle_url}}">{{ $cat->cle_name }}</a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-3">
                        <div id="parameters" v-cloak>
                            <div class="loader" v-if="loading"></div>
                            <div class="alert alert-danger" v-else-if="error">
                                {{__('alerts.unknown_error')}}
                            </div>
                            <div class="param-panel-list" v-else>
                                <div class="param-panel-item" v-bind:class="{loading: loading}">
                                    <b class="title" v-on:click="isPriceHidden=!isPriceHidden" v-bind:class="{in: !isPriceHidden}">{{__('label.price')}} [Kč]</b>
                                    <div class="row" v-if="!isPriceHidden">
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="pricemin">{{ucfirst(__('label.from'))}}:</label>
                                            <input id="pricemin" type="text" name="price_min" @change="isLarger()" v-model="items.prices.price_min_selected" class="form-control">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label for="pricemax">{{ucfirst(__('label.to'))}}:</label>
                                            <input id="pricemax" type="text" name="price_max" @change="isSmaller()" v-model="items.prices.price_max_selected" class="form-control">
                                        </div>
                                        <button type="button" @click="onSubmitPrices()" class="btn btn-full btn-outline-primary">{{__('buttons.accept')}}</button>
                                    </div>
                                </div>

                                <div class="param-panel-item">
                                    <b class="title" v-on:click="isAvailabilityHidden=!isAvailabilityHidden" v-bind:class="{in: !isAvailabilityHidden}">{{__('label.availability')}}</b>
                                    <div class="custom-control custom-checkbox" v-if="!isAvailabilityHidden">
                                        <input @change="onChangeAvailability()" v-model="items.availability.model" type="checkbox" class="custom-control-input" id="availability">
                                        <label class="custom-control-label" for="availability">{{__('label.in_stock')}} <span class="text-muted item-count">(@{{items.availability.count_availability}})</span></label>
                                    </div>
                                </div>

                                <div class="param-panel-item">
                                    <b class="title" v-on:click="isManufacturersHidden=!isManufacturersHidden" v-bind:class="{in: !isManufacturersHidden}">{{__('label.manufacturer')}}</b>
                                    <div v-bind:class="{inactive: mur.count_mur==0}" class="custom-control custom-checkbox" v-if="!isManufacturersHidden" v-for="mur in items.manufacturers">
                                        <input :disabled="mur.count_mur==0" @change="onChangeManufacturer(mur.model, mur.mur_id)" v-model="mur.model" type="checkbox" class="custom-control-input" v-bind:id="'manufacturer'+mur.mur_id">
                                        <label class="custom-control-label" v-bind:for="'manufacturer'+mur.mur_id">@{{mur.mur_name}}
                                            <span v-if="!mur.model && !items.man_set" class="text-muted item-count">(@{{mur.count_mur}})</span>
                                            <span v-if="!mur.model && mur.count_mur>0 && items.man_set" class="text-muted item-count">(+@{{mur.count_mur}})</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="param-panel-item" v-for="param in items.params">
                                    <b class="title" v-on:click="param.is_hidden=!param.is_hidden" v-bind:class="{in: !param.is_hidden}">@{{param.pls_name}} <span v-if="param.pls_unit" class="text-muted">[@{{param.pls_unit}}]</span></b>
                                    <div v-bind:class="{inactive: item.count==0||(item.count_plus !== null && item.count_plus-productCount<=0 && !item.model)}" v-for="item in param.items_first" class="custom-control custom-checkbox" v-if="!param.is_hidden">
                                        <input :disabled="item.count==0||(item.count_plus !== null && item.count_plus-productCount<=0 && !item.model)" @change="onChangeParam(item.model, param.prr_id, item.pve_id)" v-model="item.model" v-bind:id="'param'+item.pve_id" type="checkbox" class="custom-control-input">
                                        <label class="custom-control-label" v-bind:for="'param'+item.pve_id">@{{item.pvs_value}}
                                            <span v-if="!item.model && item.count>0 && item.count_plus === null" class="text-muted item-count">(@{{item.count}})</span>
                                            <span v-if="!item.model && item.count_plus !== null && item.count_plus-productCount>0" class="text-muted item-count">(+@{{item.count_plus-productCount}})</span>
                                        </label>
                                    </div>

                                    <button class="btn btn-link" v-if="param.items_all.length > 10 && !param.is_hidden && param.is_next_hidden" v-on:click="param.is_next_hidden=!param.is_next_hidden">
                                        {{ __('buttons.next') }} @{{ Number(param.items_all.length-10) }}
                                        <i class="fas fa-angle-down"></i>
                                    </button>
                                    <div v-bind:class="{collapsed: !param.is_next_hidden}" v-if="param.items_all.length > 10 && !param.is_hidden && !param.is_next_hidden">
                                        <div v-for="n in (param.items_all.length-10)" class="custom-control custom-checkbox">
                                            <input @change="onChangeParam(param.items_all[n+9].model, param.prr_id, param.items_all[n+9].pve_id)" v-model="param.items_all[n+9].model" v-bind:id="'param'+param.items_all[n+9].pve_id" type="checkbox" class="custom-control-input">
                                            <label class="custom-control-label" v-bind:for="'param'+param.items_all[n+9].pve_id">@{{param.items_all[n+9].pvs_value}} <span class="text-muted item-count">(@{{param.items_all[n+9].count}})</span></label>
                                        </div>
                                    </div>
                                    <button class="btn btn-link" v-if="param.items_all.length > 10 && !param.is_hidden && !param.is_next_hidden" v-on:click="param.is_next_hidden=!param.is_next_hidden">
                                        {{ __('buttons.less') }}
                                        <i class="fas fa-angle-up"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <script>

                            let ul = '{{action($paramController, $paramUrl)}}';
                            ul = ul.replace(/&amp;/g, '&');

                            var params = new Vue({
                                el: '#parameters',
                                data () {
                                    return {
                                        productCount: {{$products[0]->count}},
                                        items: null,
                                        loading: true,
                                        error: false,
                                        isPriceHidden: false,
                                        isAvailabilityHidden: false,
                                        isManufacturersHidden: false,
                                        url: new URL('{{Request::fullUrl()}}')
                                    }
                                },
                                mounted () {
                                    axios
                                        .get(ul)
                                        .then(response => {
                                            this.items = response.data
                                        })
                                        .catch(() => {
                                            this.error = true
                                        })
                                        .finally(() =>
                                            this.loading = false
                                        )
                                },
                                methods: {
                                    isLarger () {
                                        if (!(isNaN(this.items.prices.price_min_selected) ? !1 : (x = parseFloat(this.items.prices.price_min_selected), (0 | x) === x)) || (Number(this.items.prices.price_min_selected) > Number(this.items.prices.price_max_selected))
                                            || (Number(this.items.prices.price_min_selected) > Number(this.items.prices.price_max))
                                            || (Number(this.items.prices.price_min_selected) < Number(this.items.prices.price_min))){
                                            this.items.prices.price_min_selected = this.items.prices.price_min;
                                        }
                                    },
                                    isSmaller () {
                                        if (!(isNaN(this.items.prices.price_max_selected) ? !1 : (x = parseFloat(this.items.prices.price_max_selected), (0 | x) === x)) || (Number(this.items.prices.price_max_selected) < Number(this.items.prices.price_min_selected))
                                            || (Number(this.items.prices.price_max_selected) < Number(this.items.prices.price_min))
                                            || (Number(this.items.prices.price_max_selected) > Number(this.items.prices.price_max))) {
                                            this.items.prices.price_max_selected = this.items.prices.price_max;
                                        }
                                    },
                                    onChangeParam (model, prrId, pveId) {

                                        let search = this.url.search;
                                        search = decodeURIComponent(search.replace(/&amp;/g, '&'));
                                        const search_params = new URLSearchParams(this.url.search);
                                        const re = new RegExp('(?<=f:(.*))('+prrId+':)(.*?)(?=;)');
                                        const array = re.exec(decodeURIComponent(search_params.toString()));
                                        let split = [];
                                        if (array === null && model) {
                                            let smatch = search.match(/f:/g);
                                            if (smatch !== null && smatch.length > 0) {
                                                search = search.replace('f:', 'f:' + prrId + ':' + pveId.toString() + ';');
                                            } else if (search.trim().length > 0) {
                                                search = search.replace('?', '?f:' + prrId + ':' + pveId.toString() + ';');
                                            } else {
                                                search = '?f:' + prrId + ':' + pveId.toString() + ';';
                                            }
                                        } else if (array !== null) {
                                            split = array[3].split(',');
                                            if (!model) {
                                                split.splice(split.indexOf(pveId.toString()), 1);
                                            } else if (!split.includes(pveId.toString())) {
                                                split.push(pveId.toString());
                                            }
                                            search = search.replace(prrId+":"+array[3].toString()+";", split.length > 0 ? prrId+":"+split.join(',')+';' : '');
                                        }

                                        search = search.replace(';sort', ';&sort');
                                        search = search.replace(/page=[0-9]+/, '');
                                        window.history.replaceState({}, '', search);
                                        window.location.reload();

                                    },
                                    onChangeManufacturer (model, id) {
                                        let search = this.url.search;
                                        search = decodeURIComponent(search.replace(/&amp;/g, '&'));
                                        const search_params = new URLSearchParams(this.url.search);
                                        const re = new RegExp('(?<=m:)(.*?)(?=;)');
                                        const array = re.exec(decodeURIComponent(search_params.toString()));
                                        let split = [];
                                        if (array === null && model) {
                                            split.push(id.toString());
                                        } else if (array !== null) {
                                            search = search.replace("m:"+array[0].toString()+";", '');
                                            split = array[0].split(',');
                                            if (!model) {
                                                split.splice(split.indexOf(id.toString()), 1);
                                            } else if (!split.includes(id.toString())) {
                                                split.push(id.toString());
                                            }
                                        }

                                        search = (split.length > 0) ? '?m:'+split.join(',')+';' + search.replace('?', '') : search;
                                        search = search.replace(';sort', ';&sort');
                                        search = search.replace(';search', ';&search');
                                        search = search.replace(/page=[0-9]+/, '');
                                        window.history.replaceState({}, '', search);
                                        window.location.reload();
                                    },
                                    onChangeAvailability () {
                                        let search = this.url.search;
                                        search = decodeURIComponent(search.replace(/&amp;/g, '&'));
                                        search = this.items.availability.model ? '?a:true;' + search.replace('?', '') : search.replace('a:true;', '');
                                        search = search.replace(';sort', ';&sort');
                                        search = search.replace(';search', ';&search');
                                        search = search.replace(/page=[0-9]+/, '');
                                        window.history.replaceState({}, '', search);
                                        window.location.reload();
                                    },
                                    onSubmitPrices () {
                                        let search = this.url.search;
                                        search = decodeURIComponent(search.replace(/&amp;/g, '&'));
                                        search = search.replace('?', '');
                                        search = '?pn:'+this.items.prices.price_min_selected+';px:'+this.items.prices.price_max_selected+';' + search.replace(/pn:[0-9]+;px:[0-9]+;/, '');
                                        search = search.replace(';sort', ';&sort');
                                        search = search.replace(';search', ';&search');
                                        search = search.replace(/page=[0-9]+/, '');
                                        window.history.replaceState({}, '', search);
                                        window.location.reload();
                                    }
                                }
                            })
                        </script>
                    </div>
                    <div class="col-md-9">
                        <div class="tools">
                            <span class="items-count text-muted">{{$offset+1}}-{{$offset+count($products)}} {{__('label.of')}} {{$products[0]->count}} {{__('label.products')}}</span>
                            <div class="form-group text-right">
                                <label for="dropdownMenuButton">
                                    {{__('label.sort')}}:
                                </label>
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ __('label.'.$sort_selected) }}
                                    </button>
                                    <div id="sortable" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @foreach ($sort as $item)
                                            @if(strcmp($item,$sort_selected)==0)
                                                <a class="dropdown-item active" href="#">{{ __('label.'.$item) }}</a>
                                            @else
                                                <a @click="changeSort('{{$item}}')" class="dropdown-item" href="#">{{ __('label.'.$item) }}</a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            var sortable = new Vue({
                                el: '#sortable',
                                data () {
                                    return {
                                        url: new URL('{{Request::fullUrl()}}')
                                    }
                                },
                                methods: {
                                    changeSort(sort) {
                                        let search = this.url.search;
                                        search = decodeURIComponent(search.replace(/&amp;/g, '&'));

                                        if (search.indexOf('sort') >= 0) {
                                            search = search.replace(/(sort_name|sort_price_min|sort_price_max|sort_relevance)/, sort);
                                        } else {
                                            if (search.length > 0) {
                                                search = search.replace('?', '?sort=' + sort + '&');
                                            } else {
                                                search = '?sort=' + sort;
                                            }
                                        }

                                        window.history.replaceState({}, '', search);
                                        window.location.reload();
                                    }
                                }
                            });
                        </script>
                        <div>
                            @foreach($products as $item)
                                <div class="card card-product flex-md-row h-md-250">
                                    <div class="card-part-1 card-body d-flex flex-column align-items-start">
                                        @if(isset($item->image) && $item->image !== null && strlen($item->image) > 0)
                                            <img alt="" src="{{ asset('storage/'.$item->image) }}" data-holder-rendered="true" style="max-width: 120px; max-height: 120px;">
                                        @else
                                            <i class="fas text-secondary fa-3x fa-image"></i>
                                        @endif
                                    </div>

                                    <div class="card-part-2 card-body d-flex flex-column align-items-start">
                                        <h3 class="mb-0">
                                            <a class="text-primary" href="/offers/{{$item->ple_url}}">{{ $item->ple_name }}</a>
                                        </h3>
                                        <ul class="list-inline">
                                            <li class="list-inline-item @if($item->rating < 50) text-danger @elseif($item->rating<70) text-dark @else text-success @endif">{{intval($item->rating)}}%</li>
                                            <li class="list-inline-item "><a href="/offers/{{$item->ple_url}}" class="text-primary">{{$item->reviews . ' ' . __('label.reviews')}}</a></li>
                                        </ul>
                                        <p class="card-text mb-auto">{{ $item->ple_desc_short }}</p>
                                    </div>

                                    <div class="card-part-3 card-body flex-column align-items-start">
                                        <b class="price">{{$item->pee_price_min}} Kč - {{$item->pee_price_max}} Kč</b>
                                        <p class="text-muted">{{ __('label.in_shops', ['count'=>$item->shops]) }}</p>
                                        <a class="btn btn-primary" role="button" href="/offers/{{$item->ple_url}}">{{ __('buttons.compare_prices') }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="shopPagination" class="pagination-wrapper">
                            @if($pages > 1)
                                <nav aria-label="Page navigation example" class="pages">
                                    <ul class="pagination">
                                        @for($i = 1; $i <= $pages; $i++)
                                            @if($i==$page)
                                                <li class="page-item active"><a class="page-link" href="#">{{ $i }}</a></li>
                                            @else
                                                <li class="page-item" @click="changePage('{{$i}}')"><a class="page-link" href="#">{{ $i }}</a></li>
                                            @endif
                                        @endfor
                                    </ul>
                                </nav>
                            @endif
                        </div>

                        <script>
                            var shopPagination = new Vue({
                                el: '#shopPagination',
                                data () {
                                    return {
                                        url: new URL('{{Request::fullUrl()}}')
                                    }
                                },
                                methods: {
                                    changePage(page) {
                                        let search = this.url.search;
                                        search = decodeURIComponent(search.replace(/&amp;/g, '&'));

                                        if (search.indexOf('page') >= 0) {
                                            search = search.replace(/page=[0-9]+/, 'page='+page);
                                        } else {
                                            if (search.length > 0) {
                                                search = search.replace('?', '?page=' + page + '&');
                                            } else {
                                                search = '?page=' + page;
                                            }
                                        }
                                        window.history.replaceState({}, '', search);
                                        window.location.reload();
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
            @else
                <div class="alert alert-primary" role="alert">
                    {{ __('alerts.products').' ' .__('alerts.not_found') }}
                </div>
            @endif
        </div>
    </div>
@endsection