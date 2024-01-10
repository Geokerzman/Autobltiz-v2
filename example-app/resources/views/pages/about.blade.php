@include('inc.header')
<h1>{{ $title }}</h1>
<p>{{ $description }}</p>
<p>Version: <strong>{{ env('APPVERSION') }}</strong></p>
@include('inc.footer')
