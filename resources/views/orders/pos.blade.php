@extends('auth.layouts.app')

@section('content')
<style>
    #cart-table tbody {
        -webkit-overflow-scrolling: touch !important;
        overflow-y: auto!important;

    }
    .keypad-box {
        border: 2px solid #ccc;  
        border-radius: 12px;
        background: #f9f9f9;
        display: inline-block;
        margin-bottom:10px;
    }

    /* Buttons */
    #custom-keypad button {
        width: 40px;
        height: 45px;
        font-size: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Small buttons */
    #custom-keypad #keypad-backspace,
    #custom-keypad #keypad-clear {
        width: 60px;
        height: 30px;
        font-size: 12px;
        border-radius: 4px;
    }

  /* minimal keypad styling - adapt as needed */
  #miniKeypad {
  display: none;
  position: fixed;
  bottom: 20px;   /* distance from bottom */
  right: 20px;    /* distance from right */
  width: 220px;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 8px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
  z-index: 9999;
}

  #miniKeypad .kp-display {
    height: 40px;
    margin-bottom: 8px;
    padding: 6px 8px;
    background: #f7f7f7;
    text-align: right;
    font-size: 18px;
    border-radius: 6px;
    border: 1px solid #eee;
    box-sizing: border-box;
    overflow: hidden;
  }
  #miniKeypad .kp-keys { display: grid; grid-template-columns: repeat(3,1fr); gap:8px; }
  #miniKeypad button { height:44px; border-radius:6px; border: none; cursor:pointer; font-size:16px; }
  #miniKeypad .kp-ok { grid-column: span 3; background:#0d6efd; color:white; }
  #miniKeypad .kp-clear { background:#f1f1f1; }
</style>
<div class="fluid-container py-4">
    <div class="row g-4">
        <!-- Left Side: Cart -->

        <div class="col-md-6">
    <div class="card shadow d-flex flex-column" style="height: 100vh;"> <!-- Full-page height -->
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">🛒 New Order</h5>
        </div>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Make this section scrollable -->
        <div class="card-body d-flex flex-column p-3" style="overflow-y: auto; flex: 1 1 auto;">

            <!-- Form Section -->
            <div class="row">
            <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Select Customer</label>
                <select id="customer_id" class="form-select">
                    <option value="">Select</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            </div>
            <div class="col-6">
            <div class="mb-3">
                <label class="form-label">Order Type</label>
                <select id="order_type" class="form-select">
                    <option value="C">Cash</option>
                    <option value="U">Counter UPI</option>
                    <option value="Z">Zomato</option>
                    <option value="S">Swiggy</option>
                </select>
            </div>
            </div>
           
            <div class="row">
                <div class="col-6">
                    <div class="mb-3" id="orderid_field">
                        <label class="form-label">Order ID</label>
                        <input type="text" class="form-control custom-input" id="order_id" placeholder="Enter order ID" maxlength="10">
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3" id="mobile_field" style="display: none;">
                        <label class="form-label">Otp</label>
                        <input type="text" class="form-control custom-input" id="mobile" placeholder="Enter Otp Here" maxlength="4">
                    </div>
                </div>
            </div>
            <!-- Custom Numeric Keypad (hidden by default) -->
            <div class="col-12 mt-3 d-none" id="custom-keypad">
                <div class="keypad-box d-flex flex-wrap justify-content-center p-3">
                    @for ($i = 1; $i <= 9; $i++)
                        <button type="button" class="btn btn-light m-1 keypad-key" data-key="{{ $i }}">{{ $i }}</button>
                    @endfor
                    <button type="button" class="btn btn-light m-1 keypad-key" data-key="0">0</button>
                    <button type="button" class="btn btn-sm btn-danger m-1" id="keypad-backspace">⌫</button>
                    <button type="button" class="btn btn-sm btn-secondary m-1" id="keypad-clear">Clear</button>
                </div>
            </div>
            </div>

            <!-- Scrollable Items Table -->
            <div class="table-responsive mb-3" id="cart-scroll-container" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-striped align-middle" id="cart-table">
                <thead class="table-dark">
                    <tr><th>Item</th><th>Qty/Rs</th><th>Gram</th><th>Price</th><th>Total</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <!-- items will be added here -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end">
                            <h5>Total: ₹<span id="total">0.00</span></h5>
                        </td>
                    </tr>
                    <tr id="discount_row" style="display: none;">
                        <td colspan="2" class="text-end">
                            <label class="form-label">Discount (%)</label>
                        </td>
                        <td colspan="2">
                            <input type="number" id="discount_percent" name="discount_percent" class="form-control" value="0" min="0" max="100" disabled>
                        </td>
                    </tr>
                    <tr id="final_row" style="display: none;">
                        <td colspan="3" class="text-end">
                            <h5>Final Amount: ₹<span id="final_total">0.00</span></h5>
                            <input type="hidden" class="final_total" name="final_total" value="">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        </div>

        <!-- Fixed Bottom Buttons -->
        <div class="p-3 border-top bg-white" style="position: sticky; bottom: 0; z-index: 100;">
            <div class="d-flex justify-content-between gap-2 flex-wrap">
                <button class="btn btn-warning flex-fill" id="token-view">🧾 Token View</button>
                <button class="btn btn-primary flex-fill" id="bill-view">🧾 Bill View</button>
                <button class="btn btn-danger flex-fill" id="save-order-only">✅ Save</button>
                <button class="btn btn-info flex-fill" id="print-last-bill">🧾 Last Print</button>
                <button class="btn btn-success flex-fill"  id="save-order">✅ Save & Print</button>
            </div>
        </div>
    </div>
</div>



        <!-- Right Side: Item List -->
        <div class="col-md-6">
    <div class="card shadow" id="itemCard" style="height: 90vh;">
        <div class="card-header bg-warning text-center">
            <h5 class="mb-0">📋 Item List</h5>
        </div>

        <div class="card-body p-0 d-flex" style="height: calc(100% - 56px); overflow: hidden;">
            <!-- Category List -->
            <div id="categories"
                 class="list-group bg-primary text-white flex-shrink-0"
                 style="width: 140px; overflow-y: auto; padding: 5px;">
                <!-- Category buttons load here -->
            </div>

            <!-- Items List -->
            <div id="items"
                 class="bg-light flex-grow-1 d-flex flex-wrap p-2 gap-2 justify-content-start align-content-start"
                 style="overflow-y: auto; scroll-behavior: smooth;">
                <!-- Items load here -->
            </div>
        </div>
    </div>
</div>

    </div>
</div>
<!-- Keypad -->
<div id="miniKeypad" aria-hidden="true">
  <div class="kp-display" id="kpDisplay"></div>
  <div class="kp-keys">
    <button type="button" data-val="1">1</button>
    <button type="button" data-val="2">2</button>
    <button type="button" data-val="3">3</button>

    <button type="button" data-val="4">4</button>
    <button type="button" data-val="5">5</button>
    <button type="button" data-val="6">6</button>

    <button type="button" data-val="7">7</button>
    <button type="button" data-val="8">8</button>
    <button type="button" data-val="9">9</button>

    <button type="button" data-val="0">0</button>
    <button type="button" data-val="clear" class="kp-clear">Clear</button>
    <button type="button" data-val="ok" class="kp-ok">OK</button>
  </div>
</div>
<iframe id="print-frame" style="display:none;"></iframe>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
// custom keyboard start

$(document).ready(function () {
    let activeInput = null;

    // Show keypad when focusing any custom input
    $('.custom-input').on('focus', function () {
        activeInput = $(this);
        $('#custom-keypad').removeClass('d-none');
    });

    // Hide keypad when clicking outside inputs and keypad
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.custom-input, #custom-keypad').length) {
            $('#custom-keypad').addClass('d-none');
            activeInput = null;
        }
    });

    // Handle number key press
    $('.keypad-key').click(function () {
        if (!activeInput) return;
        let digit = $(this).data('key');
        let current = activeInput.val();
        let maxLength = activeInput.attr('maxlength');

        if (current.length < maxLength) {
            activeInput.val(current + digit).trigger('input');
        }
    });

    // Handle backspace
    $('#keypad-backspace').click(function () {
        if (!activeInput) return;
        let current = activeInput.val();
        activeInput.val(current.slice(0, -1)).trigger('input');
    });

    // Handle clear
    $('#keypad-clear').click(function () {
        if (!activeInput) return;
        activeInput.val('').trigger('input');
    });

    // Restrict to numbers only from physical keyboard
    $('.custom-input').on('keypress', function (e) {
        if (e.which < 48 || e.which > 57) { // not 0-9
            e.preventDefault();
        }
    });
});

// customkeyboard end

(function () {
  const keypad = document.getElementById('miniKeypad');
  const kpDisplay = document.getElementById('kpDisplay');

  let buffer = '';
  let activeInput = null;
  let activeType = ''; // 'qty' | 'amount' | 'grams'

  function showKeypadFor(inputEl) {
    activeInput = inputEl;
    activeType = inputEl.classList.contains('qty-direct-input') ? 'qty'
              : inputEl.classList.contains('amount-input')      ? 'amount'
              : 'grams';
    buffer = '';
    kpDisplay.textContent = '';

    const r = inputEl.getBoundingClientRect();
    // keypad.style.left = (r.left + window.scrollX) + 'px';
    // keypad.style.top  = (r.bottom + window.scrollY + 6) + 'px';
    keypad.style.display = 'block';
    keypad.setAttribute('aria-hidden', 'false');
  }

  function hideKeypad() {
    keypad.style.display = 'none';
    keypad.setAttribute('aria-hidden', 'true');
    activeInput = null;
    activeType = '';
    buffer = '';
    kpDisplay.textContent = '';
  }

  function removeRow(id) {
    if (typeof removeItem === 'function') {
      removeItem(id);
    } else {
      document.querySelector(`tr[data-id="${id}"]`)?.remove();
    }
    if (Array.isArray(cart)) {
      cart = cart.filter(i => i.id != id);
    }
    if (typeof updateTotals === 'function') updateTotals();
    if (typeof updateFinalTotal === 'function') updateFinalTotal();
  }

  function commitValue() {
    if (!activeInput) { hideKeypad(); return; }
    const id  = activeInput.dataset.id;
    const val = buffer.trim();

    if (activeType === 'qty') {
      if (!val) {
        //removeRow(id);
      } else {
        const qty = parseInt(val, 10);
        if (!isNaN(qty) && qty >= 0) {
          activeInput.value = qty;
          const item = cart.find(i => i.id == id);
          if (item) item.qty = qty;
          if (typeof updateRow === 'function') updateRow(id);
          if (typeof updateTotals === 'function') updateTotals();
        } else {
         // removeRow(id);
        }
      }
    }

    if (activeType === 'amount') {
      if (!val) {
       // removeRow(id);
      } else {
        activeInput.value = parseFloat(val).toFixed(2);
        if (typeof updateAmountAndLock === 'function') {
          updateAmountAndLock(id, activeInput.value);
        }
        if (typeof updateFinalTotal === 'function') updateFinalTotal();
      }
    }

    if (activeType === 'grams') {
      if (!val) {
       // removeRow(id);
      } else {
        activeInput.value = parseFloat(val).toFixed(2);
        if (typeof updateGramAndLock === 'function') {
          updateGramAndLock(id, activeInput.value);
        }
        if (typeof updateFinalTotal === 'function') updateFinalTotal();
      }
    }

    hideKeypad();
  }

  // ---------- Events ----------
  document.addEventListener('click', (e) => {
    const input = e.target.closest && e.target.closest(
      'input.qty-direct-input, input.amount-input, input.grams-input'
    );
    if (input) {
      showKeypadFor(input);
      e.preventDefault();
      return;
    }
    if (!keypad.contains(e.target)) {
      setTimeout(commitValue, 0);
    }
  });

  keypad.addEventListener('click', (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    const val = btn.dataset.val;

    if (val === 'clear') {
      buffer = '';
      kpDisplay.textContent = '';
      return;
    }
    if (val === 'ok') {
      commitValue();
      return;
    }
    if (val === '.') {
      if (activeType === 'qty') return; // no decimals in qty
      if (buffer.includes('.')) return;
      if (!buffer) buffer = '0';
    }
    buffer += val;
    if (activeType === 'qty' && buffer.length > 1 && buffer[0] === '0') {
      buffer = buffer.replace(/^0+/, '') || '0';
    }
    kpDisplay.textContent = buffer;
  });

  document.addEventListener('keydown', (e) => {
    if (keypad.style.display !== 'block') return;
    if (e.key === 'Enter' || e.key === 'Tab') {
      commitValue();
    } else if (e.key === 'Escape') {
      hideKeypad();
    }
  });
})();
</script>







<script>
    // custom keyboard start

    $(document).ready(function () {
        let activeInput = null;

        // Show keypad when focusing any custom input
        $('.custom-input').on('focus', function () {
            activeInput = $(this);
            $('#custom-keypad').removeClass('d-none');
        });

        // Hide keypad when clicking outside inputs and keypad
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.custom-input, #custom-keypad').length) {
                $('#custom-keypad').addClass('d-none');
                activeInput = null;
            }
        });

        // Handle number key press
        $('.keypad-key').click(function () {
            if (!activeInput) return;
            let digit = $(this).data('key');
            let current = activeInput.val();
            let maxLength = activeInput.attr('maxlength');

            if (current.length < maxLength) {
                activeInput.val(current + digit).trigger('input');
            }
        });

        // Handle backspace
        $('#keypad-backspace').click(function () {
            if (!activeInput) return;
            let current = activeInput.val();
            activeInput.val(current.slice(0, -1)).trigger('input');
        });

        // Handle clear
        $('#keypad-clear').click(function () {
            if (!activeInput) return;
            activeInput.val('').trigger('input');
        });

        // Restrict to numbers only from physical keyboard
        $('.custom-input').on('keypress', function (e) {
            if (e.which < 48 || e.which > 57) { // not 0-9
                e.preventDefault();
            }
        });
    });
    // customkeyboard end


function scrollCartToBottom() {
    const container = document.querySelector("#cart-scroll-container");
    if (container) {
        setTimeout(() => {
            container.scrollTop = container.scrollHeight;
        }, 50); // delay ensures DOM has updated
    }
}



$('#order_type').change(function () {
    const type = $(this).val();
    const original = parseFloat($('#total').text());
    const val = $(this).val();

    // Toggle optional fields
    $('#mobile_field').toggle(val !== 'C' && val !== 'U');
    // $('#orderid_field').toggle(val !== 'C' && val !== 'U');

 

    if (type === 'Z' || type === 'S') {
        $.ajax({
            url: '{{ route("get.discount") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                order_type: type
            },
            success: function (response) {
                const percent = parseFloat(response.discount_percent || 0);
             
                // Update fields
                $('#discount_percent').val(percent);

                updateDiscountDisplay(); // keep this if needed

              
            },
            error: function () {
                alert('Failed to fetch discount.');
            }
        });
    } else {
        $('#discount_percent').val(0);
        $('#discount_amount').val('0.00');
        $('#final_amount').val(original.toFixed(2));
    }
});



let cart = [];

function updateDiscountDisplay() {
        const orderType = $('#order_type').val();
        const isDiscountVisible = (orderType === 'Z' || orderType === 'S');
        $('#discount_row, #final_row').toggle(isDiscountVisible);
        updateFinalTotal();
    }

    function updateFinalTotal() {
        const total = parseFloat($('#total').text()) || 0;
        const discountPercent = parseFloat($('#discount_percent').val()) || 0;
        const discountAmount = (total * discountPercent) / 100;
        const finalAmount = total - discountAmount;

        $('#final_total').text(finalAmount.toFixed(2));
        $('.final_total').val(finalAmount.toFixed(2));
    }


    // $('#order_type').on('change', function () {
    //     const val = $(this).val();
    //     $('#mobile_field').toggle(val !== 'C' && val !== 'U');
    //     $('#orderid_field').toggle(val !== 'C' && val !== 'U');
    //     updateDiscountDisplay();
    // }).trigger('change');

    $('#discount_percent').on('input', updateFinalTotal);


$('#new-order').click(function () {
    location.reload();
});


$(document).ready(function () {
    // Load item buttons
    $.get('/all-items', function (res) {
    let categoryHtml = '';
    let firstCategory = null;

    // --- Add custom categories first ---


    // --- Build Category List from DB ---
    res.categories.forEach((cat, index) => {
        const activeClass = index === 0 ? 'active bg-info text-dark' : '';
        if (index === 0) firstCategory = cat.item_grpcode;

        categoryHtml += `
            <a href="#" class="list-group-item list-group-item-action ${activeClass}" 
               data-code="${cat.item_grpcode}">
                ${cat.item_grpdesc}
            </a>`;
    });

    categoryHtml += `
        <a href="#" class="list-group-item list-group-item-action bg-success text-white" 
           data-code="zomato" data-custom="true">
            Zomato
        </a>
        <a href="#" class="list-group-item list-group-item-action bg-danger text-white" 
           data-code="swiggy" data-custom="true">
            Swiggy
        </a>
    `;

    $('#categories').html(categoryHtml);

    // --- Set click event for categories ---
    $('#categories').on('click', '.list-group-item', function (e) {
        e.preventDefault();
        const catCode = $(this).data('code');
        const isCustom = $(this).data('custom');

        // Toggle active class
        $('#categories .list-group-item').removeClass('active bg-info text-dark');
        $(this).addClass('active bg-info text-dark');

        // If Zomato or Swiggy → load all items
        if (isCustom) {
            loadAllItems();
        } else {
            // Load selected category items
            loadItems(catCode);
        }
    });

    // Auto load first category items
    if (firstCategory) {
        loadItems(firstCategory);
    }
});

// --- Load items by category ---
function loadItems(categoryCode) {
    $.get('/items-by-category/' + categoryCode, function (res) {
        renderItems(res.items);
    });
}

// --- Load all items (for Zomato & Swiggy) ---
function loadAllItems() {
    $.get('/all-items-status', function (res) {
        renderItems(res.items);
    });
}

// --- Render Items ---
function renderItems(items) {
    let html = '';
    items.forEach(item => {
        const isDisabled = item.item_status === 'D' ? 'disabled' : '';
        html += `
            <button  data-item-name="${item.item_desc}" class="btn btn-outline-warning text-dark ${isDisabled}"
                    style="width: 120px; height: 80px; margin: 5px; font-size: 14px;"
                    onclick="addItem('${item.item_code}', '${item.item_desc}', ${item.item_rate})"
                    ${isDisabled}>
                ${item.item_desc}
            </button>`;
    });
    $('#items').html(html);
}

    // Handle order type change
   


    $('#save-order').click(function () {
    if (cart.length === 0) return alert("Cart is empty!");

    const $saveBtn = $(this);
    $saveBtn.prop('disabled', true);
    $('#itemCard').css({
        'pointer-events': 'none',
        'opacity': '0.5'
    });

    const paymode = $('#order_type').val();
    const mobile = $('#mobile').val();
    const order_id = $('#order_id').val();
    const dsc = $('#discount_percent').val();
    const ft = $('.final_total').val();
    const custId = $('#customer_id').val();

    $.post('{{ route("order.save") }}', {
        _token: '{{ csrf_token() }}',
        cart: cart,
        paymode: paymode,
        mobile: mobile,
        dsc: dsc,
        ft: ft,
        custId: custId,
        order_id: order_id
    }, function (response) {
        if (response.success) {
            localStorage.setItem('lastOrderId', response.order_id); // ✅ Store it here
            Swal.fire({
                title: 'Order Saved!',
                showConfirmButton: false,
                timer: 1000
            }).then(() => {
                // Automatically print the bill/token
                if (typeof handlePrint === 'function') {
                    handlePrint(response.order_id, 'token');
                }

                // Refresh after 2 seconds (adjust if needed)
                setTimeout(() => {
                    location.reload();
                }, 2000);
            });

            $('#manual-print-token').removeAttr('disabled');
            $('#manual-print-bill').removeAttr('disabled');
            lastOrderId = response.order_id;
        } else {
            Swal.fire("Error", "Failed to save order", "error");
            $saveBtn.prop('disabled', false);
            $('#itemCard').css({
                'pointer-events': 'auto',
                'opacity': '1'
            });
        }
    }).fail(function () {
        Swal.fire("Error", "Something went wrong while saving order", "error");
        $saveBtn.prop('disabled', false);
        $('#itemCard').css({
            'pointer-events': 'auto',
            'opacity': '1'
        });
    });
});


$('#save-order-only').click(function () {
    if (cart.length === 0) return alert("Cart is empty!");

    const $saveBtn = $(this);
    $saveBtn.prop('disabled', true);
    $('#itemCard').css({
        'pointer-events': 'none',
        'opacity': '0.5'
    });

    const paymode = $('#order_type').val();
    const mobile = $('#mobile').val();
    const order_id = $('#order_id').val();
    const dsc = $('#discount_percent').val();
    const ft = $('.final_total').val();
    const custId = $('#customer_id').val();

    $.post('{{ route("order.save") }}', {
        _token: '{{ csrf_token() }}',
        cart: cart,
        paymode: paymode,
        mobile: mobile,
        dsc: dsc,
        ft: ft,
        custId: custId,
        order_id: order_id
    }, function (response) {
        if (response.success) {
            localStorage.setItem('lastOrderId', response.order_id); // ✅ Store it here
            Swal.fire({
                title: 'Order Saved!',
                showConfirmButton: false,
                timer: 1000
            }).then(() => {
                // Automatically print the bill/token
                // if (typeof handlePrint === 'function') {
                //     handlePrint(response.order_id, 'token');
                // }

                // Refresh after 2 seconds (adjust if needed)
                setTimeout(() => {
                    location.reload();
                }, 2000);
            });

            $('#manual-print-token').removeAttr('disabled');
            $('#manual-print-bill').removeAttr('disabled');
            lastOrderId = response.order_id;
        } else {
            Swal.fire("Error", "Failed to save order", "error");
            $saveBtn.prop('disabled', false);
            $('#itemCard').css({
                'pointer-events': 'auto',
                'opacity': '1'
            });
        }
    }).fail(function () {
        Swal.fire("Error", "Something went wrong while saving order", "error");
        $saveBtn.prop('disabled', false);
        $('#itemCard').css({
            'pointer-events': 'auto',
            'opacity': '1'
        });
    });
});




    function showPrintOptions(orderId) {
    Swal.fire({
        title: 'Order Saved!',
        html: `
            <p>What do you want to do next?</p>
            <button id="printToken" class="swal2-confirm swal2-styled" style="margin:5px;">Print Token</button>
            <button id="printBill" class="swal2-confirm swal2-styled" style="margin:5px;">Print Bill</button>
            <button id="printBoth" class="swal2-confirm swal2-styled" style="margin:5px;">Print Both</button>
        `,
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            document.getElementById('printToken').addEventListener('click', () => handlePrint(orderId, 'token'));
            document.getElementById('printBill').addEventListener('click', () => handlePrint(orderId, 'bill'));
            document.getElementById('printBoth').addEventListener('click', () => handlePrint(orderId, 'both'));
        }
    });
}

let lastOrderId = null;

$('#manual-print-token').click(() => {
    if (!lastOrderId) return alert('No order to print!');
    handlePrint(lastOrderId, 'token');
});

$('#manual-print-bill').click(() => {
    if (!lastOrderId) return alert('No order to print!');
    handlePrint(lastOrderId, 'bill');
});


$('#print-last-bill').click(() => {
    const storedOrderId = localStorage.getItem('lastOrderId');
    if (!storedOrderId) {
        alert('No recent order found to print!');
        return;
    }

    handlePrint(storedOrderId, 'bill'); // or 'token' if that’s your print type
    // localStorage.removeItem('lastOrderId');

});


$('#token-view').click(() => {
    const storedOrderId = localStorage.getItem('lastOrderId');
    if (!storedOrderId) {
        alert('No recent order found to print!');
        return;
    }

    handlePrintView(storedOrderId, 'token'); // or 'token' if that’s your print type
    // localStorage.removeItem('lastOrderId');

});


$('#bill-view').click(() => {
    const storedOrderId = localStorage.getItem('lastOrderId');
    if (!storedOrderId) {
        alert('No recent order found to print!');
        return;
    }

    handlePrintView(storedOrderId, 'bill'); // or 'token' if that’s your print type
    // localStorage.removeItem('lastOrderId');

});

function handlePrint(orderId, type) {
    Swal.close();

    $.post('/print-content', {
        _token: $('meta[name="csrf-token"]').attr('content'),
        trans_no: orderId,
        type: type
    }, function(res) {
        console.log(res); //
        if (res.html) {
          
            sendToPrinter(res.html);
            
        } else {
            alert('No HTML returned.');
        }
    }).fail(function() {
        alert('Print failed due to server error.');
    });
}


function handlePrintView(orderId, type) {
    Swal.close();

    $.post('/print-content', {
        _token: $('meta[name="csrf-token"]').attr('content'),
        trans_no: orderId,
        type: type
    }, function(res) {
        console.log(res); //
        if (res.html) {
          
            printHtml(res.html);
            
        } else {
            alert('No HTML returned.');
        }
    }).fail(function() {
        alert('Print failed due to server error.');
    });
}




function printHtml(html) {
    const win = window.open('', '', 'width=800,height=600');

    win.document.open();
    win.document.write('<html><head><title>Print</title></head><body>');
    win.document.write(html);
    win.document.close();

    win.onload = function () {
        win.focus();
        win.print();
        win.close();
    };
}


function sendToPrinter(html) {
    fetch("http://127.0.0.1:3000/print", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ html: html })
    })
    .then(res => res.json())
    .then(data => console.log("✅ Print response:", data))
    .catch(err => console.error("❌ Print error:", err));
}





});


const gramBasedItems = ['012', '007', '008', '009', '010', '015','025','026','031'];


function addItem(id, name, price) {
    let existing = cart.find(i => i.id === id);

    if (existing) {
        if (!gramBasedItems.includes(id)) {
            existing.qty++;
        }
    } else {
        if (id === '017') { // Gulab Jamun ID
            // default 4 qty
            cart.push({ id, name, price, qty: 4 });
        } else if (gramBasedItems.includes(id)) {
            cart.push({ id, name, price, amount: 0, grams: 0 });
        } else {
            cart.push({ id, name, price, qty: 1 });
        }
    }

    renderCart();
    scrollCartToBottom();
}


function changeQty(id, delta) {
    let item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) {
        cart = cart.filter(i => i.id !== id);
    }
    renderCart();
}

function removeItem(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

function renderCart() {
    let html = '';
    let total = 0;

    cart.forEach(item => {
        const isGramItem = gramBasedItems.includes(item.id);
        let lineTotal = 0;

        if (isGramItem) {
            let amount = parseFloat(item.amount) || 0;
            let grams = parseFloat(item.grams) || 0;

            // Set default 100gm amount if both are empty
            if (!amount && !grams) {
                item.grams = 100;
                item.amount = item.price;
            } else {
                if (amount && !grams) {
                    item.grams = (amount / item.price) * 100;
                }
                if (grams && !amount) {
                    item.amount = (grams / 100) * item.price;
                }
            }

            lineTotal = item.amount;
        } else {
            lineTotal = item.price * item.qty;
        }

        total += lineTotal;

        html += `<tr data-id="${item.id}">
            <td>${item.name}</td>`;

        if (isGramItem) {
            html += `   <td style="padding: 0;">
      <input type="text" class="form-control form-control-sm amount-input" 
             data-id="${item.id}" style="width: 70px; margin: 0;" 
             placeholder="₹ Amt" step="0.01" value="${item.amount?.toFixed(2) || ''}">
    </td>
    <td style="padding: 0;">
      <input type="text" class="form-control form-control-sm grams-input" 
             data-id="${item.id}" style="width: 70px; margin: 0;" 
             placeholder="Gram" value="${item.grams?.toFixed(2) || ''}">
    </td>`;
        } else {
            html += `<td style="padding: 0;">
  <div class="input-group input-group-sm" style="max-width: 120px; margin: 0;">
    <button class="btn btn-outline-secondary px-2 py-1" onclick="changeQty('${item.id}', -1)">-</button>
    <input type="text" class="form-control text-center qty-direct-input" 
           data-id="${item.id}" value="${item.qty}" min="0" style="margin: 0;">
    <button class="btn btn-outline-secondary px-2 py-1" onclick="changeQty('${item.id}', 1)">+</button>
  </div>
</td><td></td>
`;
        }

        html += `
            <td>₹${item.price}</td>
            <td class="line-total">₹${lineTotal.toFixed(2)}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="removeItem('${item.id}')">
                    <i class="bi bi-x-lg"></i>
                </button>
            </td>
        </tr>`;
    });

    $('#cart-table tbody').html(html);
    $('#total').text(total.toFixed(2));

    bindAmountEvents(); // rebind inputs
    updateFinalTotal(); // recalculate any discounts/tax if needed
}





function bindAmountEvents() {

    $('.amount-input').off().on('blur keydown', function (e) {
    if (e.type === 'blur' || e.key === 'Enter' || e.key === 'Tab') {
        const id = $(this).data('id');
        const value = this.value;
        updateAmountAndLock(id, value);
        updateFinalTotal();
    }
});

$('.grams-input').off().on('blur keydown', function (e) {
    if (e.type === 'blur' || e.key === 'Enter' || e.key === 'Tab') {
        const id = $(this).data('id');
        const value = this.value;
        updateGramAndLock(id, value);
        updateFinalTotal();
    }
});


    $('.qty-direct-input').off().on('change blur keydown', function (e) {
        if (e.type === 'blur' || e.key === 'Enter' || e.key === 'Tab') {
            const id = $(this).data('id');
            const qty = parseInt(this.value);
            if (!isNaN(qty) && qty >= 0) {
                const item = cart.find(i => i.id === id);
                if (item) {
                    item.qty = qty;
                    updateRow(id);
                }
            }
        }
    });
}

function updateAmountAndLock(id, value) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    const amount = parseFloat(value);
    if (isNaN(amount) || amount <= 0) return;

    item.amount = parseFloat(amount.toFixed(2));
    item.grams = parseFloat(((amount / item.price) * 100).toFixed(2)); // price per 100g

    updateRow(id);
}

function updateGramAndLock(id, value) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    const grams = parseFloat(value);
    if (isNaN(grams) || grams <= 0) return;

    item.grams = parseFloat(grams.toFixed(2));
    item.amount = parseFloat(((grams / 100) * item.price).toFixed(2)); // price per 100g

    updateRow(id);
}





function updateQtyAndRecalc(id, value) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    const qty = parseFloat(value);
    if (isNaN(qty) || qty <= 0) return;

    item.qty = qty;
    item.amount = qty * item.price;
    item.grams = (item.amount / item.price) * 100;

    updateRow(id);
}


function updateAmount(id, value) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    const amount = parseFloat(value);
    if (isNaN(amount)) {
        item.amount = 0;
        item.grams = 0;
    } else {
        item.amount = amount;
        item.grams = (amount / item.price) * 100;
    }

    updateRow(id);
}

function updateGram(id, value) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    const grams = parseFloat(value);
    if (isNaN(grams)) {
        item.grams = 0;
        item.amount = 0;
    } else {
        item.grams = grams;
        item.amount = (grams / 100) * item.price;
    }

    updateRow(id);
}

function updateRow(id) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    const $row = $(`#cart-table tbody tr[data-id="${id}"]`);

    // Update inputs for gram-based items
    if ('amount' in item && 'grams' in item) {
        $row.find('.amount-input').val(item.amount?.toFixed(2) || '');
        $row.find('.grams-input').val(item.grams?.toFixed(2) || '');
    }

    // Update inputs for qty-based items (non-gram items)
    if ('qty' in item) {
        $row.find('.qty-input').val(item.qty?.toFixed(0) || '');
        $row.find('.qty-direct-input').val(item.qty || '');
    }

    // Calculate line total
    const total = item.amount || (item.qty * item.price);
    $row.find('.line-total').text('₹' + total.toFixed(2));

    // Recalculate and update overall total
    let fullTotal = 0;
    cart.forEach(i => {
        const lineTotal = i.amount || (i.qty * i.price);
        fullTotal += parseFloat(lineTotal || 0);
    });

    const paymode = $('#order_type').val();

    if (paymode.trim().toUpperCase() === 'C' || paymode.trim().toUpperCase() === 'U') {
        let roundedAmount = Math.round(fullTotal);
        $('#total').text(roundedAmount);
    } else {
        $('#total').text(fullTotal.toFixed(2));
    }
  
    // $('#total').text(fullTotal.toFixed(2));
}




</script>



@endsection
