@extends('layout')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-bottom p-3">
                    <h5 class="mb-0 fw-bold text-dark">Edit Product Details</h5>
                </div>

                <div class="card-body p-4">
                    <form id="updateForm" data-id="{{ $product->id }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Product Name</label>
                            <input type="text" name="name" class="form-control form-control-lg fs-6" 
                                   value="{{ $product->name }}" placeholder="e.g. Apple iPhone 15 Pro" required>
                            <small class="text-muted">Include brand, model and key specs.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-secondary">Price (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" name="price" class="form-control" 
                                           value="{{ $product->price }}" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-secondary">Stock Quantity</label>
                                <input type="number" name="stock" class="form-control" 
                                       value="{{ $product->stock }}" placeholder="Enter available units" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Product Image</label>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div style="width: 80px; height: 80px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background: #f9f9f9;">
                                    @if($product->image)
                                        <img src="{{ asset('uploads/'.$product->image) }}" style="width:100%; height:100%; object-fit:contain;">
                                    @else
                                        <img src="https://via.placeholder.com/80" style="width:100%; height:100%; object-fit:contain;">
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="image" class="form-control">
                                    <small class="text-muted">Leave empty to keep the current image.</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="d-flex gap-3">
                            <a href="/products" class="btn btn-light border flex-grow-1 fw-bold">Cancel</a>
                            <button type="submit" class="btn btn-warning flex-grow-1 fw-bold shadow-sm" style="background-color: #fb641b; border: none; color: white;">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection