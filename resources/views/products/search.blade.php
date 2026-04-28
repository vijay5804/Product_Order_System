<div class="row">

@foreach($products as $product)

<div class="col-md-3 mb-4">
    <div class="card product-card">

        @if($product->image)
            <img src="{{ asset('uploads/'.$product->image) }}">
        @else
            <img src="https://via.placeholder.com/300x250">
        @endif

        <div class="card-body">
            <h6>{{ $product->name }}</h6>
            <span>₹{{ $product->price }}</span>
        </div>

    </div>
</div>

@endforeach

@if($products->count() == 0)
    <p class="text-danger">No products found</p>
@endif

</div>