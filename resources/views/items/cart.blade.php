<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Cart</title>
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">


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
              </tr>
            </thead>
            <tbody>
              @foreach(session('cart') as $id => $item)
              <tr data-id="{{ $id }}">
                <td>{{ $item['name'] }}</td>
                <td>₹ {{ number_format($item['price'], 2) }}</td>
                <td class="Itemquantity">{{ $item['quantity'] }}</td>
                <td class="item-total">₹ {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
             
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

  
  <script>
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

  // 🚀 Handle Pay Now (Ajax call to Laravel)
  $("#orderForm").on("submit", function (e) {
    e.preventDefault(); 

    $.ajax({
      url: "{{ route('initiate.payment') }}",
      type: "POST",
      data: $(this).serialize(),
      success: function (response) {
        if (response.status === "success" && response.upiIntent) {
          // ✅ Redirect to UPI intent link (will open apps like GPay, PhonePe, PayZapp)
           window.location.href = data.upiIntent;
        } else {
          toastr.error("Failed to get UPI intent link");
        }
      },
      error: function () {
        toastr.error("Something went wrong, please try again.");
      }
    });
  });

  // Quantity change handlers (unchanged)
  $('.increase-quantity').on('click', function () {
    var row = $(this).closest('[data-id]');
    var id = row.data('id');
    var quantityEl = row.find('.Itemquantity');
    var currentQty = parseInt(quantityEl.text());
    $.ajax({
      url: "{{ route('items.addToCartitem') }}",
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        quantity: currentQty + 1,
        id: id
      },
      success: function (response) {
        quantityEl.text(response.quantity);
        updateTotals();
      }
    });
  });

  $('.decrease-quantity').on('click', function () {
    var row = $(this).closest('[data-id]');
    var id = row.data('id');
    var quantityEl = row.find('.Itemquantity');
    var currentQty = parseInt(quantityEl.text());

    if (currentQty > 1) {
      $.ajax({
        url: "{{ route('items.removeFromCart') }}",
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          quantity: currentQty - 1,
          id: id
        },
        success: function (response) {
          quantityEl.text(response.quantity);
          updateTotals();
        }
      });
    } else {
      $.ajax({
        url: "{{ route('items.removeFromCart') }}",
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          id: id
        },
        success: function () {
          row.remove();
          updateTotals();
        }
      });
    }
  });

});
</script>

</body>
</html>
