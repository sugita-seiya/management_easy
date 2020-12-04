@section('header')
  <meta charset="UTF-8">
  <!-- userログイン機能 -->
  <meta name='csrf-token' content='{{ csrf_token() }}'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- css読み込み -->
  <link rel="stylesheet" href='{{ asset("css/style.css")}}'>
  <!-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> -->
  <!-- bootstrap読み込み -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <title>勤怠管理システム</title>
  <!-- userログイン機能読み込み -->
  <script src='{{ asset("js/app.js") }}' defer></script>
@endsection