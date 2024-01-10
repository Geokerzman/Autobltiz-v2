@include('inc.header')

<a href="{{ route('posts.index') }}" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<br>
@php
    $userGroup = session('user_group', null);
@endphp

@if ($userGroup == 2)
    <hr>
    <a href="{{ route('posts.edit', ['id' => $post->id]) }}" class="btn btn-dark">Edit</a>
    <form class="pull-right" action="{{ route('posts.destroy', ['id' => $post->id]) }}" method="post">
        @csrf
        @method('DELETE')
        <input type="submit" value="Delete" class="btn btn-danger">
    </form>
@endif

@include('inc.footer')

<div class="show-card card card-body mb-3">
    <div class="upper-bar">
        <i class="fa fa-circle" style="color: #029402"></i>
        <i class="fa fa-circle" style="color: #f1bf3f"></i>
        <i class="fa fa-circle" style="color: #b70101"></i>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                @if ($post->image_path)
                    <img src="{{ asset($post->image_path) }}" class="img-fluid" alt="Post Image">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $post->brand }}</h5>

                    @if ($userGroup == 2)
                        <h5 class="card-text mb-2 text-muted">
                            {{ $post->year }}
                        </h5>
                    @endif

                    <div class="p-2 mb-3">
                        {{ $post->model }}
                    </div>
                    <p>{{ $post->description }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
