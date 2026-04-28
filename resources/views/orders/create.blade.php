@extends('layout')

@section('content')
<div class="container mt-3" style="max-height: 100vh; overflow: hidden;">
    
    <div id="selectionBox" class="card shadow-sm border-0 rounded-4 mb-3 d-none" style="border-left: 5px solid #ffc107 !important;">
        <div class="card-header bg-white fw-bold py-2 border-0">Current Selection</div>
        <div class="card-body py-2">
            <div id="selectedItemsList" class="d-flex flex-wrap gap-2" style="max-height: 80px; overflow-y: auto;">
                </div>
        </div>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0 fw-bold">Create Order</h6>
            <input type="text" id="productSearch" class="form-control form-control-sm w-25 shadow-none" placeholder="Search products...">
        </div>

        <form id="orderForm">
            @csrf
            <div class="card-body p-0">
                <div class="px-3 pt-3">
                    <select name="user_id" id="user_id" class="form-select form-select-sm border-2 mb-3 shadow-none" required>
                        <option value="">-- Select Customer --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="table-responsive" style="height: 320px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-3">Select</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th style="width: 140px;">Qty</th>
                                <th class="text-end pe-3">Total</th>
                            </tr>
                        </thead>
                        <tbody id="productBody">
                        @foreach($products as $product)
                        <tr class="product-row" data-id="{{ $product->id }}">
                            <td class="ps-3">
                                <input type="checkbox" class="product-check form-check-input shadow-sm">
                            </td>
                            <td>
                                <span class="product-name fw-bold d-block small">{{ $product->name }}</span>
                                <small class="text-muted">Stock: <span class="stock-val" data-original="{{ $product->stock }}">{{ $product->stock }}</span></small>
                            </td>
                            <td class="small">₹<span class="price">{{ $product->price }}</span></td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <button class="btn btn-outline-secondary btn-minus" type="button" disabled>−</button>
                                    <input type="number" class="qty form-control text-center fw-bold shadow-none" value="1" min="1" disabled style="max-width: 40px;">
                                    <button class="btn btn-outline-secondary btn-plus" type="button" disabled>+</button>
                                </div>
                            </td>
                            <td class="text-end pe-3 fw-bold text-primary small">₹<span class="row-total">0.00</span></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center py-2 bg-light border-top" id="paginationControls"></div>
            </div>

            <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0 fw-bold">Grand Total: <span class="text-success">₹<span id="grandTotal">0.00</span></span></h4>
                <button type="submit" class="btn btn-warning fw-bold px-5 shadow border-0">Place Order</button>
            </div>
        </form>
    </div>
</div>

<style>
    .page-link { border: none; padding: 5px 12px; cursor: pointer; color: #333; font-weight: bold; font-size: 14px; }
    .active .page-link { background: #ffc107 !important; border-radius: 4px; color: #000 !important; }
    input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    .sticky-top { top: 0; z-index: 10; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function(){
    const allRows = Array.from(document.querySelectorAll('.product-row'));
    const productBody = document.getElementById('productBody');
    const paginationControls = document.getElementById('paginationControls');
    const grandTotalEl = document.getElementById('grandTotal');
    const selectionBox = document.getElementById('selectionBox');
    const selectedItemsList = document.getElementById('selectedItemsList');

    let rowsPerPage = 5;
    let currentPage = 1;
    let filteredRows = [...allRows];

    
    function refreshAll() {
        let grandTotal = 0;
        selectedItemsList.innerHTML = "";
        let hasSelected = false;

        allRows.forEach(row => {
            const checkbox = row.querySelector('.product-check');
            const qtyInput = row.querySelector('.qty');
            const price = parseFloat(row.querySelector('.price').textContent) || 0;
            const stockValEl = row.querySelector('.stock-val');
            const originalStock = parseInt(stockValEl.dataset.original);
            const rowTotalEl = row.querySelector('.row-total');

            if (checkbox.checked) {
                hasSelected = true;
                qtyInput.disabled = false;
                row.querySelector('.btn-plus').disabled = false;
                row.querySelector('.btn-minus').disabled = false;

                let qty = parseInt(qtyInput.value) || 1;
                if(qty > originalStock) { qty = originalStock; qtyInput.value = qty; }
                
                stockValEl.textContent = originalStock - qty;
                let rowTotal = qty * price;
                rowTotalEl.textContent = rowTotal.toFixed(2);
                grandTotal += rowTotal;

                const name = row.querySelector('.product-name').textContent;
                let badge = document.createElement('div');
                badge.className = "btn btn-sm btn-warning d-flex align-items-center gap-2 fw-bold rounded-pill px-2";
                badge.innerHTML = `<span>${name} x${qty}</span><i class="bi bi-x-circle-fill" onclick="unselectItem('${row.dataset.id}')"></i>`;
                selectedItemsList.appendChild(badge);
            } else {
                qtyInput.disabled = true;
                row.querySelector('.btn-plus').disabled = true;
                row.querySelector('.btn-minus').disabled = true;
                rowTotalEl.textContent = "0.00";
                stockValEl.textContent = originalStock;
            }
        });

        grandTotalEl.textContent = grandTotal.toFixed(2);
        selectionBox.classList.toggle('d-none', !hasSelected);
    }

    window.unselectItem = (id) => {
        const row = document.querySelector(`.product-row[data-id="${id}"]`);
        row.querySelector('.product-check').checked = false;
        refreshAll();
    };

    function displayTable(page) {
        currentPage = page;
        let start = (page - 1) * rowsPerPage;
        let end = start + rowsPerPage;

        allRows.forEach(r => r.style.display = "none");
        filteredRows.slice(start, end).forEach(r => r.style.display = "");
        
        setupPagination();
    }

    function setupPagination() {
        paginationControls.innerHTML = "";
        let pageCount = Math.ceil(filteredRows.length / rowsPerPage);
        if(pageCount <= 1) return;

        let ul = document.createElement('ul');
        ul.className = "pagination mb-0";
        for(let i=1; i<=pageCount; i++) {
            let li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link shadow-none">${i}</a>`;
            li.onclick = () => displayTable(i);
            ul.appendChild(li);
        }
        paginationControls.appendChild(ul);
    }

    productBody.addEventListener('click', (e) => {
        let row = e.target.closest('tr');
        if(!row) return;
        let q = row.querySelector('.qty');
        if(e.target.classList.contains('btn-plus')) { q.value = parseInt(q.value) + 1; refreshAll(); }
        if(e.target.classList.contains('btn-minus')) { if(q.value > 1) q.value = parseInt(q.value) - 1; refreshAll(); }
    });

    productBody.addEventListener('change', (e) => { if(e.target.classList.contains('product-check')) refreshAll(); });

    document.getElementById('productSearch').addEventListener('keyup', function(){
        let k = this.value.toLowerCase();
        filteredRows = allRows.filter(r => r.querySelector('.product-name').textContent.toLowerCase().includes(k));
        displayTable(1);
    });

    displayTable(1);

    document.getElementById('orderForm').addEventListener('submit', function(e){
        e.preventDefault();
        let selected = [];
        allRows.forEach(row => {
            if(row.querySelector('.product-check').checked) 
                selected.push({ id: row.dataset.id, qty: row.querySelector('.qty').value });
        });
        if(!selected.length) return alert("Select products");
        
        fetch("/orders", {
            method: "POST",
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: document.getElementById('user_id').value, products: JSON.stringify(selected) })
        }).then(res => res.json()).then(data => { if(data.status) location.href = "/orders"; });
    });
});
</script>
@endsection