@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Session Details</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th class="text-start">Session Title:</th>
                                    <td class="text-muted">{{ $session->title }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">Session Type:</th>
                                    <td class="text-muted">{{ $session->session_type }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">Session Price:</th>
                                    <td class="text-muted">{{ $session->price_per_slot }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('sessions') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Session
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
