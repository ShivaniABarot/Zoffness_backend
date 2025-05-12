@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Executive Addmissions Packages Details</h4>
                </div>
                <div class="card-body">
                 
                    <div class="mb-3">
                        <strong>Package Name:</strong>
                        <p class="text-muted mb-0">{{ $ExecutivePackage->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong>Package Price:</strong>
                        <p class="text-muted mb-0">{{ $ExecutivePackage->price ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong>Package Description:</strong>
                        <p class="text-muted mb-0">{{ $ExecutivePackage->description ?? 'N/A' }}</p>
                    </div>
                 
                </div>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('executive_function_packages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
