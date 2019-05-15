<div id="shopPagination" class="pagination-wrapper">
    @if($pages > 1)
        <nav class="pages">
            <ul class="pagination">
                {{--$first, $current, $prev, $next, $last--}}
                @if($pagination[0]!==null)
                    <li class="page-item"><a class="page-link" href="?page={{$pagination[0]}}">{{ $pagination[0] }}</a></li>
                @endif
                @if($pagination[2]!==null)
                    <li class="page-item"><a class="page-link" href="?page={{$pagination[2]}}">{{ $pagination[2] }}</a></li>
                @endif
                <li class="page-item active"><a class="page-link" href="#">{{ $pagination[1] }}</a></li>
                @if($pagination[3]!==null)
                    <li class="page-item"><a class="page-link" href="?page={{$pagination[3]}}">{{ $pagination[3] }}</a></li>
                @endif
                @if($pagination[4]!==null)
                    <li class="page-item"><a class="page-link" href="?page={{$pagination[4]}}">{{ $pagination[4] }}</a></li>
                @endif
            </ul>
        </nav>
    @endif
</div>