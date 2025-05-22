@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Student Details:</h5>
            <a href="{{ route('student') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
        </div>
        <div class="card-body">
            <!-- Student Information -->
            <div class="mb-4">
                <h6 class="fw-bold">Student Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Student Name:</strong> {{ $student->student_name }}</p>
                        <p><strong>Student Email:</strong> {{ $student->student_email ?? 'N/A' }}</p>
                        <p><strong>School:</strong> {{ $student->school ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Parent Name:</strong> {{ $student->parent_name }}</p>
                        <p><strong>Parent Email:</strong> {{ $student->parent_email }}</p>
                        <p><strong>Parent Phone:</strong> {{ $student->parent_phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Booked Exams -->
            <div class="mb-4">
                <h6 class="fw-bold">Booked Exams</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Practice Test</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 0; @endphp
                            @foreach($student->collegeAdmissions as $exam)
                                @if($exam->status == 'booked')
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>College Admission</td>
                                        <td>{{ $exam->packages ?? 'N/A' }}</td>
                                        <td>{{ $exam->created_at ? $exam->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $exam->subtotal ? '$' . number_format($exam->subtotal, 2) : 'N/A' }}</td>
                                        <td><span class="badge bg-info">Booked</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($student->collegeEssays as $essay)
                                @if($essay->status == 'booked')
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>College Essay</td>
                                        <td>{{ $essay->packages ?? 'N/A' }}</td>
                                        <td>{{ $essay->created_at ? $essay->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>N/A</td> <!-- College Essays has no amount field -->
                                        <td><span class="badge bg-info">Booked</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($student->enrollments as $enrollment)
                                @if($enrollment->status == 'booked')
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>Enrollment</td>
                                        <td>{{ $enrollment->packages ?? 'N/A' }}</td>
                                        <td>{{ $enrollment->created_at ? $enrollment->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $enrollment->total_amount ? '$' . number_format($enrollment->total_amount, 2) : 'N/A' }}</td>
                                        <td><span class="badge bg-info">Booked</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($student->satActCourseRegistrations as $course)
                                @if($course->status == 'booked')
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>SAT/ACT Course</td>
                                        <td>{{ $course->package_name ?? 'N/A' }}</td>
                                        <td>{{ $course->created_at ? $course->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $course->amount ? '$' . number_format($course->amount, 2) : 'N/A' }}</td>
                                        <td><span class="badge bg-info">Booked</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($student->practiceTests as $test)
                                @if($test->status == 'booked')
                                    @php
                                        $testDate = null;
                                        try {
                                            $testDate = $test->date ? \Carbon\Carbon::parse($test->date) : null;
                                        } catch (\Exception $e) {
                                            $testDate = null; // Handle invalid date format
                                        }
                                    @endphp
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>Practice Test</td>
                                        <td>{{ $test->test_type ?? 'N/A' }}</td>
                                        <td>{{ $testDate ? $testDate->format('M d, Y') : ($test->created_at ? $test->created_at->format('M d, Y') : 'N/A') }}</td>
                                        <td>{{ $test->subtotal ? '$' . number_format($test->subtotal, 2) : 'N/A' }}</td>
                                        <td><span class="badge bg-info">Booked</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($student->executiveFunctionCoaching as $coaching)
                                @if($coaching->status == 'booked')
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>Executive Function Coaching</td>
                                        <td>{{ $coaching->package_type ?? 'N/A' }}</td>
                                        <td>{{ $coaching->created_at ? $coaching->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $coaching->subtotal ? '$' . number_format($coaching->subtotal, 2) : 'N/A' }}</td>
                                        <td><span class="badge bg-info">Booked</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @if($counter == 0)
                                <tr>
                                    <td colspan="6" class="text-center py-3">No booked exams found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Attempted Exams -->
            <div class="mb-4">
                <h6 class="fw-bold">Attempted Exams</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Practice Test</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 0; @endphp
                            @foreach($student->collegeAdmissions as $exam)
                                @if($exam->status == 'completed')
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>College Admission</td>
                                        <td>{{ $exam->packages ?? 'N/A' }}</td>
                                        <td>{{ $exam->created_at ? $exam->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $exam->subtotal ? '$' . number_format($exam->subtotal, 2) : 'N/A' }}</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($student->collegeEssays as $essay)
                                @if($essay->status == 'completed')
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>College Essay</td>
                                        <td>{{ $essay->packages ?? 'N/A' }}</td>
                                        <td>{{ $essay->created_at ? $essay->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>N/A</td> <!-- College Essays has no amount field -->
                                        <td><span class="badge bg-success">Completed</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($student->enrollments as $enrollment)
                                @if($enrollment->status == 'completed')
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>Enrollment</td>
                                        <td>{{ $enrollment->packages ?? 'N/A' }}</td>
                                        <td>{{ $enrollment->created_at ? $enrollment->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $enrollment->total_amount ? '$' . number_format($enrollment->total_amount, 2) : 'N/A' }}</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($student->satActCourseRegistrations as $course)
                                @if($course->status == 'completed')
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>SAT/ACT Course</td>
                                        <td>{{ $course->package_name ?? 'N/A' }}</td>
                                        <td>{{ $course->created_at ? $course->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $course->amount ? '$' . number_format($course->amount, 2) : 'N/A' }}</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($student->practiceTests as $test)
                                @if($test->status == 'completed')
                                    @php
                                        $testDate = null;
                                        try {
                                            $testDate = $test->date ? \Carbon\Carbon::parse($test->date) : null;
                                        } catch (\Exception $e) {
                                            $testDate = null; // Handle invalid date format
                                        }
                                    @endphp
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>Practice Test</td>
                                        <td>{{ $test->test_type ?? 'N/A' }}</td>
                                        <td>{{ $testDate ? $testDate->format('M d, Y') : ($test->created_at ? $test->created_at->format('M d, Y') : 'N/A') }}</td>
                                        <td>{{ $test->subtotal ? '$' . number_format($test->subtotal, 2) : 'N/A' }}</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach($student->executiveFunctionCoaching as $coaching)
                                @if($coaching->status == 'completed')
                                    @php $counter++; @endphp
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>Executive Function Coaching</td>
                                        <td>{{ $coaching->package_type ?? 'N/A' }}</td>
                                        <td>{{ $coaching->created_at ? $coaching->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $coaching->subtotal ? '$' . number_format($coaching->subtotal, 2) : 'N/A' }}</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                    </tr>
                                @endif
                            @endforeach
                            @if($counter == 0)
                                <tr>
                                    <td colspan="6" class="text-center py-3">No completed exams found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection