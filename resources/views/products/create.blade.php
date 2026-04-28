@extends('layout')

@section('content')

<div class="container mt-4">

    <div class="card shadow border-0 rounded-4 p-4" style="max-width:500px; margin:auto;">

        <h4 class="fw-bold mb-3 text-center">Add Product</h4>

        <a href="/products" class="btn btn-light mb-3">
            ← Back to Products
        </a>

        <form id="productForm" enctype="multipart/form-data">
        @csrf

            <div class="mb-3">
                <label class="fw-bold">Product Name</label>
                <input name="name" class="form-control" placeholder="Enter product name" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Price (₹)</label>
                <input name="price" type="number" class="form-control" placeholder="Enter price" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Stock</label>
                <input name="stock" type="number" class="form-control" placeholder="Enter stock" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Product Image</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button class="btn btn-warning w-100 fw-bold">
                Add Product
            </button>

        </form>

    </div>

</div>

@endsection