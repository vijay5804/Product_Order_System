@extends('layout')

@section('content')
<div class="d-flex flex-column" style="height: calc(100vh - 60px); overflow: hidden;">
    
    <div class="row g-3 mb-3 flex-shrink-0">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <label class="form-label fw-bold text-muted small mb-2 uppercase">
                        <i class="bi bi-person-circle me-1 text-warning"></i> Customer Information
                    </label>
                    <select name="user_id" id="user_id" class="form-select border-0 bg-light shadow-none fw-bold" required>
                        <option value="">-- Select Customer --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div id="selectionBox" class="card border-0 shadow-sm rounded-4 h-100 d-none" style="background: #fffdf5; border-left: 5px solid #ffc107 !important;">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center mb-1 px-2">
                        <span class="fw-bold small text-warning">LIVE SELECTION</span>
                        <span class="badge bg-dark rounded-pill" id="itemCount">0 Items</span>
                    </div>
                    <div id="selectedItemsList" class="d-flex overflow-auto gap-3 pb-2 px-2" style="scrollbar-width: thin;">
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 flex-grow-1 d-flex flex-column mb-3" style="min-height: 0;">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark">📦 Product Catalog</h5>
            <div class="input-group input-group-sm w-25">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                <input type="text" id="productSearch" class="form-control bg-light border-0 shadow-none" placeholder="Search product...">
            </div>
        </div>

        <form id="orderForm" class="d-flex flex-column flex-grow-1" style="min-height: 0;">
            @csrf
            <div class="table-responsive flex-grow-1" style="overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light sticky-top" style="z-index: 5;">
                        <tr class="text-muted small uppercase">
                            <th class="ps-4" style="width: 60px;">Select</th>
                            <th>Product Details</th>
                            <th>Unit Price</th>
                            <th style="width: 160px;" class="text-center">Quantity</th>
                            <th class="text-end pe-4">Line Total</th>
                        </tr>
                    </thead>
                    <tbody id="productBody">
                        @foreach($products as $product)
                        <tr class="product-row" data-id="{{ $product->id }}">
                            <td class="ps-4">
                                <input type="checkbox" class="product-check form-check-input shadow-none" style="width: 1.2rem; height: 1.2rem; cursor: pointer;">
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="product-name fw-bold text-dark">{{ $product->name }}</span>
                                    <span class="small text-muted mt-1">
                                        Stock: <span class="stock-val fw-bold" data-original="{{ $product->stock }}">{{ $product->stock }}</span>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-bold text-dark">₹<span class="price">{{ $product->price }}</span></div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="btn-group btn-group-sm border rounded-pill p-1 bg-white shadow-sm">
                                        <button class="btn btn-link text-dark btn-minus border-0 p-0 mx-1" type="button" disabled style="width:24px; height:24px;">
                                            <i class="bi bi-dash-circle-fill"></i>
                                        </button>
                                        <input type="number" class="qty form-control form-control-sm text-center fw-bold border-0 bg-transparent p-0 shadow-none" 
                                               value="1" min="1" disabled style="width: 45px;">
                                        <button class="btn btn-link text-dark btn-plus border-0 p-0 mx-1" type="button" disabled style="width:24px; height:24px;">
                                            <i class="bi bi-plus-circle-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <span class="fw-bold text-primary">₹<span class="row-total">0.00</span></span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-dark text-white border-0 py-3 px-4 d-flex justify-content-between align-items-center rounded-bottom-4">
                <div class="d-flex flex-column">
                    <span class="small text-secondary fw-bold uppercase">Grand Total</span>
                    <h2 class="mb-0 fw-bold text-warning">₹<span id="grandTotal">0.00</span></h2>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div id="paginationControls"></div>
                    <button type="submit" class="btn btn-warning fw-bold px-5 py-2 rounded-pill shadow-sm border-0 text-dark">
                        <i class="bi bi-cart-check-fill me-1"></i> PLACE ORDER
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
input[type=number] {
    appearance: textfield;
}

.selection-item-card {
    background: white;
    border-radius: 10px;
    padding: 10px;
}

.selection-item-card:hover {
    transform: translateY(-2px);
}

.remove-item {
    cursor: pointer;
}

.page-link {
    background: transparent;
    border: none;
}

.active .page-link {
    color: orange;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function(){
    const allRows = Array.from(document.querySelectorAll('.product-row'));
    const selectedItemsList = document.getElementById('selectedItemsList');
    const grandTotalEl = document.getElementById('grandTotal');
    const itemCountEl = document.getElementById('itemCount');
    const selectionBox = document.getElementById('selectionBox');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    let rowsPerPage = 5;
    let currentPage = 1;
    let filteredRows = [...allRows];

    function refreshAll() {
        let grandTotal = 0;
        let count = 0;
        selectedItemsList.innerHTML = "";
        let hasSelection = false;

        allRows.forEach(row => {
            const checkbox = row.querySelector('.product-check');
            const qtyInput = row.querySelector('.qty');
            const price = parseFloat(row.querySelector('.price').textContent) || 0;
            const stockValEl = row.querySelector('.stock-val');
            const originalStock = parseInt(stockValEl.dataset.original);
            const rowTotalEl = row.querySelector('.row-total');

            if (checkbox.checked) {
                hasSelection = true;
                count++;
                qtyInput.disabled = false;
                row.querySelector('.btn-plus').disabled = false;
                row.querySelector('.btn-minus').disabled = false;

                let qty = parseInt(qtyInput.value) || 1;
                if(qty > originalStock) { qty = originalStock; qtyInput.value = qty; }
                if(qty < 1) { qty = 1; qtyInput.value = 1; }
                
                stockValEl.textContent = originalStock - qty;
                let rowTotal = qty * price;
                rowTotalEl.textContent = rowTotal.toFixed(2);
                grandTotal += rowTotal;

                // Create Card UI with BOTH prices
                const name = row.querySelector('.product-name').textContent;
                let card = document.createElement('div');
                card.className = "selection-item-card shadow-sm border";
                card.innerHTML = `
                    <div class="remove-item" onclick="unselectItem('${row.dataset.id}')"><i class="bi bi-x-circle-fill"></i></div>
                    <div class="fw-bold small text-dark text-truncate mb-2" style="max-width: 160px;">${name}</div>
                    
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Unit Price:</span>
                        <span class="fw-bold text-dark">₹${price.toFixed(2)}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>Qty:</span>
                        <span class="badge bg-warning text-dark px-2">${qty}</span>
                    </div>
                    <div class="border-top pt-2 d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-secondary">Total:</span>
                        <span class="text-primary fw-bold">₹${rowTotal.toFixed(2)}</span>
                    </div>`;
                selectedItemsList.appendChild(card);
            } else {
                qtyInput.disabled = true;
                row.querySelector('.btn-plus').disabled = true;
                row.querySelector('.btn-minus').disabled = true;
                rowTotalEl.textContent = "0.00";
                stockValEl.textContent = originalStock;
            }
        });

        grandTotalEl.textContent = grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2});
        itemCountEl.textContent = `${count} Items`;
        selectionBox.classList.toggle('d-none', !hasSelection);
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
        const controls = document.getElementById('paginationControls');
        controls.innerHTML = "";
        let pageCount = Math.ceil(filteredRows.length / rowsPerPage);
        if(pageCount <= 1) return;
        let ul = document.createElement('ul');
        ul.className = "pagination mb-0 me-3";
        for(let i=1; i<=pageCount; i++) {
            let li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link">${i}</a>`;
            li.onclick = () => displayTable(i);
            ul.appendChild(li);
        }
        controls.appendChild(ul);
    }

    document.getElementById('productBody').addEventListener('click', (e) => {
        let row = e.target.closest('tr');
        if(!row) return;
        let q = row.querySelector('.qty');
        if(e.target.closest('.btn-plus')) { q.value = parseInt(q.value) + 1; refreshAll(); }
        if(e.target.closest('.btn-minus')) { if(parseInt(q.value) > 1) { q.value = parseInt(q.value) - 1; refreshAll(); } }
    });

    document.getElementById('productBody').addEventListener('input', (e) => { if(e.target.classList.contains('qty')) refreshAll(); });
    document.getElementById('productBody').addEventListener('change', (e) => { if(e.target.classList.contains('product-check')) refreshAll(); });
    document.getElementById('productSearch').addEventListener('keyup', function(){
        let k = this.value.toLowerCase();
        filteredRows = allRows.filter(r => r.querySelector('.product-name').textContent.toLowerCase().includes(k));
        displayTable(1);
    });

    // --- FORM SUBMIT LOGIC ---
    document.getElementById('orderForm').addEventListener('submit', function(e){
        e.preventDefault();
        let selected = [];
        allRows.forEach(row => {
            if(row.querySelector('.product-check').checked) 
                selected.push({ id: row.dataset.id, qty: row.querySelector('.qty').value });
        });

        if(!selected.length) return Swal.fire("Empty Cart", "Select at least one product", "warning");
        if(!document.getElementById('user_id').value) return Swal.fire("No Customer", "Please select a customer", "warning");

        Swal.fire({
            title: 'Place Order?',
            text: "Are you sure you want to confirm this order?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'Yes, Confirm',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("/orders", {
                    method: "POST",
                    headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ user_id: document.getElementById('user_id').value, products: JSON.stringify(selected) })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status){
                        Swal.fire("Placed!", "Your order has been successful.", "success")
                        .then(() => window.location.href = "/orders");
                    } else {
                        Swal.fire("Error", "Could not place order.", "error");
                    }
                });
            }
        });
    });

    displayTable(1);
});
</script>
@endsection