@extends('layouts.app')

@section('content')
    <div class="container text-center mt-5">
        <h1 class="text-success">Payment Successful!</h1>
        <p>Thank you for registering for the exam. We will contact you soon with further details.</p>
        <a href="{{ route('exam.form') }}" class="btn btn-primary">Go Back to Form</a>
    </div>
@endsection
