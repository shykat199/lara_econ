@extends('admin.layout.app')

@section('title', 'Products')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-20 mb-0">Products</p>
                <p class="fs-13 text-muted mb-0">Manage your product catalog.</p>
            </div>
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-wave">
                <i class="ri-add-line align-middle me-1"></i> Add Product
            </a>
        </div>

        <div class="card custom-card">
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>
                                        @if ($product->image)
                                            <img
                                                src="{{ asset(\Illuminate\Support\Facades\Storage::url($product->image)) }}"
                                                alt="{{ $product->name }}"
                                                class="rounded border"
                                                style="width: 44px; height: 44px; object-fit: cover;"
                                            >
                                        @else
                                            <div class="rounded border d-flex align-items-center justify-content-center bg-light text-muted" style="width: 44px; height: 44px;">
                                                <i class="ri-image-line"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $product->name }}
                                        <div class="fs-11 text-muted">{{ $product->slug }}</div>
                                    </td>
                                    <td><span class="badge bg-light text-dark">{{ $product->sku }}</span></td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success-transparent' : 'bg-danger-transparent' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-icon btn-light" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-light text-danger" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
