<ol class="breadcrumb">
    @if(!isset($breadcrumbs) || count($breadcrumbs) == 0)
        <li class="breadcrumb-item active">{{__('titles.dashboard')}}</li>
    @else
        <li class="breadcrumb-item">
            <a href="/">{{__('titles.dashboard')}}</a>
        </li>
    @endif
    @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
        @foreach($breadcrumbs as $bread)
            @if($bread->active)
                <li class="breadcrumb-item active">{{$bread->name}}</li>
            @else
                <li class="breadcrumb-item"><a href="{{$bread->url}}">{{$bread->name}}</a></li>
            @endif
        @endforeach
    @endif
</ol>