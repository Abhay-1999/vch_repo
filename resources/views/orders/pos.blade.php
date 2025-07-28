@extends('auth.layouts.app')

@section('content')
<div class="fluid-container py-4">
    <div class="row g-4">
        <!-- Left Side: Cart -->

        <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">🛒 New Order</h5>
                    </div>
                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Order Type</label>
                            <select id="order_type" class="form-select">
                                <option value="C">Cash</option>
                                <option value="Z">Zomato</option>
                                <option value="S">Swiggy</option>
                                <option value="U">Counter UPI</option>
                            </select>
                        </div>

                        <div class="mb-3" id="mobile_field">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile" placeholder="Enter mobile number">
                        </div>

                        <div class="mb-3" id="orderid_field" style="display: none;">
                            <label class="form-label">Order ID</label>
                            <input type="text" class="form-control" id="order_id" placeholder="Enter order ID">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="cart-table">
                                <thead class="table-dark">
                                    <tr><th>Item</th><th>Qty/Gram</th><th>Price</th><th>Total</th><th>Action</th></tr>
                                </thead>
                                <tbody></tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="text-align: right;">
                                            <h5>Total: ₹<span id="total">0.00</span></h5>
                                        </td>
                                    </tr>
                                    <tr id="discount_row" style="display: none;">
                                        <td colspan="2" style="text-align: right;">
                                            <label class="form-label">Discount (%)</label>
                                        </td>
                                        <td colspan="2">
                                            <input type="number" id="discount_percent" name="discount_percent" class="form-control" value="0" min="0" max="100">
                                        </td>
                                    </tr>
                                    <tr id="final_row" style="display: none;">
                                        <td colspan="3" style="text-align: right;">
                                            <h5>Final Amount: ₹<span id="final_total">0.00</span></h5>
                                            <input type="hidden" class="final_total" name="final_total" value="">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                            <button class="btn btn-success" id="save-order">✅ Save Order</button>
                            <button class="btn btn-primary" id="new-order">🆕 New Order</button>
                            <button class="btn btn-warning" id="manual-print-token" disabled>🎟️ Print Token</button>
                            <button class="btn btn-info" id="manual-print-bill" disabled>🧾 Print Bill</button>
                        </div>

                    </div>
                </div>
            </div>

        <!-- Right Side: Item List -->
        <div class="col-md-5">
            <div class="card shadow" id="itemCard">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">📋 Item List</h5>
                </div>
                <div class="card-body">
                    <div id="items" class="d-flex flex-wrap gap-2 justify-content-start"></div>
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

    $('#order_type').on('change', function () {
        const val = $(this).val();
        $('#mobile_field').show();
        $('#orderid_field').toggle(val !== 'C' && val !== 'U');
        updateDiscountDisplay();
    }).trigger('change');

    $('#discount_percent').on('input', updateFinalTotal);


$('#new-order').click(function () {
    location.reload();
});


$(document).ready(function () {
    // Load item buttons
    $.get('/all-items', function (res) {
        let html = '';
        res.items.forEach(item => {
            
            let btnClass = ''; // default

if (item.item_status === 'A') {
    btnClass = ''; // Active
} else if (item.item_status === 'D') {
    btnClass = 'disabled'; // Disabled style
}

html += `
    <button class="btn btn-outline-dark ${btnClass}" style="min-width:100px;" 
        onclick="addItem('${item.item_code}', '${item.item_desc}', ${item.item_rate})">
        <strong>${item.item_desc}</strong><br><small>₹${item.item_rate}</small>
    </button>`;
        });
        $('#items').html(html);
    });

    // Handle order type change
   


    $('#save-order').click(function () {
    if (cart.length === 0) return alert("Cart is empty!");

    // Disable Save button and item card
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

    $.post('{{ route("order.save") }}', {
        _token: '{{ csrf_token() }}',
        cart: cart,
        paymode: paymode,
        mobile: mobile,
        dsc: dsc,
        ft: ft,
        order_id: order_id
    }, function (response) {
        if (response.success) {
            Swal.fire({
                title: 'Order Saved!',
                showConfirmButton: true,
                allowOutsideClick: true,
            });

            $('#manual-print-token').removeAttr('disabled');
            $('#manual-print-bill').removeAttr('disabled');

            lastOrderId = response.order_id;
        } else {
            Swal.fire("Error", "Failed to save order", "error");

            // Re-enable on failure
            $saveBtn.prop('disabled', false);
            $('#itemCard').css({
                'pointer-events': 'auto',
                'opacity': '1'
            });
        }
    }).fail(function () {
        Swal.fire("Error", "Something went wrong while saving order", "error");

        // Re-enable on failure
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



function handlePrint(orderId, type) {
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
    win.document.write('</body></html>');
    win.document.close();

    win.onload = function () {
        win.focus();
        win.print();
        win.close();
    };
}





});


const gramBasedItems = ['012', '007', '008', '009', '010', '015'];

function addItem(id, name, price) {
    let existing = cart.find(i => i.id === id);
    if (existing) {
        if (!gramBasedItems.includes(id)) {
            existing.qty++;
        }
    } else {
        if (gramBasedItems.includes(id)) {
            cart.push({ id, name, price, amount: 0, grams: 0 });
        } else {
            cart.push({ id, name, price, qty: 1 });
        }
    }
    renderCart();
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
            <td>${item.name}</td>
            <td>`;

        if (isGramItem) {
            html += `
                <div class="d-flex flex-wrap gap-1">
                    <input type="number" class="form-control form-control-sm amount-input" 
                        data-id="${item.id}" style="width: 70px;" 
                        placeholder="₹ Amt" step="0.01" value="${item.amount?.toFixed(2) || ''}">
                    <input type="text" class="form-control form-control-sm grams-input" 
                        style="width: 70px;" disabled
                        value="${item.grams?.toFixed(2) || ''}">
                </div>`;
        } else {
            html += `
                <div class="input-group input-group-sm" style="max-width: 160px;">
                    <button class="btn btn-outline-secondary" onclick="changeQty('${item.id}', -1)">-</button>
                    <input type="number" class="form-control text-center qty-direct-input" 
                           data-id="${item.id}" value="${item.qty}" min="0">
                    <button class="btn btn-outline-secondary" onclick="changeQty('${item.id}', 1)">+</button>
                </div>`;
        }

        html += `</td>
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
            updateAmountAndLock($(this).data('id'), this.value);
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
    item.grams = (amount / item.price) * 100;

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

    // Gram based
    $row.find('.amount-input').val(item.amount?.toFixed(2));
    $row.find('.grams-input').val(item.grams?.toFixed(2));
    $row.find('.qty-input').val(item.qty?.toFixed(0));

    // Non-gram based
    $row.find('.qty-direct-input').val(item.qty);

    const total = item.amount || (item.qty * item.price);
    $row.find('.line-total').text('₹' + total.toFixed(2));

    // Update grand total
    let fullTotal = 0;
    cart.forEach(i => {
        fullTotal += i.amount || (i.qty * i.price);
    });
    $('#total').text(fullTotal.toFixed(2));
}



</script>



@endsection
