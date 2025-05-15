@extends('layouts.app')

@section('content')
    <div class="container text-center mt-5">
        <h1 class="text-danger">Payment Cancelled!</h1>
        <p>Your payment was not completed. Please try again.</p>
        <a href="{{ route('exam.form') }}" class="btn btn-primary">Go Back to Form</a>
    </div>
@endsection
