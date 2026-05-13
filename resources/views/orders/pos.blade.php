@extends('auth.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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


</style>
<style>
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

  .order-type {
    border-radius: 0 !important;
    font-weight: 600;
}

.order-type.active {
    background-color: #ffc107 !important; /* highlight */
    color: #000 !important;
}
.d-flex i {
    font-size: 18px;
    color: #444;
    cursor: pointer;
}

.d-flex i:hover {
    color: #000;
}
#return_amount {
    font-weight: bold;
    color: green;
}
</style>
<div class="fluid-container py-4">
    <div class="row g-4">
        <!-- Left Side: Cart -->

       

        <!-- Right Side: Item List -->
        <div class="col-md-6">
    <div class="card shadow" id="itemCard" style="height: 90vh;">
      
    <div class="p-2 w-100">
    <input type="text" id="item-search" class="form-control" placeholder="🔍 Search item...">
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
 <div class="col-md-6">
    <div class="card shadow d-flex flex-column" style="height: 100vh;"> <!-- Full-page height -->
        <div class="card-header  p-0">
       <!-- ORDER TYPE BUTTONS -->
<div class="btn-group w-100 text-center mb-2" role="group">
    <button type="button" class="btn btn-light order-type active w-100" data-type="T">
        Dine In
    </button>
    <button type="button" class="btn btn-light order-type w-100" data-type="D">
        Delivery
    </button>
    <button type="button" class="btn btn-light order-type w-100" data-type="P">
        Pick Up
    </button>
</div>

<!-- ICON ROW (NEW ROW BELOW) -->
<div class="d-flex justify-content-around align-items-center bg-light py-2 border rounded">

    <i class="fas fa-filter"></i>
<i class="fa-regular fa-user"></i>
<i class="fa-solid fa-users"></i>
<i class="fa-regular fa-comment" id="orderNoteBtn" style="cursor:pointer;"></i>
<i class="fa-solid fa-bowl-food"></i>

<input type="hidden" name="order_inst" id="order_inst">
<input type="hidden" id="tran_no" value="{{ $order->tran_no ?? '' }}">
</div>
</div>

<input type="hidden" id="order_mode" name="order_mode" value="T">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Make this section scrollable -->
        <div class="card-body d-flex flex-column p-3" style="overflow-y: auto; flex: 1 1 auto;">

          

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
      <!-- Payment Mode Selection -->


<!-- Bottom Action Buttons -->
<div class="p-3 border-top bg-white" style="position: sticky; bottom: 0; z-index: 100;">
 <div class="btn-group w-100 p-3" role="group">

    <input type="radio" class="btn-check" name="payment_mode" id="cash" value="C" checked>
    <label class="btn btn-outline-success w-100" for="cash">Cash</label>

    <input type="radio" class="btn-check" name="payment_mode" id="upi" value="U">
    <label class="btn btn-outline-primary w-100 " for="upi">UPI</label>

    <input type="radio" class="btn-check" name="payment_mode" id="card" value="E">
    <label class="btn btn-outline-success w-100" for="card">Card</label>


    <input type="radio" class="btn-check delv d-none" name="payment_mode" id="zomato" value="Z">
    <label class="btn btn-outline-danger w-100 delv d-none" for="zomato">Zomato</label>

    <input type="radio" class="btn-check delv d-none" name="payment_mode" id="swiggy" value="S">
    <label class="btn btn-outline-warning w-100 delv d-none" for="swiggy">Swiggy</label>

</div>
<div class="p-3 border-top bg-white" id="payment-extra" style="display:none;">

    <!-- UPI Options -->
    <div id="upi-options" style="display:none;">
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-primary">Paytm</button>
            <button class="btn btn-outline-success">PhonePe</button>
            <button class="btn btn-outline-dark">Google Pay</button>
            <button class="btn btn-outline-info">BHIM UPI</button>
        </div>
    </div>

    <!-- Card Options -->
    <div id="card-options" style="display:none;">
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-primary">Credit Card</button>
            <button class="btn btn-outline-success">Debit Card</button>
        </div>
    </div>

</div>
    <!-- SETTLEMENT ROW -->
<div class="d-flex align-items-center gap-2 px-3 pb-3">

    <!-- Settlement Input -->
    <input type="number" step="0.01" id="settle_amount" 
           class="form-control setl" placeholder="Enter Settlement Amount">

    <!-- Return Input -->
    <input type="text" id="return_amount" 
           class="form-control setl" placeholder="Return" readonly>

    <!-- Offers Button -->
    <button class="btn btn-danger px-3" id="offersBtn">
        Offers
    </button>

    <!-- Split Button -->
    <button class="btn btn-danger px-3" id="splitBtn" disabled>
        Split
    </button>

</div>
    <div class="d-flex justify-content-between gap-2 flex-wrap">
 

        <button class="btn btn-danger flex-fill" id="save-order-only"> Save </button>
        <button class="btn btn-danger flex-fill" id="save-order-only"> Save & Print</button>
        <button class="btn btn-danger flex-fill" id="save-order"> Save & eBill</button>
        <button class="btn btn-danger flex-fill" id="save-order"> Save & Kot Print</button>
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

<div class="modal fade" id="orderNoteModal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header py-2">
        <h6 class="modal-title">Order Instruction</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-2">
        <input type="text" id="orderNoteInput" class="form-control" placeholder="Enter instruction">
      </div>

      <div class="modal-footer py-2">
        <button class="btn btn-primary btn-sm" id="saveOrderNote">Save</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="splitModal">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Split Payment</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <h6>Total: ₹<span id="split_total">0</span></h6>

        <!-- Tabs -->
        <ul class="nav nav-tabs mt-2">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#userSplit">User Wise</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#itemSplit">Item Wise</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#percentSplit">Percentage</button>
          </li>
        </ul>

        <div class="tab-content mt-3">

          <!-- USER WISE -->
          <div class="tab-pane fade show active" id="userSplit">
              <input type="number" id="split_users" class="form-control mb-2" placeholder="No. of Persons">
              <button class="btn btn-primary btn-sm" onclick="calculateUserSplit()">Split</button>

              <div id="user_split_result" class="mt-2"></div>
          </div>

          <!-- ITEM WISE -->
          <div class="tab-pane fade" id="itemSplit">
              <div id="item_split_list"></div>
          </div>

          <!-- PERCENTAGE -->
          <div class="tab-pane fade" id="percentSplit">
              <input type="number" id="percent_value" class="form-control mb-2" placeholder="Enter %">
              <button class="btn btn-primary btn-sm" onclick="calculatePercentSplit()">Apply</button>

              <div id="percent_result" class="mt-2"></div>
          </div>

        </div>

      </div>

    </div>
  </div>
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>


$('input[name="payment_mode"]').on('change', function () {
    let mode = $(this).val();

    if (mode === 'C') {
        $('.setl').removeClass('d-none');  // show
    } else {
        $('.setl').addClass('d-none');    // hide
    }

      // sab hide karo pehle
    $('#payment-extra').hide();
    $('#upi-options').hide();
    $('#card-options').hide();

    if (mode === 'U') {
        $('#payment-extra').show();
        $('#upi-options').slideDown();
    } 
    else if (mode === 'E') {
        $('#payment-extra').show();
        $('#card-options').slideDown();
    }
});

$(document).ready(function () {
    loadItemSplit();
    calculateInitialTotals();
});

document.getElementById('splitBtn').addEventListener('click', function () {
    let total = parseFloat(document.getElementById('total').innerText) || 0;
    
    if (total <= 0) return;

    let modal = new bootstrap.Modal(document.getElementById('splitModal'));
    document.getElementById('split_total').innerText = total.toFixed(2);
    modal.show();
    loadItemSplit();
});

function calculateUserSplit() {
    let total = parseFloat($('#split_total').text()) || 0;
    let users = parseInt($('#split_users').val()) || 1;

    let perPerson = total / users;

    $('#user_split_result').html(`Each Pays: ₹${perPerson.toFixed(2)}`);
}

function loadItemSplit() {
    let html = '';

    cart.forEach(item => {
        let total = item.price * item.qty;

        html += `
        <div class="border p-2 mb-2">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <strong>${item.name}</strong><br>
                    <small>₹${item.price} x ${item.qty} = <b>₹${total}</b></small><br>
                    <small>Remaining: 
                        <b id="remain-${item.id}" class="text-success">₹${total}</b>
                    </small>
                </div>

                <input type="number"
                    class="form-control form-control-sm w-25 item-split"
                    data-id="${item.id}"
                    data-type="${item.type}"
                    data-total="${total}"
                    placeholder="₹">
            </div>
        </div>`;
    });

    $('#item_split_list').html(html);
}

function calculateInitialTotals() {
    let vegTotal = 0, nonVegTotal = 0;

    cart.forEach(item => {
        let total = item.price * item.qty;

        if (item.type === 'veg') {
            vegTotal += total;
        } else {
            nonVegTotal += total;
        }
    });

    $('#veg-total').text('₹' + vegTotal);
    $('#nonveg-total').text('₹' + nonVegTotal);

    // Initially remaining = total
    $('#veg-remaining').text('₹' + vegTotal);
    $('#nonveg-remaining').text('₹' + nonVegTotal);
}


$(document).on('keyup change', '.item-split', function () {
    let total = 0;

    $('.item-split').each(function () {
        let val = parseFloat($(this).val()) || 0;
        total += val;
    });

    $('#split_total').text('₹ ' + total);
});

$(document).on('input', '.item-split', function () {

    let vegTotal = 0, nonVegTotal = 0;
    let vegSplit = 0, nonVegSplit = 0;

    $('.item-split').each(function () {

        let id = $(this).data('id');
        let type = $(this).data('type');
        let total = parseFloat($(this).data('total')) || 0;
        let val = parseFloat($(this).val()) || 0;

        // ❌ validation
        if (val > total) {
            val = total;
            $(this).val(total);
        }

        let remaining = total - val;

        // ✅ item remaining update
        $('#remain-' + id).text('₹' + remaining);

        // ✅ category totals
        if (type === 'veg') {
            vegTotal += total;
            vegSplit += val;
        } else {
            nonVegTotal += total;
            nonVegSplit += val;
        }
    });

    // ✅ update totals
    $('#veg-total').text('₹' + vegTotal);
    $('#nonveg-total').text('₹' + nonVegTotal);

    $('#veg-split').text('₹' + vegSplit);
    $('#nonveg-split').text('₹' + nonVegSplit);

    $('#veg-remaining').text('₹' + (vegTotal - vegSplit));
    $('#nonveg-remaining').text('₹' + (nonVegTotal - nonVegSplit));
});

function calculatePercentSplit() {
    let total = parseFloat($('#split_total').text()) || 0;
    let percent = parseFloat($('#percent_value').val()) || 0;

    let amount = (total * percent) / 100;

    $('#percent_result').html(`Amount: ₹${amount.toFixed(2)}`);
}

document.getElementById('settle_amount').addEventListener('input', function () {
    
    let paid = parseFloat(this.value) || 0;
    let total = parseFloat(document.getElementById('total').innerText) || 0;

    let returnAmt = paid - total;

    document.getElementById('return_amount').value = returnAmt >= 0 
        ? returnAmt.toFixed(2) 
        : '0.00';
});

  // Open popup
    document.getElementById('orderNoteBtn').addEventListener('click', function () {
        let modal = new bootstrap.Modal(document.getElementById('orderNoteModal'));
        
        // set existing value if any
        document.getElementById('orderNoteInput').value = document.getElementById('order_inst').value;
        
        modal.show();
    });

    // Save input to hidden field
    document.getElementById('saveOrderNote').addEventListener('click', function () {
        let value = document.getElementById('orderNoteInput').value;
        document.getElementById('order_inst').value = value;

        bootstrap.Modal.getInstance(document.getElementById('orderNoteModal')).hide();
    });

    $(document).on('click', '.order-type', function () {
        $('.order-type').removeClass('active');
        $(this).addClass('active');

        let type = $(this).data('type');
        $('#order_mode').val(type);

        if(type == 'D'){
            $('.delv').removeClass('d-none');
        }else{
            $('.delv').addClass('d-none');
        }

    });
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
     //   removeRow(id);
      } else {
        const qty = parseInt(val, 10);
        if (!isNaN(qty) && qty >= 0) {
          activeInput.value = qty;
          const item = cart.find(i => i.id == id);
          if (item) item.qty = qty;
          if (typeof updateRow === 'function') updateRow(id);
          if (typeof updateTotals === 'function') updateTotals();
        } else {
          //removeRow(id);
        }
      }
    }

    if (activeType === 'amount') {
      if (!val) {
        //removeRow(id);
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
        //removeRow(id);
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
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    if (sidebar) {
        sidebar.classList.add("collapsed");
    }
});

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

$('#item-search').on('keyup', function (e) {

    const keyword = $(this).val().toLowerCase().trim();

    // ✅ ENTER press → direct add item
    if (e.key === 'Enter') {

        let foundItem = globalItems.find(item => 
            item.item_srcd?.toLowerCase() === keyword
        );

        if (foundItem) {
            addItem(foundItem.item_code, foundItem.item_desc, foundItem.item_rate);

            $(this).val('');
            drawItems(allItems);

        } else {
            alert('Item not found');
        }

        return;
    }

    // 🔍 Normal search
    if (keyword === '') {
        drawItems(allItems);
        return;
    }

    let filtered = globalItems.filter(item => 
        item.item_desc.toLowerCase().includes(keyword) ||
        item.item_srcd?.toLowerCase().includes(keyword)
    );

    drawItems(filtered);
});

let allItems = [];        // category items
let globalItems = [];     // ALL items (full DB)

// 🔥 Load ALL items once (for search)
$.get('/all-items-status', function (res) {
    globalItems = res.items; // full items store
});


function renderItems(items) {
    allItems = items; // store all items
    drawItems(items);
}

function drawItems(items) {
    let html = '';

    items.forEach(item => {
        const isDisabled = item.item_status === 'D' ? 'disabled' : '';

        // 🎨 Food type color
        let bgColor = '';
        if (item.veg_nonveg === 'V') {
            bgColor = 'border-success text-success';
        } else if (item.veg_nonveg === 'N') {
            bgColor = 'border-danger text-danger';
        } else if (item.veg_nonveg === 'W') {
            bgColor = 'border-warning text-warning';
        }

        html += `
            <button class="btn ${bgColor} ${isDisabled}"
                    style="width: 120px; height: 80px; margin: 5px; font-size: 14px;"
                    onclick="addItem('${item.item_code}', '${item.item_desc}', ${item.item_rate})"
                    ${isDisabled}>
                ${item.item_desc}
            </button>`;
    });

    $('#items').html(html);
}

    // Handle order type change
   


   $('#save-order').click(async function () {

    if (cart.length === 0) {
        return Swal.fire("Cart is empty!", "", "warning");
    }

    const $saveBtn = $(this);
    $saveBtn.prop('disabled', true);

    $('#itemCard').css({
        'pointer-events': 'none',
        'opacity': '0.5'
    });

    let paymentMode = $('input[name="payment_mode"]:checked').val();
    let tran_no = $('#tran_no').val(); // 🔥 EDIT MODE

    const payload = {
        _token: '{{ csrf_token() }}',
        cart: cart,
        paymode: paymentMode,
        mobile: $('#mobile').val(),
        dsc: $('#discount_percent').val(),
        ft: $('.final_total').val(),
        custId: $('#customer_id').val(),
        order_id: $('#order_id').val(),
        order_inst: $('#order_inst').val(),
        order_mode: $('#order_mode').val(),
        tran_no: tran_no // ✅ IMPORTANT
    };

    try {
        const response = await fetch('{{ route("order.save") }}', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (data.success) {

            localStorage.setItem('lastOrderId', data.order_id);

            // 🔥 PRINT (optional)
            if (data.html) {
                sendToPrinter(data.html);
            }

            // 🔥 EDIT vs NEW MESSAGE
            let msg = tran_no ? 'Order Updated!' : 'Order Saved!';

            Swal.fire({
                title: msg,
                icon: 'success',
                showConfirmButton: false,
                timer: 1000
            });

            $('#manual-print-token, #manual-print-bill').prop('disabled', false);

            // 🔥 AFTER SAVE
            if (tran_no) {
                // 👉 EDIT MODE → redirect back to order list
                setTimeout(() => {
                    window.location.href = "/orders"; // change route if needed
                }, 1000);
            } else {
                // 👉 NEW ORDER → clear cart
                cart = [];
                updateCartUI();
            }

        } else {
            throw new Error("Save failed");
        }

    } catch (err) {

        Swal.fire("Error", "Something went wrong while saving order", "error");

        $saveBtn.prop('disabled', false);
        $('#itemCard').css({
            'pointer-events': 'auto',
            'opacity': '1'
        });
    }
});



function updateCartUI() {
    // ✅ Empty cart array but keep reference
    if (Array.isArray(cart)) cart.length = 0; 
    else window.cart = [];

    // ✅ Clear cart table rows (tbody)
    $('#cart-table tbody').empty();

    // ✅ Reset totals & discount
    $('#total').text('0.00');
    $('#final_total').text('0.00');
    $('.final_total').val('0.00');
    $('#discount_percent').val('').prop('disabled', true);
    
    // ✅ Hide discount & final rows again
    $('#discount_row, #final_row').hide();

    // ✅ Reset inputs
    $('#order_id, #mobile').val('');
    $('#customer_id').val('');
    $('#order_type').val('C'); // default back to Cash (adjust if you want)

    // ✅ Disable manual print buttons until next save
    $('#manual-print-token, #manual-print-bill').prop('disabled', true);

    // ✅ Re-enable interactions & save buttons
    $('#itemCard').css({ 'pointer-events': 'auto', 'opacity': '1' });
    $('#save-order, #save-order-only').prop('disabled', false);
}




$('#save-order-only').click(function () {
    if (cart.length === 0) return alert("Cart is empty!");

    const $saveBtn = $(this);
    $saveBtn.prop('disabled', true);
    $('#itemCard').css({
        'pointer-events': 'none',
        'opacity': '0.5'
    });

let paymentMode = $('input[name="payment_mode"]:checked').val();
    const mobile = $('#mobile').val();
    const order_id = $('#order_id').val();
    const dsc = $('#discount_percent').val();
    const ft = $('.final_total').val();
    const custId = $('#customer_id').val();
    const order_mode = $('#order_mode').val()
    const order_inst = $('#order_inst').val()
    let tran_no = $('#tran_no').val(); // 🔥 EDIT MODE


    $.post('{{ route("order.save") }}', {
        _token: '{{ csrf_token() }}',
        cart: cart,
        paymode: paymentMode,
        mobile: mobile,
        dsc: dsc,
        ft: ft,
        custId: custId,
        orderType: order_mode,
        order_inst: order_inst,
        tran_no: tran_no,
        order_id: order_id
    }, function (response) {
        if (response.success) {
            localStorage.setItem('lastOrderId', response.order_id); // ✅ Store it here

              // 🔥 EDIT vs NEW MESSAGE
            let msg = tran_no ? 'Order Updated!' : 'Order Saved!';
            Swal.fire({
                title: msg,
                showConfirmButton: false,
                timer: 1000
            }).then(() => {
                // Automatically print the bill/token
                // if (typeof handlePrint === 'function') {
                //     handlePrint(response.order_id, 'token');
                // }
                if(tran_no){
                      let url = "{{ route('orders.indexp') }}";
                        window.location.href = url;
                }

                // Refresh after 2 seconds (adjust if needed)
               // setTimeout(() => {
                cart = [];
                  updateCartUI();
              //  }, 1000);
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
            // Normal item → increase qty
            existing.qty++;
        } else {
            // Gram-based item → increase grams by 100
            existing.grams += 100;
            existing.amount = (existing.grams / 100) * existing.price;
        }
    } else {
        if (id === '017') { // Gulab Jamun ID
            // default 4 qty
            cart.push({ 
                id, name, price, qty: 4,
                item_inst: ''   // ✅ ADD HERE
            });

        } else if (gramBasedItems.includes(id)) {
            // Gram-based item starts at 100g
            cart.push({ 
                id, name, price, grams: 100, amount: price,
                item_inst: ''   // ✅ ADD HERE
            });

        } else {
            // Normal item starts with qty 1
            cart.push({ 
                id, name, price, qty: 1,
                item_inst: ''   // ✅ ADD HERE
            });
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
            let grams = parseFloat(item.grams) || 0;
            let amount = parseFloat(item.amount) || 0;

            if (grams > 0) {
                amount = (grams / 100) * item.price;
            } else if (amount > 0) {
                grams = (amount / item.price) * 100;
            } else {
                grams = 100;
                amount = item.price;
            }

            item.grams = grams;
            item.amount = amount;
            lineTotal = amount;
        } else {
            lineTotal = item.price * item.qty;
        }

        total += lineTotal;

        // ✅ ITEM NAME + INLINE INSTRUCTION EDIT
        html += `<tr data-id="${item.id}">
            <td>
                <div onclick="editInstruction('${item.id}')" style="cursor:pointer;">
                    <strong>${item.name}</strong>

                    <!-- Display Text -->
                    <div id="inst-text-${item.id}" style="font-size:12px; color:#666;">
                        ${item.item_inst 
                            ? '📝 ' + item.item_inst 
                            : '<span style="color:#aaa;">+ Add note</span>'}
                    </div>
                </div>

                <!-- Input Box -->
                <input type="text" 
                       id="inst-input-${item.id}"
                       class="form-control form-control-sm mt-1"
                       value="${item.item_inst || ''}"
                       style="display:none;"
                       onblur="saveInstruction('${item.id}')">
            </td>`;
        if (isGramItem) {
            html += `
                <td style="padding: 0;">
                    <input type="text" class="form-control form-control-sm amount-input" 
                           data-id="${item.id}" style="width: 70px;" 
                           placeholder="₹ Amt" value="${item.amount?.toFixed(2) || ''}">
                </td>
                <td style="padding: 0;">
                    <input type="text" class="form-control form-control-sm grams-input" 
                           data-id="${item.id}" style="width: 70px;" 
                           placeholder="Gram" value="${item.grams?.toFixed(2) || ''}">
                </td>`;
        } else {
            html += `<td style="padding: 0;">
                <div class="input-group input-group-sm" style="max-width: 120px;">
                    <button class="btn btn-outline-secondary px-2 py-1" onclick="changeQty('${item.id}', -1)">-</button>
                    <input type="text" class="form-control text-center qty-direct-input" 
                           data-id="${item.id}" value="${item.qty}">
                    <button class="btn btn-outline-secondary px-2 py-1" onclick="changeQty('${item.id}', 1)">+</button>
                </div>
            </td><td></td>`;
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
    $('.final_total').val(total.toFixed(2));

    bindAmountEvents();
    updateFinalTotal();

    let totalitems = parseFloat($('#total').text()) || 0;

    if (totalitems > 0) {
        $('#splitBtn').prop('disabled', false);
    } else {
        $('#splitBtn').prop('disabled', true);
    }
}

function editInstruction(id) {
    document.getElementById('inst-text-' + id).style.display = 'none';

    let input = document.getElementById('inst-input-' + id);
    input.style.display = 'block';
    input.focus();
}

function saveInstruction(id) {
    let input = document.getElementById('inst-input-' + id);
    let value = input.value;

    // Save in cart
    let item = cart.find(i => i.id == id);
    if (item) {
        item.item_inst = value;
    }

    // Update text
    let textDiv = document.getElementById('inst-text-' + id);
    textDiv.innerHTML = value 
        ? '📝 ' + value 
        : '<span style="color:#aaa;">+ Add note</span>';

    input.style.display = 'none';
    textDiv.style.display = 'block';
}

function toggleInstruction(id) {
    let box = document.getElementById('inst-box-' + id);
    box.style.display = (box.style.display === 'none') ? 'block' : 'none';
}

function bindInstructionEvents() {
    document.querySelectorAll('.item-inst-input').forEach(input => {
        input.addEventListener('input', function () {
            let id = this.dataset.id;
            let value = this.value;

            let item = cart.find(i => i.id == id);
            if (item) {
                item.item_inst = value;
            }
        });
    });
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
        $('.final_total').val(roundedAmount);

    } else {
        $('#total').text(fullTotal.toFixed(2));
        $('.final_total').val(fullTotal);
        
    }
  
    // $('#total').text(fullTotal.toFixed(2));
}




</script>
@if(isset($items))
<script>
    let items = @json($items);

    cart = items.map(function(item) {
        return {
            id: item.item_code,
            name: item.item_desc || '',
            price: item.item_rate,
            qty: item.item_qty,
            item_inst: item.item_inst || ''
        };
    });

    renderCart();
</script>
@endif


@endsection
