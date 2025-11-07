@extends('layouts.app')
@section('title', 'View SAT/ACT Page Section')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-eye"></i> SAT/ACT Page Section Details</h2>
        <a href="{{ route('master_sat_act_page.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm p-4">
        <table class="table table-bordered align-middle">
            <tbody>
                <!-- <tr>
                    <th width="25%">Section Type</th>
                    <td>{{ $masterSATACTPage->section_type }}</td>
                </tr> -->
                <tr>
                    <th>Title</th>
                    <td>{{ $masterSATACTPage->title }}</td>
                </tr>
                <tr>
                    <th>Subtitle</th>
                    <td>{{ $masterSATACTPage->subtitle }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{!! nl2br(e($masterSATACTPage->description)) !!}</td>
                </tr>
                <tr>
                    <th>Icon</th>
                    <td>
                        @if($masterSATACTPage->icon)
                            <i class="{{ $masterSATACTPage->icon }}"></i> {{ $masterSATACTPage->icon }}
                        @else
                            —
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Image</th>
                    <td>
                        @if($masterSATACTPage->image_path)
                            <img src="{{ asset('storage/' . $masterSATACTPage->image_path) }}" width="120" height="120" class="rounded">
                        @else
                            —
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Point Text</th>
                    <td>{{ $masterSATACTPage->point_text }}</td>
                </tr>
                <tr>
                    <th>Button Text</th>
                    <td>{{ $masterSATACTPage->button_text }}</td>
                </tr>
                <tr>
                    <th>Button Link</th>
                    <td>
                        @if($masterSATACTPage->button_link)
                            <a href="{{ $masterSATACTPage->button_link }}" target="_blank">{{ $masterSATACTPage->button_link }}</a>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Order Index</th>
                    <td>{{ $masterSATACTPage->order_index }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($masterSATACTPage->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $masterSATACTPage->created_at?->format('d M Y, h:i A') }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $masterSATACTPage->updated_at?->format('d M Y, h:i A') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
