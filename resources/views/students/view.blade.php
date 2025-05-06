@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Student Details</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th class="text-start">Parent Name:</th>
                                <td class="text-muted">{{ $student->parent_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">Parent Phone No.:</th>
                                <td class="text-muted">{{ $student->parent_phone }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">Parent Email:</th>
                                <td class="text-muted">{{ $student->parent_email }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">Student Name:</th>
                                <td class="text-muted">{{ $student->student_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">Student Email:</th>
                                <td class="text-muted">{{ $student->student_email }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">School:</th>
                                <td class="text-muted">{{ $student->school }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">Bank Name:</th>
                                <td class="text-muted">{{ $student->bank_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">Account Number:</th>
                                <td class="text-muted">{{ $student->account_number }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('student') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Student
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
