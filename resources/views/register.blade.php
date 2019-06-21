@extends('layouts.main')
@section('content')
<div class="row">
    <div class="md-col-6 offset-md-4">
        <form class="form-signup form">
          <h1 class="h3 mb-3 font-weight-normal">Register</h1>
          <label for="inputEmail" class="sr-only">Name</label>
          <input type="text" id="name" class="form-control" placeholder="Name" required="" >
          <label for="inputEmail" class="sr-only">Email address</label>
          <input type="email" id="email" class="form-control" placeholder="Email address" required="" autofocus="">
          <label for="inputPassword" class="sr-only">Password</label>
          <input type="password" id="password" class="form-control" placeholder="Password" required="">

          <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
        </form>
    </div>
</div>
@endsection
