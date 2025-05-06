@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Package Details</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th class="text-start">Package Name:</th>
                                    <td class="text-muted">{{ $package->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">Package Price:</th>
                                    <td class="text-muted">{{ $package->price }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">Description :</th>
                                    <td class="text-muted">{{ $package->description }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('packages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Package
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
