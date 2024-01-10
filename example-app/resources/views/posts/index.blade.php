@include('inc.header')

<div class="wrapping row mb-3">
    <!-- Filters Display -->

    <form action="{{ route('posts.index') }}" method="get">
        <div class="row">
            <div class="col-xs-4 m-2">
                <label for="brand" class="form-label">Марка: <sup>*</sup></label>
                <select name="brand" id="allBrands" class="form-select form-control-lg">
                    <option value="" selected>Выберите марку</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand }}" {{ ($brand == $brand) ? 'selected' : '' }}>{{ $brand }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xs-4 m-2">
                <label for="model" class="form-label">Модель: <sup>*</sup></label>
                <select name="model" id="model" class="form-select form-control-lg">
                    <option value="" selected>Выберите модель</option>
                    @foreach ($models as $model)
                        <option value="{{ $model }}" {{ ($model == $model) ? 'selected' : '' }}>{{ $model }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xs-4 m-2">
                <label for="year" class="form-label">Год: <sup>*</sup></label>
                <select name="year" id="year" class="form-select form-control-lg">
                    <option value="" selected>Выберите год</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ ($year == $year) ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xs-4 m-2 s_input">
                <button type="submit" class="btn btn-success">Применить фильтры</button>
            </div>
        </div>
    </form>

    <!-- Display posts regarding the search results -->
    @forelse ($posts as $post)
        <div class="card card-body mb-3">
            <div class="card card-body mb-3">
                <div class="upper-bar">
                    <i class="fa fa-circle" style="color: #029402"></i>
                    <i class="fa fa-circle" style="color: #f1bf3f"></i>
                    <i class="fa fa-circle" style="color: #b70101"></i>
                </div>
                <h4 class="card-title">{{ $post->title }}</h4>
                <div class="p-2 mb-3">
                    Written by {{ $post->user->name }} on {{ $post->created_at }}
                </div>
                <div class="p-2 mb-3">
                    {{ $post->brand }}
                </div>
                <div class="p-2 mb-3">
                    {{ $post->model }}
                </div>
                <div class="p-2 mb-3">
                    {{ $post->year }}
                </div>
                @if ($post->image_path)
                    <img src="{{ asset($post->image_path) }}" class="img-fluid" alt="Post Image">
                    <p class="card-text">{{ $post->description }}</p>
                @endif
                <a href="{{ route('posts.show', ['id' => $post->id]) }}" class="btn btn-dark">More</a>
            </div>
        </div>
    @empty
        <p>No posts found</p>
    @endforelse

    <!-- Pages separation -->

</div>
<div class="row mb-3 mx-auto">
    <div class="col-md-6">
        <a href="{{ route('posts.add') }}" class="btn btn-danger pull-right justify-content-end">
            <i class="fa fa-pencil"></i> Add Post
        </a>
    </div>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        @if ($currentPage > 1)
            <li class="page-item">
                <a class="page-link" href="{{ route('posts.index', ['page' => $currentPage - 1]) }}">Previous</a>
            </li>
        @endif

        @for ($i = 1; $i <= $totalPages; $i++)
            <li class="page-item {{ ($i == $currentPage) ? 'active' : '' }}">
                <a class="page-link" href="{{ route('posts.index', ['page' => $i]) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($currentPage < $totalPages)
            <li class="page-item">
                <a class="page-link" href="{{ route('posts.index', ['page' => $currentPage + 1]) }}">Next</a>
            </li>
        @endif
    </ul>
</nav>

@include('inc.footer')
