@extends('layouts.master-without-nav')

@section('content')
    <div class="jumbotron text-center">
        <h1>Welcome to Business Directory</h1>
        <p>Find the best businesses in your area!</p>
        <a href="{{ route('login') }}" class="btn btn-green-border">LogIn to View</a>
    </div>
@endsection
