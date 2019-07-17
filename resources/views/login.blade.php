@extends('layouts.main')
@section('content')
<div class="row">
    <div class="md-col-6 offset-md-4">
        @if(isset($message))
            @if(isset($success) && $success)
                <div class="alert alert-success" role="alert">
                  {{$message}}
                </div>
            @else
                <div class="alert alert-danger" role="alert">
                  {{$message}}
                </div>
            @endif
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="form-signup form" method="post" action="{{url('/login')}}">
            @csrf
          <h1 class="h3 mb-3 font-weight-normal">Login here</h1>
          <label for="inputEmail" class="sr-only">Email address</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required="" >
          <label for="inputPassword" class="sr-only">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Password" required="">
          <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        </form>
        @if (Route::has('register'))
            <a href="{{ route('register') }}">Register</a>
        @endif
    </div>
</div>
@endsection
