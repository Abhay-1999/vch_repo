<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Cart</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

<!-- Toastr JS -->
  <style>
    body {
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
      background-color:rgb(0 0 0 / 0%);
      text-align: center;
      padding: 15px;
      position: fixed;
      left: 0;
      right: 0;
      z-index: 1000;
    }

    .header {
      top: 0;
    }

    .footer {
      bottom: 0;
    }

    .content {
      padding-top: 150px;
      padding-bottom: 70px;
      overflow-y: auto;
      height:calc(100vh - 100px)
    }

    .table-container {
      display: none;
    }

    .card-item {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 10px;
      color: #000;
    }

    .card-item .d-flex {
      justify-content: space-between;
      align-items: center;
    }

    @media (min-width: 768px) {
      .table-container {
        display: block;
      }
      h1{
        font-size:15px !important;
      }
      .mobile-cart {
        display: none;
      }
      .content {
      padding-top: 800px !important;
     }
  }

  @media (min-width: 320px) {
      
      .content {
      padding-top: 200px !important;
     }
  }
  </style>
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
                <th>Actions</th>
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
        <form id="orderForm" action="{{ route('payment.dummy') }}" method="POST">
          @csrf

          <input type="hidden" class="total" name="total_amount" value="" />
          <input type="hidden" name="paymode_mode" value="O">

  
          <span class="text-dark font-weight-bolder"> ₹ </span><span class="totalamt text-dark font-weight-bolder"> 0.00</span>
          <br>
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

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script>
    document.getElementById("sendOtpBtn").addEventListener("click", function () {
        const mobile = document.getElementById("mobile").value;
        if (mobile.length < 10) {
            alert("Enter a valid mobile number");
            return;
        }

        $.ajax({
          url: "{{ route('send.otp') }}",
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            mobile: mobile,
          },
          success: function (response) {
            var typeValue = response.type;
              // alert("OTP sent to " + mobile);
              if (typeValue === 'success') {
                toastr.success("OTP sent successfully!");
                document.getElementById("otpSection").style.display = "block";
              }else{
                toastr.success("Something went wrong.");
              }
          }
        });

        // Simulate sending OTP (you'll replace this with AJAX call)
     
    });

    document.getElementById("verifyOtpBtn").addEventListener("click", function () {
        const otp = document.getElementById("otpInput").value;
        // Simulate OTP check (you'll replace this with real AJAX call)
        $.ajax({
          url: "{{ route('otp.sbt') }}",
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            otp: otp,
          },
          success: function (response) {
              // alert("OTP sent to " + mobile);
              toastr.success(response.message);
              document.getElementById("otpMessage").style.display = "block";
              document.getElementById("payNowBtn").disabled = false;

              // Enable payment options if needed
              const radios = document.querySelectorAll("input[name='paymode_mode']");
              radios.forEach(r => r.disabled = false)
          }
        });
    });
</script>
  <script>
    


    document.getElementById("orderForm").addEventListener("submit", function (e) {
      const checkbox = document.querySelector('input[name="confirm_order"]');
      if (!checkbox.checked) {
        e.preventDefault();
        alert("Please confirm before ordering.");
      } else {
        alert("Order confirmed!");
      }
    });

    $(document).ready(function () {

      updateTotals();
      function updateTotals() {
        // alert('a');
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

      $('.increase-quantity').on('click', function () {
        // $('.paymode_mode').removeClass('d-none');
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

      updateTotals();
    });
  </script>
</body>
</html>
