<nav aria-label="breadcrumb" class="comparison-breadcrumb">
    <ol class="breadcrumb container">
        <li class="breadcrumb-item"><a href="{{action('PagesController@home')}}">{{ __('pages.home') }}</a></li>
        @if(isset($bread_list) && count($bread_list) > 0)
            @foreach($bread_list as $item)
                @if($item->active)
                    <li class="breadcrumb-item active" aria-current="page">{{ $item->cle_name }}</li>
                @else
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ action('PagesController@category', $item->cle_url) }}">{{ $item->cle_name }}</a></li>
                @endif
            @endforeach
        @endif

        @if(isset($bread_product) && count($bread_product) > 0)
            @foreach($bread_product as $bread)
                @if($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $bread->name }}</li>
                @else
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ action('PagesController@category', $bread->url) }}">{{ $bread->name }}</a></li>
                @endif
            @endforeach
        @endif

        @if(isset($breads) && count($breads) > 0)
            @foreach($breads as $bread)
                @if($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $bread->name }}</li>
                @else
                    <li class="breadcrumb-item" aria-current="page"><a href="{{$bread->url}}">{{ $bread->name }}</a></li>
                @endif
            @endforeach
        @endif
    </ol>
</nav>