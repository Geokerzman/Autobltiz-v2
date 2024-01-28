@include('inc.header')
<head>
    <style>
        body.about-us {
            background-image: url('https://cdn.dribbble.com/userupload/5540031/file/original-3369ff2dfaab08eb2eaae6c59618da1a.jpg?resize=1504x846');
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;  /* Убедитесь, что отступы по умолчанию отсутствуют */
            padding: 0; /* Убедитесь, что отступы по умолчанию отсутствуют */
            height: 100vh; /* Установите высоту на весь видимый экран */
        }
    </style>
</head>
<body class="about-us">
<h1>{{ $title }}</h1>
<p>{{ $description }}</p>
<p>Version: <strong>{{ env('APPVERSION') }}</strong></p>
</body>
@include('inc.footer')
