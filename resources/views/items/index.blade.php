<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restaurant Items</title>

  <!-- ✅ Critical CSS -->
  <style>
    body { font-family: Arial, sans-serif; margin:0; padding:0; }
    #page-loader {
      position: fixed; inset: 0;
      display:flex; align-items:center; justify-content:center;
      background:#fff; z-index: 9999;
    }
    .header { background:#fff; padding:8px 12px; display:flex;
      justify-content:space-between; align-items:center;
      box-shadow:0 2px 6px rgba(0,0,0,.1);
    }
    .header img { max-width:120px; height:auto; }
  </style>

  <!-- ✅ Bootstrap CSS (blocking, needed for layout) -->
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

  <!-- ✅ Custom CSS lazy-loaded -->
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" media="print" onload="this.media='all'">
  <noscript><link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}"></noscript>
</head>
<body>

<!-- Loader -->
<div id="page-loader">
  <div class="spinner-border text-danger" role="status" style="width: 4rem; height: 4rem;"></div>
</div>

<!-- Header -->
<div class="header">
  <div class="logo">
    <img src="{{ asset('images/vijaychat.webp') }}" alt="Restaurant Logo" width="120" height="auto">
  </div>
</div>

<!-- Content -->
<div class="content">
  <div class="container">
    <h2 class="text-center mb-3">Welcome To Vijay Chaat House</h2>

    <!-- Filter Form -->
    <form id="filterForm" method="GET" class="mb-4">
      <div class="row justify-content-center">
        <div class="col-md-3 mb-2">
          <input type="text" class="form-control" name="item_desc" placeholder="Search by item name">
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

    <!-- Items -->
    <div id="itemsContainer">
      <div class="row">
        @foreach ($items as $id => $item)
        <div class="col-md-4 col-sm-6 mb-4" data-id="{{ $item->item_code }}">
          <div class="card h-100">
            <div class="position-relative">
          
              <!-- ✅ Serve optimized images instead of base64 -->
              <img src="{{ route('image.show', [$item->item_code, $item->item_grpcode]) }}"
     alt="{{ $item->item_desc }}"
     class="card-img-top"
     loading="lazy"
     width="200" height="200">

         
              <div class="veg-nonveg-icon position-absolute" style="top: 8px; left: 8px; width: 40px; height: 40px;">
                @if($item->veg_nonveg == 'V')
                  <img src="{{ asset('images/veg.png') }}" alt="Veg" style="width:100%; height:auto;" loading="lazy">
                @else
                  <img src="{{ asset('images/nonveg.png') }}" alt="Non-Veg" style="width:100%; height:auto;" loading="lazy">
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

<!-- Footer -->
<div class="footer d-none d-md-block">
  <p>&copy; 2025 Kscinfotech. All rights reserved.</p>
</div>

<!-- ✅ Mobile Cart Footer -->
<div class="mobile-cart-footer px-3 py-2 text-center"
     style="position: fixed; bottom: 0; width: 100%; background: rgba(0, 0, 0, 0.95); z-index: 1051;">
  <div class="mb-2">
    <a href="{{ route('items.cart') }}" 
       class="btn btn-danger position-relative mx-auto d-inline-flex justify-content-center align-items-center"
       style="max-width: 220px;">
      <span>Check Your Tray</span>
      <img src="{{ asset('images/cart_logo.png') }}" alt="Cart" width="50" height="auto" style="margin-left:5px;" loading="lazy">
      <span class="cart-quantity-mobile position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
        {{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}
      </span>
    </a>
  </div>

  <div class="footer-links">
    <a href="/">🏠 Home</a>
    <a href="/about">ℹ️ About</a>
    <a href="/contact">📞 Contact</a>
    <a href="/privacy">🔒 Privacy</a>
    <a href="/refund">💸 Refund</a>
  </div>
</div>

<div style="height: 120px;"></div>

<div class="footer-nav d-flex justify-content-around">
  <a href="/">🏠 Home</a>
  <a href="/about">ℹ️ About</a>
  <a href="/contact">📞 Contact</a>
  <a href="/privacy">🔒 Privacy</a>
  <a href="/refund">💸 Refund</a>
</div>

<!-- ✅ Scripts (deferred) -->
<script src="{{ asset('assets/js/jquery-3.6.0.js') }}" ></script>
<script src="{{ asset('assets/js/popper.min.js') }}" ></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}" ></script>
<script src="{{ asset('assets/js/sweetalert2@11') }}" ></script>

<script defer>
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
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      url: "{{ route('item.filter') }}",
      type: 'post',
      data: form.serialize(),
      beforeSend: function() {
        $('#itemsContainer').html('<div class="text-center w-100 py-5"><div class="spinner-border text-danger" role="status"></div></div>');
      },
      success: function (data) {
        $('#itemsContainer').html(data.html);
      },
      error: function () {
        alert('Something went wrong while filtering items.');
      }
    });
  }

  $('#filterForm').on('input change', 'input, select', function () {
    filterItems();
  });

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

      button.text('Item Added').removeClass('btn-primary').addClass('added').prop('disabled', true);

      $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function (response) {
          Swal.fire({ toast:true, position:'top-end', icon:'success', title:response.message, showConfirmButton:false, timer:3000 });
          updateCartCount(response.total_quantity ?? 0);
        },
        error: function () {
          Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Error adding item', showConfirmButton:false, timer:3000 });
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
      input.val(qty);
      visibleInput.val(qty);

      $.ajax({
        url: "{{ route('items.addToCartitem') }}",
        type: 'POST',
        data: { _token: '{{ csrf_token() }}', quantity: qty, id: id },
        success: function (response) {
          Swal.fire({ toast:true, position:'top-end', icon:'success', title:response.message, showConfirmButton:false, timer:3000 });
          updateCartCount(response.total_quantity);
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
        input.val(qty);
        visibleInput.val(qty);

        $.ajax({
          url: "{{ route('items.removeFromCart') }}",
          type: 'POST',
          data: { _token: '{{ csrf_token() }}', quantity: qty, id: id },
          success: function (response) {
            updateCartCount(response.total_quantity);
          }
        });
      } else {
        input.val(0);
        visibleInput.val(0).addClass('d-none');
        addButton.removeClass('d-none');

        $.ajax({
          url: "{{ route('items.removeFromCart') }}",
          type: 'POST',
          data: { _token: '{{ csrf_token() }}', quantity: 0, id: id },
          success: function (response) {
            updateCartCount(response.total_quantity);
          }
        });
      }
    });
  });
</script>
</body>
</html>
