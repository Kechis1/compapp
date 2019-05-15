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
                        {{__('pages.languages')}}
                    </div>
                    <div class="card-body">
                        <a href="{{action('LanguageController@create')}}" class="mb-4 btn btn-primary">{{__('buttons.add')}}</a>
                        <div class="table-responsive">
                            @if($count>0)
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>{{__('label.abbreviation')}}</th>
                                        <th>{{__('label.name')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($langs as $lang)
                                        <tr>
                                            <td>{{$lang->lge_id}}</td>
                                            <td>{{$lang->lge_abbreviation}}</td>
                                            <td>{{$lang->lge_name}}</td>
                                            <td>
                                                <a href="{{action('LanguageController@edit', $lang)}}" class="d-inline btn btn-sm btn-outline-secondary">{{__('buttons.show')}}</a>
                                                <form action="{{ action('LanguageController@destroy', $lang) }}" method="post" class="d-inline">
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
                                    {{__('pages.languages').' '.__('alerts.not_found')}}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <span class="items-count text-muted">{{$offset+1}}-{{$offset+$langs->count()}} {{__('label.of')}} {{$count}} {{__('label.entries')}}</span>
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