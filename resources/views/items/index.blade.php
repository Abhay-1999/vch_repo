<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Items</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
         #page-loader {
            position: fixed;
            z-index: 9999;
            background: #fff;
            top: 0; left: 0; right: 0; bottom: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        body {
            /* background-image: url('images/directdine.png'); */
            background-color:#f38534; !important;
            background-size: cover;
            background-position: center;
            color: #fff;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }
        .header, .footer {
            background-color:rgb(0 0 0 / 0%);;
            padding: 15px;
            text-align: center;
            position: fixed;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .header { top: 0; }
        .footer { bottom: 0; }
        .content {
            padding-top: 100px;
            padding-bottom: 70px;
            overflow-y: auto;
            height: calc(100vh - 100px);
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-title, .card-text { color: #000; }
        .added {
            background-color: #28a745;
            color: white;
        }
        button.btn.btn-primary.add-to-cart-button {
            background-color: #ed323d !important;
            border-color: #eb3240 !important;
        }
        .cart-icon img { width: 50px; }
        .cart-quantity {
            position: absolute;
            top: -8px;
            right: -10px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
        .mobile-cart-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.85);
            padding: 10px;
            z-index: 1050;
        }
        .mobile-cart-footer a.btn {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .cart-quantity-mobile {
            top: -5px;
            right: 20px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
        /* @media (max-width: 768px) {
            .header, .footer { padding: 10px; }
            .logo img { width: 100px; }
            .card-title { font-size: 1.1rem; }
            .card-text { font-size: 0.95rem; }
            .card-img-top { height: 150px; }
        }
        @media (max-width: 576px) {
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        } */

        button.increase-quantity,
button.decrease-quantity {
    font-size: 24px;
    padding: 12px;
    line-height: 1;
    min-width: 60px;
    min-height: 60px;
}

    </style>
</head>
<body>
<div id="page-loader">
    <div class="spinner-border text-danger" role="status" style="width: 4rem; height: 4rem;">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="header d-flex justify-content-between align-items-center px-3">
    <div class="logo">
        <img src="images/vijaychat.webp" alt="Restaurant Logo" style="width: 120px;">
    </div>
    <!-- <div class="cart-icon position-relative" style="margin-right:25px !important;">
        <a href="{{ route('items.cart') }}">
            <img src="images/cart.webp" alt="Cart">
            <span class="cart-quantity">
                {{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}
            </span>
        </a>
    </div> -->
</div>
<div class="content">
    <div class="container">
        <h1 class="text-center mb-3">Welcome To Vijay Chaat House</h1>
        <!-- <h2 class="text-center mb-4">Menu</h2> -->

        <!-- 🔽 Filter Form Start -->
        <form id="filterForm" method="GET" class="mb-4">
        <div class="row justify-content-center">
             <div class="col-md-3 mb-2">
                <input type="text" class="form-control" name="item_desc" value="" placeholder="Search by item name">
                   
            </div>
            <div class="col-md-3 mb-2">
                <select name="veg_nonveg" class="form-control">
                    <option value="">All Types</option>
                    <option value="V">Veg</option>
                    <option value="N">Non-Veg</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <select name="item_grpdesc" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($item_grpcodes as $item_grpcode)
                    <option value="{{ $item_grpcode->item_grpcode }}">{{ $item_grpcode->item_grpdesc }}</option>
                     @endforeach
                </select>
            </div>
   
        </div>
    </form>
    <div id="itemsContainer">
        <div class="row">
            @foreach ($items as $id => $item)
            <div class="col-md-4 col-sm-6 mb-4" data-id="{{ $item->item_code }}">
                <div class="card h-100">
                    <?php
                    $image = App\Models\Item::where('item_code', $item->item_code)
                                ->where('item_grpcode', $item->item_grpcode)
                                ->first();
                    ?>
                    <!-- Container for positioning the overlay icon -->
                    <div class="position-relative">
                        <img src="data:{{ $image->item_image_type }};base64,{{ base64_encode($image->item_image) }}" class="card-img-top" alt="{{ $item->name }}">
                        <div class="veg-nonveg-icon position-absolute" style="top: 8px; left: 8px; width: 40px; height: 40px;">
                            @if($item->veg_nonveg == 'V')
                                <img src="{{ asset('images/veg.png') }}" alt="Veg" style="width: 100%; height: auto;">
                            @else
                                <img src="{{ asset('images/nonveg.png') }}" alt="Non-Veg" style="width: 100%; height: auto;">
                            @endif
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $item->item_desc }}</h5>
                        <p class="card-text">Price: ₹ {{ number_format($item->item_rate, 2) }}</p>
                        <form action="{{ route('items.addToCart', $item->item_code) }}" method="POST" class="add-to-cart-form">
                            <input type="hidden" name="item_grpcode" value="{{ $item->item_grpcode }}">
                            @csrf
                            <button class="btn btn-sm btn-success increase-quantity" type="button">+</button>
                            <button type="submit" class="btn btn-primary add-to-cart-button" disabled>0</button>
                            <input type="button" class="quantity-input d-none" value="0">
                            <input type="hidden" class="btn btn-primary quantity-input qty" name="quantity" value="0">
                            <button class="btn btn-sm btn-danger decrease-quantity" type="button">-</button>
                        </form>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
        </div>
    </div>
</div>
<div class="footer d-none d-md-block">
    <p>&copy; 2025 Kscinfotech. All rights reserved.</p>
</div>
<div class="mobile-cart-footer px-3 py-2 text-center">
    <a href="{{ route('items.cart') }}" 
       class="btn btn-danger position-relative mx-auto d-inline-flex justify-content-center align-items-center"
       style="max-width: 200px;">
        
        <span>Check Your Tray</span>
        <img src="images/cart_logo.png" alt="Cart" style="width: 55px; margin-left: 5px;">
        
        <span class="cart-quantity-mobile position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
            {{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}
        </span>
    </a>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    window.addEventListener('load', function () {
    const loader = document.getElementById('page-loader');
    if (loader) {
        loader.style.transition = 'opacity 0.5s ease';
        loader.style.opacity = 0;
        setTimeout(() => loader.remove(), 500);
    }
    });


 function filterItems() {
    let form = $('#filterForm');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        url: "{{ route('item.filter') }}",
        type: 'post', // still using POST for dynamic filtering
        data: form.serialize(),
        beforeSend: function() {
            $('#itemsContainer').html('<div class="text-center w-100 py-5"><div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div></div>');
        },
        success: function (data) {
            $('#itemsContainer').html(data.html);
        },
        error: function () {
            alert('Something went wrong while filtering items.');
        }
    });
}

// Call filter when any form input or select changes
$('#filterForm').on('input change', 'input, select', function () {
    filterItems();
});




</script>

<script>

        


$(document).ready(function () {
        function updateCartCount(totalQuantity) {
            $('.cart-quantity').text(totalQuantity);
            $('.cart-quantity-mobile').text(totalQuantity);
        }

        $('.add-to-cart-form').on('submit', function (event) {
            event.preventDefault();
            const form = $(this);
            const button = form.find('.add-to-cart-button');
            const row = form.closest('[data-id]');
            const id = row.data('id');

            button.text('Item Added');
            button.removeClass('btn-primary').addClass('added');
            button.prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 3000
                });
                    updateCartCount(response.total_quantity ?? 0);
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 3000
                });
                }
            });
        });

        $('.increase-quantity').on('click', function () {
            const wrapper = $(this).closest('.card-body');
            const input = wrapper.find('.qty');
            const visibleInput = wrapper.find('.quantity-input').not('.qty');
            const addButton = wrapper.find('.add-to-cart-button');
            const row = $(this).closest('[data-id]');
            const id = row.data('id');

            addButton.addClass('d-none');
            visibleInput.removeClass('d-none');

            let qty = parseInt(input.val()) || 0;
            qty += 1;

            // 🔄 Immediately update the UI
            input.val(qty);
            visibleInput.val(qty);

            // 🔁 Fire the request in background
            $.ajax({
                url: "{{ route('items.addToCartitem') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: qty,
                    id: id
                },
                success: function (response) {
                    Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 3000
                });
                    updateCartCount(response.total_quantity);
                },
                error: function (xhr) {
                    const errorMessage = xhr.responseJSON?.message || 'Something went wrong!';
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error', // ✅ make sure this is set
                        title: errorMessage,
                        showConfirmButton: false,
                        timer: 3000
                    });
}
            });
        });

        $('.decrease-quantity').on('click', function () {
            const wrapper = $(this).closest('.card-body');
            const input = wrapper.find('.qty');
            const visibleInput = wrapper.find('.quantity-input').not('.qty');
            const addButton = wrapper.find('.add-to-cart-button');
            const row = $(this).closest('[data-id]');
            const id = row.data('id');

            let qty = parseInt(input.val()) || 0;

            if (qty > 1) {
                qty -= 1;

                // Update UI immediately
                input.val(qty);
                visibleInput.val(qty);

                // Update quantity on server
                $.ajax({
                    url: "{{ route('items.removeFromCart') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: qty,
                        id: id
                    },
                    success: function (response) {
                        updateCartCount(response.total_quantity);
                    },
                    error: function () {
                        alert('Failed to decrease quantity.');
                    }
                });
            } else {
                // Quantity will become 0 — remove item
                input.val(0);
                visibleInput.val(0).addClass('d-none');
                addButton.removeClass('d-none');

                // Remove item from cart on server
                $.ajax({
                    url: "{{ route('items.removeFromCart') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: 0,
                        id: id
                    },
                    success: function (response) {
                        updateCartCount(response.total_quantity);
                    },
                    error: function () {
                        alert('Failed to remove item from cart.');
                    }
                });
            }
        });
    });
</script>
</body>
</html>
