@include('inc.header')
<a href="{{ route('posts.index') }}" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>

<div class="card card-body mb-3 mt-3">
    <h2>Add Post</h2>
    <p>Create a post with this form</p>

    <form action="{{ route('posts.add') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Title: <sup>*</sup></label>
            <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ old('title') }}">
            @error('title')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="brand">Марка: <sup>*</sup></label>
            <select name="brand" class="form-control form-control-lg @error('brand') is-invalid @enderror">
                @foreach ($brands as $brand)
                    <option value="{{ $brand }}" {{ (old('brand') == $brand) ? 'selected' : '' }}>{{ $brand }}</option>
                @endforeach
            </select>
            @error('brand')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="model">Модель: <sup>*</sup></label>
            <input type="text" name="model" class="form-control form-control-lg @error('model') is-invalid @enderror" value="{{ old('model') }}">
            @error('model')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Описание: <sup>*</sup></label>
            <textarea name="description" class="form-control form-control-lg @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="year">Год: <sup>*</sup></label>
            <select name="year" class="form-control form-control-lg @error('year') is-invalid @enderror">
                @foreach ($years as $year)
                    <option value="{{ $year }}" {{ (old('year') == $year) ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
            @error('year')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </div>

        <button type="submit" class="btn btn-success">Send</button>
    </form>
</div>

@include('inc.footer')
