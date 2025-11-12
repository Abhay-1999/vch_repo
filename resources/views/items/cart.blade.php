<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Cart</title>
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
  <style>
    /* Suggested Items Slider */
    .suggested-section {
      margin-top: 40px;
    }
    .suggested-title {
      font-weight: bold;
      margin-bottom: 10px;
      text-align: center;
    }
    .suggested-slider {
      display: flex;
      overflow-x: auto;
      scroll-behavior: smooth;
      padding: 10px 0;
      gap: 10px;
    }
    .suggested-slider::-webkit-scrollbar {
      height: 6px;
    }
    .suggested-slider::-webkit-scrollbar-thumb {
      background-color: #ccc;
      border-radius: 4px;
    }
    .suggested-item {
      min-width: 180px;
      border: 1px solid #ddd;
      border-radius: 10px;
      background: #fff;
      flex-shrink: 0;
      padding: 10px;
      text-align: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: transform 0.2s ease-in-out;
    }
    .suggested-item:hover {
      transform: scale(1.05);
    }
    .suggested-item img {
      width: 100%;
      height: 110px;
      object-fit: cover;
      border-radius: 6px;
    }
    .suggested-item h6 {
      margin: 8px 0 5px;
      font-size: 0.9rem;
      font-weight: 600;
    }
    .suggested-item p {
      font-size: 0.85rem;
      margin-bottom: 6px;
      color: #333;
    }
    .add-cart-btn {
      font-size: 0.8rem;
      padding: 4px 10px;
    }
    .header img {
      display: block;
      margin: 10px auto;
    }
    /* .footer {
      margin-top: 50px;
      padding: 10px 0;
      background: #f9f9f9;
    } */
    
  </style>

<!-- Toastr JS -->
  
</head>
<body>
  <div class="header">
    <img src="images/vijaychat.webp" alt="Restaurant Logo" style="width: 130px;" />
  </div>

  <div class="content">
    <div class="container">
      @if(session('cart') && count(session('cart')) > 0)

        <!-- Desktop Table -->
        <div class="table-container">
          <table class="table table-bordered table-striped bg-white text-dark">
            <thead class="thead-dark">
              <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Action</th> {{-- 👈 new column --}}

              </tr>
            </thead>
            <tbody>
              @foreach(session('cart') as $id => $item)
              <tr data-id="{{ $id }}">
                <td>{{ $item['name'] }}</td>
                <td>₹ {{ number_format($item['price'], 2) }}</td>
                <td class="Itemquantity">{{ $item['quantity'] }}</td>
                <td class="item-total">₹ {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                <td>
                  <button class="btn btn-danger btn-sm remove-item" data-id="{{ $id }}">
                    🗑 Remove
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-cart">
          @foreach(session('cart') as $id => $item)
          <div class="card-item" data-id="{{ $id }}">
          <div class="d-flex">
            <h6>{{ $item['name'] }}</h6>
            <p>Price: ₹ {{ number_format($item['price'], 2) }}</p>
            </div>
            <div class="d-flex">
            <p>Qty: <span class="quantity">{{ $item['quantity'] }}</span></p>
            <p>Total: <span class="item-total">₹ {{ number_format($item['price'] * $item['quantity'], 2) }}</span></p>
            <button class="btn btn-outline-danger btn-sm remove-item" data-id="{{ $id }}">
            🗑
          </button>
            </div>
          
          </div>
          @endforeach
        </div>

        <div class="text-center mt-3 paymode_mode" style="max-width: 300px; margin: 0 auto; text-align: center;">
        <form id="orderForm" action="{{ route('initiate.payment') }}" method="POST">
          @csrf

          <input type="hidden" class="total" name="amount" name="total_amount" value="" />
          <input type="hidden" name="paymode_mode" value="O">

  
          <span class="text-dark font-weight-bolder"> ₹ </span><span class="totalamt text-dark font-weight-bolder"> 0.00</span>
          <br>
          <h1 class="text-black font-weight-bold mb-1" style="font-size: 0.85rem;">
          Once you proceed with payment, it cannot be Refund or Cancel.
        </h1>
        <button type="submit" class="btn btn-primary btn-sm mt-2" id="payNowBtn" style="font-size: 0.8rem; padding: 4px 12px;">
            Pay Now
        </button>
      </form>


        </div>
      @else
        <p class="text-center">Your plate is empty!.</p>
      @endif
      @if(isset($suggestedItems) && count($suggestedItems) > 0)
      <div class="suggested-section">
        <h5 class="suggested-title">🍽 Suggested Items</h5>
        <div class="suggested-slider">
          @foreach($suggestedItems as $item)
          <div class="suggested-item">
            <img src="{{ route('image.show', [$item->item_code, $item->item_grpcode]) }}" 
                 alt="{{ $item->item_desc }}">
            <h6>{{ $item->item_desc }}</h6>
            <p>₹ {{ number_format($item->item_rate, 2) }}</p>
            <button class="btn btn-success btn-sm add-cart-btn"
                    data-id="{{ $item->item_code }}"
                    data-name="{{ $item->item_desc }}"
                    data-price="{{ $item->item_rate }}">
              + Add to Cart
            </button>
          </div>
          @endforeach
        </div>
      </div>
      @endif
      <div class="text-center mt-4">
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Back to Menu</a>
      </div>
    </div>
  </div>

  <div class="footer">
    <p>&copy; 2025 Kscinfotech. All rights reserved.</p>
  </div>

  <script src="{{ asset('assets/js/jquery-3.6.0.js') }}"></script>
  <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
  <script src="{{ asset('assets/js/popper.min.js') }}" ></script>

  <script src="{{ asset('assets/js/sweetalert2@11') }}" ></script>

  
  <script>
    // 🔹 Remove item from cart
$(document).on('click', '.remove-item', function () {
  const id = $(this).data('id');
  const row = $(this).closest('[data-id]');

  Swal.fire({
    title: 'Remove item?',
    text: 'Do you want to remove this from your cart?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, remove it',
    cancelButtonText: 'Cancel',
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "{{ route('cart.remove') }}",
        type: "POST",
        data: { _token: '{{ csrf_token() }}', id: id },
        success: function (response) {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: response.message,
            showConfirmButton: false,
            timer: 1000
          });
          // ✅ Remove row visually
          row.fadeOut(300, function () {
            $(this).remove();
            // Reload totals or page if cart becomes empty
            setTimeout(() => location.reload(), 500);
          });
        }
      });
    }
  });
});



      $(document).on('click', '.add-cart-btn', function () {
    const button = $(this);
    const id = button.data('id');
    const name = button.data('name');
    const price = button.data('price');

    button.prop('disabled', true).text('Adding...');

    $.ajax({
      url: "{{ route('items.addToCartitem') }}", // same route you're already using
      type: "POST",
      data: {
        _token: '{{ csrf_token() }}',
        id: id,
        quantity: 1
      },
      success: function (response) {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: `${name} added to cart`,
          showConfirmButton: false,
          timer: 2000
        });

        setTimeout(() => {
          location.reload();
        }, 1000);

        // ✅ Update cart count badge if available
        if (typeof updateCartCount === 'function') {
          updateCartCount(response.total_quantity);
        } else {
          $('.cart-count').text(response.total_quantity);
        }

        // Optional: change button text temporarily
        button.text('Added ✓');
        setTimeout(() => {
          button.prop('disabled', false).text('+ Add to Cart');
        }, 1500);
      },
      error: function () {
        button.prop('disabled', false).text('+ Add to Cart');
        Swal.fire({
          icon: 'error',
          title: 'Error adding to cart',
          toast: true,
          position: 'top-end',
          timer: 2000,
          showConfirmButton: false
        });
      }
    });
  });

$(document).ready(function () {
  updateTotals();

  function updateTotals() {
    let subtotal = 0;
    $('.Itemquantity').each(function () {
      const row = $(this).closest('[data-id]');
      const quantity = parseInt($(this).text());
      const price = parseFloat(row.find('p:contains("Price")').text().replace(/[^\d.]/g, '')) || 
                    parseFloat(row.find('td:nth-child(2)').text().replace(/[^\d.]/g, ''));
      const total = quantity * price;
      subtotal += total;
      row.find('.item-total').text('₹ ' + total.toFixed(2));
    });
    $('.total').val(subtotal.toFixed(2));
    $('.totalamt').text(subtotal.toFixed(2));
    if(subtotal>0){
      $('.paymode_mode').removeClass('d-none');
    }else{
      $('.paymode_mode').addClass('d-none');
    }
  }



  
});

(function () {
  const slider = document.querySelector('.suggested-slider');
  if (!slider) return;

  // If you used CSS scroll-behavior: smooth, disable it for auto-scrolling
  slider.style.scrollBehavior = 'auto';

  let rafId = null;
  let lastTime = null;
  // pixels per second
  const pixelsPerSecond = 30; // tweak this: larger = faster

  function step(timestamp) {
    if (!lastTime) lastTime = timestamp;
    const dt = (timestamp - lastTime) / 1000; // seconds
    lastTime = timestamp;

    // Advance scroll position
    slider.scrollLeft += pixelsPerSecond * dt;

    // If we've reached (or nearly reached) the end, jump back to start
    // small epsilon to avoid floating point issues
    const atEnd = slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 1;
    if (atEnd) {
      // jump back to start (smooth loop)
      slider.scrollLeft = 0;
      // reset lastTime so next frame is smooth
      lastTime = null;
    }

    rafId = requestAnimationFrame(step);
  }

  function startAutoScroll() {
    if (rafId === null) {
      lastTime = null;
      rafId = requestAnimationFrame(step);
    }
  }

  function stopAutoScroll() {
    if (rafId !== null) {
      cancelAnimationFrame(rafId);
      rafId = null;
      lastTime = null;
    }
  }

  // pause on hover/touch
  slider.addEventListener('mouseenter', stopAutoScroll);
  slider.addEventListener('mouseleave', startAutoScroll);

  // For touch devices: pause while touching
  slider.addEventListener('touchstart', stopAutoScroll, {passive: true});
  slider.addEventListener('touchend', startAutoScroll);

  // start
  startAutoScroll();

  // Optional: expose functions for debugging
  // window._sliderAuto = { startAutoScroll, stopAutoScroll };
})();

</script>

</body>
</html>
