@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Exam Registration Form</h2>

        <form id="examForm" class="needs-validation" action="{{ route('exam.checkout') }}" method="POST" novalidate>
            @csrf

            <!-- Course & Category -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Course and Category</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="course">Select Course:</label>
                            <select id="course" class="form-select" name="course" required>
                                <option value="">Select Course</option>
                                <option value="SAT">SAT</option>
                                <option value="ACT">ACT</option>
                                <option value="SAT/ACT">SAT/ACT</option>
                            </select>
                            <div class="invalid-feedback">Please select a course.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category">Select Category:</label>
                            <select id="category" class="form-select" name="category" required>
                                <option value="">Select Category</option>
                                <option value="coaching">Coaching</option>
                                <option value="exam">Exam</option>
                                <option value="consulting">Consulting</option>
                            </select>
                            <div class="invalid-feedback">Please select a category.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exam and Time -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Exam Details</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="select2-dropdown">Select Exam:</label>
                            <select id="select2-dropdown" class="form-select" name="select2[]" multiple required>
                                <option value="diagnostic">Diagnostic SAT/ACT test</option>
                                <option value="enrichment">Enrichment classes</option>
                                <option value="practice">Practice test and analysis</option>
                                <option value="counseling">College admission counseling</option>
                                <option value="essays">College Essays</option>
                                <option value="executive">Executive function coaching</option>
                            </select>
                            <div class="invalid-feedback">Please select at least one exam.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="exam-time">Select Exam Time:</label>
                            <select id="exam-time" class="form-select" name="exam_time[]" multiple required>
                                <option value="regular">Full-Length Proctored Diagnostic SAT/ACT with Regular Time</option>
                                <option value="extended">Full-Length Proctored Diagnostic SAT/ACT with 50% Extended Time</option>
                                <option value="practice-regular">Full-Length Proctored Practice SAT Test with Regular Time</option>
                                <option value="practice-extended">Full-Length Proctored Practice SAT Test with 50% Extended Time</option>
                            </select>
                            <div class="invalid-feedback">Please select at least one exam time.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent Details -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Parent Details</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="parent_first_name">Parent First Name:</label>
                            <input type="text" id="parent_first_name" class="form-control" name="parent_first_name" required>
                            <div class="invalid-feedback">Please enter the parent's first name.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="parent_last_name">Parent Last Name:</label>
                            <input type="text" id="parent_last_name" class="form-control" name="parent_last_name" required>
                            <div class="invalid-feedback">Please enter the parent's last name.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="parent_email">Parent Email:</label>
                            <input type="email" id="parent_email" class="form-control" name="parent_email" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="parent_phone">Parent Phone:</label>
                            <input type="text" id="parent_phone" class="form-control" name="parent_phone" required>
                            <div class="invalid-feedback">Please enter a valid phone number.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Details -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Student Details</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_first_name">Student First Name:</label>
                            <input type="text" id="student_first_name" class="form-control" name="student_first_name" required>
                            <div class="invalid-feedback">Please enter the student's first name.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="student_last_name">Student Last Name:</label>
                            <input type="text" id="student_last_name" class="form-control" name="student_last_name" required>
                            <div class="invalid-feedback">Please enter the student's last name.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_email">Student Email:</label>
                            <input type="email" id="student_email" class="form-control" name="student_email" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review and Submit -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Review Registration</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price">Price:</label>
                            <input type="text" id="price" class="form-control" name="price" value="100" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="total">Total:</label>
                            <input type="text" id="total" class="form-control" name="total" value="100" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Credit Card Details -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Credit Card Details</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bank_name">Bank Name:</label>
                            <input type="text" id="bank_name" class="form-control" name="bank_name" required>
                            <div class="invalid-feedback">Please enter the bank name.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="card_number">Card Number:</label>
                            <input type="text" id="card_number" class="form-control" name="card_number" required>
                            <div class="invalid-feedback">Please enter a valid card number.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="expiration_date">Expiration Date:</label>
                            <input type="month" id="expiration_date" class="form-control" name="expiration_date" required>
                            <div class="invalid-feedback">Please enter the expiration date.</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="security_code">Security Code:</label>
                            <input type="text" id="security_code" class="form-control" name="security_code" required>
                            <div class="invalid-feedback">Please enter the security code.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="country">Country:</label>
                            <select id="country" class="form-select" name="country" required>
                                <option value="">Select Country</option>
                                <option value="US">United States</option>
                                <option value="CA">Canada</option>
                                <option value="UK">United Kingdom</option>
                                <option value="AU">Australia</option>
                                <option value="IN">India</option>
                            </select>
                            <div class="invalid-feedback">Please select a country.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-success w-60">Proceed to Payment</button>
            </div>
        </form>
    </div>

    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');
    </script>
@endsection
