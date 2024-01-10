@include('inc.header')
<a href="{{ route('posts.index') }}" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>

<div class="card card-body bg-light mt-5">
    <h2>Edit Post</h2>
    <p>Edit a post with this form</p>

    <form action="{{ route('posts.edit', ['id' => $id]) }}" method="post">
        @csrf
        <div class="form-group">
            <label for="title">Title: <sup>*</sup></label>
            <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ $title }}">
            @error('title')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="body">Body: <sup>*</sup></label>
            <textarea name="body" class="form-control form-control-lg @error('body') is-invalid @enderror">{{ $body }}</textarea>
            @error('body')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <input type="submit" class="btn btn-success" value="Submit">
    </form>
</div>

@include('inc.footer')
