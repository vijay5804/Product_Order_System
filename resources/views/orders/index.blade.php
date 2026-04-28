@extends('layout')

@section('content')

<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4 p-3">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 fw-bold">📦 Orders List</h4>
            <a href="/orders/create" class="btn btn-warning rounded-3 fw-bold shadow-sm">
                <i class="bi bi-plus-circle"></i> Create Order
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>S.No</th>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($orders as $order)
                    <tr id="order-row-{{ $order->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold text-primary">#{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>

                        <!-- 🔥 STATUS -->
                        <td>
                            <span 
                                class="badge status-toggle p-2 
                                {{ $order->status == 'pending' ? 'bg-warning text-dark' : 'bg-success' }}"
                                style="cursor:pointer"
                                data-id="{{ $order->id }}"
                                data-status="{{ $order->status }}">
                                
                                <i class="bi {{ $order->status == 'pending' ? 'bi-clock-history' : 'bi-check2-all' }}"></i>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <td>
                            <button onclick="deleteOrder({{ $order->id }}, this)" class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>

<script>
const csrf = '{{ csrf_token() }}';

/* ================= DELETE ================= */

function deleteOrder(id, btn){

    Swal.fire({
        title:"Delete?",
        icon:"warning",
        showCancelButton:true
    }).then(res=>{

        if(res.isConfirmed){

            fetch("/orders/"+id,{
                method:"DELETE",
                headers:{
                    'X-CSRF-TOKEN': csrf,
                    'Accept':'application/json'
                }
            })
            .then(res=>res.json())
            .then(data=>{
                if(data.status){
                    btn.closest("tr").remove();
                    Swal.fire("Deleted!","","success");
                }
            });
        }
    });
}

/* ================= STATUS CHANGE ================= */

document.querySelectorAll('.status-toggle').forEach(el => {

    el.addEventListener('click', function(){

        let id = this.dataset.id;
        let currentStatus = this.dataset.status;

        // 🔥 ALREADY COMPLETED CHECK
        if(currentStatus === 'completed'){
            Swal.fire({
                icon: "info",
                title: "Already Completed",
                text: "This order is already completed"
            });
            return;
        }

        // 🔥 CONFIRM POPUP
        Swal.fire({
            title:"Change status?",
            text:"Mark as completed?",
            icon:"question",
            showCancelButton:true
        }).then(res=>{

            if(res.isConfirmed){

                fetch("/orders/"+id+"/status",{
                    method:"POST",
                    headers:{
                        'X-CSRF-TOKEN': csrf,
                        'X-HTTP-Method-Override':'PUT',
                        'Content-Type':'application/json',
                        'Accept':'application/json'
                    },
                    body: JSON.stringify({
                        status:"completed"
                    })
                })
                .then(res=>res.json())
                .then(data=>{

                    if(data.status){

                        // 🔥 UPDATE UI + DATA
                        el.dataset.status = "completed";

                        el.innerHTML = `
                            <i class="bi bi-check2-all"></i> Completed
                        `;

                        el.classList.remove('bg-warning','text-dark');
                        el.classList.add('bg-success');

                        Swal.fire("Updated!","","success");
                    }

                });

            }

        });

    });

});
</script>

@endsection