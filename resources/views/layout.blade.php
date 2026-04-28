<!DOCTYPE html>
<html>

<head>
    <title>ORDER SYSTEM</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
        }

        .main {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 230px;
            background: #111;
            color: #fff;
            padding: 20px;
        }

        .sidebar h4 {
            color: orange;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            margin-bottom: 8px;
            color: #ddd;
            text-decoration: none;
            border-radius: 8px;
        }

        .sidebar a:hover {
            background: orange;
            color: #fff;
        }

        .content {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
        }

        .product-card {
            border-radius: 15px;
            transition: 0.3s;
            background: #fff;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .product-card img {
            height: 220px;
            object-fit: contain;
        }
    </style>
</head>

<body>

<div class="main">

    <div class="sidebar">
        <h4>🛒 Shop Admin</h4>
        <a href="/products"><i class="bi bi-box"></i> Products</a>
        <a href="/orders"><i class="bi bi-cart"></i> Orders</a>
        <a href="/users"><i class="bi bi-people"></i> Users</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;


let productForm = document.getElementById("productForm");

if(productForm){
    productForm.addEventListener("submit", function(e){
        e.preventDefault();

        let formData = new FormData(this);

        fetch("/products", {
            method: "POST",
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.status){
                Swal.fire("Success","Product Added","success")
                .then(()=> location.href="/products");
            }else{
                Swal.fire("Error","Add failed","error");
            }
        });
    });
}


let updateForm = document.getElementById("updateForm");

if(updateForm){
    updateForm.addEventListener("submit", function(e){
        e.preventDefault();

        let id = this.dataset.id;
        let formData = new FormData(this);

        fetch("/products/"+id, {
            method: "POST",
            body: formData,
            headers:{
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.status){
                Swal.fire("Updated!","Success","success")
                .then(()=> location.href="/products");
            }else{
                Swal.fire("Error","Update failed","error");
            }
        });
    });
}


function deleteProduct(id, btn){

    Swal.fire({
        title:"Delete product?",
        icon:"warning",
        showCancelButton:true
    }).then(res => {

        if(res.isConfirmed){

            fetch("/products/"+id,{
                method:"POST",
                headers:{
                    'X-CSRF-TOKEN': csrf,
                    'X-HTTP-Method-Override':'DELETE',
                    'Accept':'application/json'
                }
            })
            .then(res=>res.json())
            .then(data=>{
                if(data.status){

                    Swal.fire("Deleted!","","success");

                    let card = btn.closest(".col-md-3") 
                             || btn.closest(".card");

                    if(card) card.remove();
                }else{
                    Swal.fire("Error","Delete failed","error");
                }
            });
        }
    });
}


let searchInput = document.getElementById("searchInput");

if(searchInput){
    searchInput.addEventListener("keyup", function(){

        let query = this.value;

        fetch("/search-products?search="+query)
        .then(res=>res.text())
        .then(data=>{
            let list = document.getElementById("productList");
            if(list){
                list.innerHTML = data;
            }
        });
    });
}
</script>

</body>
</html>