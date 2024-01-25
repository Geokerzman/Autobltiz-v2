@include('inc.header')

<div class="container mt-3">
    <!-- Filters Display -->
    <form action="{{ route('posts.index') }}" method="get" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <label for="brand" class="form-label">Марка: <sup>*</sup></label>
                <select name="brand" id="allBrands" class="form-select form-control-lg">
                    <option value="" selected>Выберите марку</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand }}" {{ ($brand == $brand) ? 'selected' : '' }}>{{ $brand }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="model" class="form-label">Модель: <sup>*</sup></label>
                <select name="model" id="model" class="form-select form-control-lg">
                    <option value="" selected>Выберите модель</option>
                    @foreach ($models as $model)
                        <option value="{{ $model }}" {{ ($model == $model) ? 'selected' : '' }}>{{ $model }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="year" class="form-label">Год: <sup>*</sup></label>
                <select name="year" id="year" class="form-select form-control-lg">
                    <option value="" selected>Выберите год</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ ($year == $year) ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success btn-lg">Применить фильтры</button>
            </div>
        </div>
    </form>

    <!-- Display posts regarding the search results -->
    @forelse ($posts as $post)
        <div class="card mb-3 border border-2 rounded-3">
            <div class="card-body">
                <div class="upper-bar">
                    @foreach(['#f4655a', '#f39c12', '#3498db'] as $color)
                        <i class="fas fa-circle" style="color: {{ $color }}"></i>
                    @endforeach
                </div>
                <h4 class="card-title text-primary">{{ $post->title }}</h4>
                <div class="mb-3 text-muted">
                    Written by {{ $post->user->name }} on {{ $post->created_at }}
                </div>
                <div class="mb-3">{{ $post->brand }}</div>
                <div class="mb-3">{{ $post->model }}</div>
                <div class="mb-3">{{ $post->year }}</div>
                @if ($post->image_path)
                    <img src="{{ asset($post->image_path) }}" class="img-fluid mb-3" alt="Post Image">
                    <p class="card-text">{{ $post->description }}</p>
                @endif
                <a href="{{ route('posts.show', ['post' => $post->id]) }}" class="btn btn-dark">More</a>
            </div>
        </div>
    @empty
        <p>No posts found</p>
    @endforelse

    <!-- Pages separation -->
</div>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('posts.add') }}" class="btn btn-danger btn-lg">
                <i class="fas fa-pencil-alt"></i> Add Post
            </a>
        </div>
    </div>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        @if ($currentPage > 1)
            <li class="page-item">
                <a class="page-link" href="{{ route('posts.index', ['page' => $currentPage - 1]) }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        @endif

        @for ($i = 1; $i <= $totalPages; $i++)
            <li class="page-item {{ ($i == $currentPage) ? 'active' : '' }}">
                <a class="page-link" href="{{ route('posts.index', ['page' => $i]) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($currentPage < $totalPages)
            <li class="page-item">
                <a class="page-link" href="{{ route('posts.index', ['page' => $currentPage + 1]) }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        @endif
    </ul>
</nav>

@include('inc.footer')
