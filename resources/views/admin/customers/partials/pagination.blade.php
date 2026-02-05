@if($customers->hasPages())
    <nav>
        <ul class="pagination mb-0">
            {{-- Previous Page Link --}}
            @if ($customers->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">પાછળ</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="#" data-page="{{ $customers->currentPage() - 1 }}">પાછળ</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach(range(1, $customers->lastPage()) as $page)
                @if($page == $customers->currentPage())
                    <li class="page-item active">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="{{ $page }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($customers->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="#" data-page="{{ $customers->currentPage() + 1 }}">આગળ</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">આગળ</span>
                </li>
            @endif
        </ul>
    </nav>
@endif