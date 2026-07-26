@extends('admin.layout.app')

@section('title', 'Edit Product')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">Edit Product</p>
                <p class="fs-13 text-muted mb-0">Update product details.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-light btn-wave">
                <i class="ri-arrow-left-line align-middle me-1"></i> Back
            </a>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('product::_form', ['product' => $product, 'categories' => $categories])

                            <button type="submit" class="btn btn-primary btn-wave">
                                <i class="ri-save-line align-middle me-1"></i> Update Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
