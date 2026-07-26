@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">

        {{-- Page header --}}
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">Dashboard</p>
                <p class="fs-13 text-muted mb-0">Overview of your content and media library.</p>
            </div>
        </div>

    </div>
@endsection
