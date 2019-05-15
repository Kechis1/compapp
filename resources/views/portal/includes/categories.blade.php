<nav id="sidebar-nav" class="float-left">
    <ul class="list-group">
        @if(count($categories) > 0)
            @foreach($categories as $category)
                <li class="list-group-item"><h3><a href="/category/{{$category->cle_url}}">{{ $category->cle_name }}</a></h3>
                    @if(isset($category->items))
                        <ul class="nav list-inline">
                            @foreach($category->items as $cat)
                                <li class="mr-15"><a href="/category/{{$cat->cle_url}}">{{ $cat->cle_name }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        @else
            <div class="alert alert-primary" role="alert">
                {{ ucfirst(__('alerts.categories')) . ' ' . __('alerts.not_found') }}
            </div>
        @endif
    </ul>
</nav>