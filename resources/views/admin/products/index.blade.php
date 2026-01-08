@extends('layouts.admin')

@section('title', 'Manage Products')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-box-open"></i> Products Management</h2>
                <p class="text-muted mb-0">Manage your product inventory and pricing</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add New Product
            </a>
        </div>
    </div>

    <!-- Products Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Products</h6>
                    <h3 class="mb-0">{{ $products->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-muted mb-2">In Stock</h6>
                    <h3 class="mb-0 text-success">{{ $products->where('stock', '>', 0)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Low Stock</h6>
                    <h3 class="mb-0 text-warning">{{ $products->whereBetween('stock', [1, 10])->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Out of Stock</h6>
                    <h3 class="mb-0 text-danger">{{ $products->where('stock', 0)->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list"></i> All Products</h5>
                <span class="badge bg-primary">{{ $products->total() }} Total</span>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($products->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No products found</h4>
                    <p class="text-muted mb-4">Start building your product catalog by adding your first product</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Your First Product
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;" class="text-center">ID</th>
                                <th style="width: 100px;">Image</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center" style="width: 200px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $product->id }}</span>
                                    </td>
                                    <td>
                                        <div style="width: 70px; height: 70px; background-color: #f8f9fa; border: 1px solid #dee2e6; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                            @if($product->image)
                                                <img src="{{ $product->image }}" 
                                                     alt="{{ $product->name }}" 
                                                     style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                <i class="fas fa-image text-muted" style="display: none;"></i>
                                            @else
                                                <i class="fas fa-image text-muted"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        @if($product->description)
                                            <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $product->category->name }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">₹{{ number_format($product->price, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($product->stock > 10)
                                            <span class="badge bg-success">{{ $product->stock }} units</span>
                                        @elseif($product->stock > 0)
                                            <span class="badge bg-warning text-dark">{{ $product->stock }} units</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="btn btn-sm btn-warning me-1" 
                                           title="Edit Product">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.products.delete', $product->id) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Delete Product">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if($products->isNotEmpty())
            <div class="card-footer bg-white">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                        <span class="text-muted">
                            Showing <strong>{{ $products->firstItem() }}</strong> to <strong>{{ $products->lastItem() }}</strong> of <strong>{{ $products->total() }}</strong> products
                        </span>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Products pagination">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
