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
                        {{__('pages.category')}}
                    </div>
                    <div class="card-body">
                        <a href="{{action('CategoryController@create')}}" class="mb-4 btn btn-primary">{{__('buttons.add')}}</a>
                        <div class="table-responsive">
                            @if($count>0)
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>{{__('label.name')}}</th>
                                        <th>{{__('label.full_path')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ceys as $cey)
                                        @php
                                            $ceyPivot = $cey->languages()->where('category_languages.lge_id', \Illuminate\Support\Facades\Auth::user()->act_lge_id)->first();
                                            $parent = $cey->category()->first();
                                            $parents = [];
                                        @endphp
                                        @while($parent !== null)
                                            @php
                                                $parentPivot = $parent->languages()->where('category_languages.lge_id', \Illuminate\Support\Facades\Auth::user()->act_lge_id)->first();
                                                if ($parentPivot !== null)
                                                {
                                                    array_push($parents, $parentPivot->pivot->cle_name);
                                                }
                                                $parent = $parent->category()->first();
                                            @endphp
                                        @endwhile

                                        <tr>
                                            <td>{{$cey->cey_id}}</td>
                                            <td>
                                                @if($ceyPivot!==null)
                                                    {{$ceyPivot->pivot->cle_name}}
                                                @else
                                                    {{__('label.attribute_not_set')}}
                                                @endif
                                            </td>
                                            <td>
                                                @for($i = count($parents)-1; $i >= 0; $i--)
                                                    {{$parents[$i]}} |
                                                @endfor
                                                @if($ceyPivot!==null)
                                                    {{$ceyPivot->pivot->cle_name}}
                                                @else
                                                    {{__('label.attribute_not_set')}}
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{action('CategoryController@edit', $cey)}}" class="d-inline btn btn-sm btn-outline-secondary">{{__('buttons.show')}}</a>
                                                <form action="{{ action('CategoryController@destroy', $cey) }}" method="post" class="d-inline">
                                                    @csrf
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn btn-sm btn-danger">{{__('buttons.delete')}}</button>
                                                </form>
                                            </td>
                                        </tr>
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
                                <span class="items-count text-muted">{{$ceys->count() == 0 ? $offset : $offset+1}}-{{$offset+$ceys->count()}} {{__('label.of')}} {{$count}} {{__('label.entries')}}</span>
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