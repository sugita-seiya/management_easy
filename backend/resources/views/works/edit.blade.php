@extends('layouts.works')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center" style="background:#e9e1de;">{{('出退勤') }}</div>

                    <div class="card-body text-center">
                    {{ Form::model('$contact_id',['route' =>['work.update',$contact_id->id]]) }}
                        @method('PUT')
                        <div class="work-day mb-3">
                          {{$today_date[0]}}年{{$today_date[1]}}日{{$today_date[2]}}日({{$today_date[3]}})
                        </div>
                        <div class="work-time mb-3">
                          <h1>
                            {{$today_date[4]}}
                          </h1>
                        </div>
                        <div class="work-form mb-3">
                          <button type="button" class="btn text-white pr-4 pl-4" style="background: #ef7709;">出勤</button>
                          <button type="button" class="btn btn-secondary pr-4 pl-4">退勤</button>
                        </div>
                      {{ Form::close() }}
                    </div>
                        <!-- <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('メールアドレス') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('パスワード') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('パスワード記憶') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4 mb-2">
                                    <button type="submit" class="btn text-white pr-5 pl-5" style="background: #ef7709;">
                                        {{ __('ログイン') }}
                                    </button>
                                </div>
                                <div class="col-md-8 offset-md-4">
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}" style="color: #ef7709;">
                                            {{ __('パスワードを忘れた方はこちら') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form> -->
                </div>
            </div>
        </div>
    </div>
@endsection