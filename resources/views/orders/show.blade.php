@extends('layout')

@section('content')

<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        
        <div class="card-header bg-dark text-white p-3 rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📦 Order Details: #{{ $order->id }}</h5>
            <a href="/orders" class="btn btn-outline-light btn-sm">← Back to Orders</a>
        </div>

        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Customer Name:</p>
                    <h5 class="fw-bold">{{ $order->user->name }}</h5>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1 text-muted">Order Status:</p>
                    <span class="badge {{ $order->status == 'pending' ? 'bg-warning text-dark' : 'bg-success' }} p-2">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total Price</th> </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @foreach($order->items as $index => $item)
                            @php 
                                $rowTotal = $item->quantity * $item->price; 
                                $grandTotal += $rowTotal;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-bold text-dark">{{ $item->product->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                                <td class="text-end fw-bold">₹{{ number_format($rowTotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                            <td class="text-end fw-bold text-success" style="font-size: 1.2rem;">
                                ₹{{ number_format($grandTotal, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection