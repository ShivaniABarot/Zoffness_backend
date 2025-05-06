@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Payment</h1>
        <form action="{{ route('payments.update', $payment->id) }}" method="POST">
            @csrf
            {{-- @method('PUT') <!-- This is for update requests --> --}}
            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select name="payment_method" class="form-control" required>
                    <option value="Credit Card" {{ $payment->payment_method == 'Credit Card' ? 'selected' : '' }}>Credit Card
                    </option>
                    <option value="PayPal" {{ $payment->payment_method == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                    <option value="UPI" {{ $payment->payment_method == 'UPI' ? 'selected' : '' }}>UPI</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="package_id" class="form-label">Package</label>
                <select name="package_id" class="form-control">
                    <option value="">None</option>
                    @foreach ($packages as $package)
                        <option value="{{ $package->id }}" {{ $payment->package_id == $package->id ? 'selected' : '' }}>
                            {{ $package->name }} (${{ $package->price }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="session_id" class="form-label">Session</label>
                <select name="session_id" class="form-control">
                    <option value="">None</option>
                    @foreach ($sessions as $session)
                        <option value="{{ $session->id }}" {{ $payment->session_id == $session->id ? 'selected' : '' }}>
                            {{ $session->title }} (${{ $session->price_per_slot }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" name="amount" class="form-control" value="{{ $payment->amount }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
