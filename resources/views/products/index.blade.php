@extends('layout')

@section('content')

<style>
.img-container {
    width: 100%;
    height: 230px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
}
.img-container img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.product-card {
    border-radius: 12px;
    text-align: center;
    transition: 0.3s;
    padding: 10px;
}
.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.product-title {
    font-size: 15px;
    font-weight: 600;
    margin-top: 12px;
    margin-bottom: 6px;
}

.price-text {
    font-size: 18px;
    font-weight: 700;
    color: #000;
    margin-bottom: 10px;
}

.row.gap-fix {
    row-gap: 25px;
}

.pagination .page-link {
    border-radius: 8px;
    margin: 0 4px;
}
.pagination .active .page-link {
    background: orange;
    border-color: orange;
    color: #fff;
}
</style>

<div class="d-flex justify-content-between mb-3">
    <h4 class="fw-bold">Product Catalog</h4>
    <a href="/products/create" class="btn btn-primary">+ Add Product</a>
</div>

<input type="text" id="searchInput" class="form-control mb-4" placeholder="Search product...">

<div id="productList">

<div class="row gap-fix">

@foreach($products as $product)
<div class="col-md-3">

    <div class="card product-card shadow-sm">

        <div class="img-container">
            @if($product->image)
                <img src="{{ asset('uploads/'.$product->image) }}">
            @else
                <img src="https://via.placeholder.com/200">
            @endif
        </div>

        <div class="card-body">

            <div class="product-title">
                {{ $product->name }}
            </div>

            <div class="price-text">
                ₹{{ number_format($product->price) }}
            </div>

            <div class="d-flex justify-content-center gap-2">
                <a href="/products/{{ $product->id }}/edit" class="btn btn-sm btn-outline-primary">Edit</a>
                <button onclick="deleteProduct({{ $product->id }}, this)" class="btn btn-sm btn-outline-danger">Delete</button>
            </div>

        </div>

    </div>

</div>
@endforeach

</div>

</div>

<nav class="mt-5">
    <ul class="pagination justify-content-center">

        @if ($products->onFirstPage())
            <li class="page-item disabled"><span class="page-link">«</span></li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $products->previousPageUrl() }}">«</a>
            </li>
        @endif

        @for ($i = 1; $i <= $products->lastPage(); $i++)
            <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($products->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $products->nextPageUrl() }}">»</a>
            </li>
        @else
            <li class="page-item disabled"><span class="page-link">»</span></li>
        @endif

    </ul>
</nav>

@endsection