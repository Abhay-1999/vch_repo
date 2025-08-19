@extends('auth.layouts.app')

@section('content')
<style>
    #cart-table tbody {
  -webkit-overflow-scrolling: touch !important;
  overflow-y: auto!important;
}

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
           
            <div class="col-6">
            <div class="mb-3" id="orderid_field">
                <label class="form-label">Order ID</label>
                <input type="text" class="form-control" id="order_id" placeholder="Enter order ID">
            </div>
            </div>
            <div class="col-6">
            <div class="mb-3" id="mobile_field" style="display: none;">
                <label class="form-label">Otp</label>
                <input type="text" class="form-control" id="mobile" placeholder="Enter Otp Here">
            </div>
            </div>

            </div>

            <!-- Scrollable Items Table -->
            <div class="table-responsive mb-3" id="cart-scroll-container" style="max-height: 300px; overflow-y: auto;">
    <table class="table table-striped align-middle" id="cart-table">
        <thead class="table-dark">
            <tr><th>Item</th><th>Qty</th><th>Gram</th><th>Price</th><th>Total</th><th>Action</th></tr>
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
<iframe id="print-frame" style="display:none;"></iframe>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    // Load all categories and default items
$.get('/all-items', function (res) {
    let categoryHtml = '';
    let firstCategory = null;

    // Build Category List
    res.categories.forEach((cat, index) => {
        const activeClass = index === 0 ? 'active bg-info text-dark' : '';
        if (index === 0) firstCategory = cat.item_grpcode;

        categoryHtml += `
            <a href="#" class="list-group-item list-group-item-action ${activeClass}" 
               data-code="${cat.item_grpcode}">
                ${cat.item_grpdesc}
            </a>`;
    });

    $('#categories').html(categoryHtml);

    // Set click event for categories
    $('#categories').on('click', '.list-group-item', function (e) {
        e.preventDefault();
        const catCode = $(this).data('code');

        // Toggle active class
        $('#categories .list-group-item').removeClass('active bg-info text-dark');
        $(this).addClass('active bg-info text-dark');

        // Load selected category items
        loadItems(catCode);
    });

    // Auto load first category items
    if (firstCategory) {
        loadItems(firstCategory);
    }
});

// Load items by category
function loadItems(categoryCode) {
    $.get('/items-by-category/' + categoryCode, function (res) {
        let html = '';

        res.items.forEach(item => {
            const isDisabled = item.item_status === 'D' ? 'disabled' : '';

            html += `
    <button class="btn btn-outline-warning text-dark"
            style="width: 120px; height: 80px; margin: 5px; font-size: 14px;"
            onclick="addItem('${item.item_code}', '${item.item_desc}', ${item.item_rate})">
        ${item.item_desc}
    </button>`;

        });

        $('#items').html(html);
    });
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
