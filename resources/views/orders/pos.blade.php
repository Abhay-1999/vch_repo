@extends('auth.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Left Side: Cart -->
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🛒 New Order</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Order Type</label>
                        <select id="order_type" class="form-select">
                            <option value="C">Cash</option>
                            <option value="Z">Zomato</option>
                            <option value="S">Swiggy</option>
                            <option value="O">Other</option>
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
                                <tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th><th>Action</th></tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h5 class="mb-0">Total: ₹<span id="total">0.00</span></h5>
                        <button class="btn btn-success" id="save-order">✅ Save Order</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Item List -->
        <div class="col-md-5">
            <div class="card shadow">
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
let cart = [];

$(document).ready(function () {
    // Load item buttons
    $.get('/all-items', function (res) {
        let html = '';
        res.items.forEach(item => {
            html += `
                <button class="btn btn-outline-dark" style="min-width:100px;" 
                    onclick="addItem('${item.item_code}', '${item.item_desc}', ${item.item_rate})">
                    <strong>${item.item_desc}</strong><br><small>₹${item.item_rate}</small>
                </button>`;
        });
        $('#items').html(html);
    });

    // Handle order type change
    $('#order_type').on('change', function () {
        const val = $(this).val();
        $('#mobile_field').show();
        $('#orderid_field').toggle(val !== 'C');
    }).trigger('change');

    // Save order
    $('#save-order').click(function () {
        if (cart.length === 0) return alert("Cart is empty!");
        const paymode = $('#order_type').val();
        const mobile = $('#mobile').val();
        const order_id = $('#order_id').val();

        $.post('{{ route("order.save") }}', {
            _token: '{{ csrf_token() }}',
            cart: cart,
            paymode: paymode,
            mobile: mobile,
            order_id: order_id
        }, function (res) {
            alert("Order Saved! ID: " + res.order_id);
            cart = [];
            renderCart();
        });
    });
});

function addItem(id, name, price) {
    let existing = cart.find(i => i.id === id);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({ id, name, price, qty: 1 });
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
        const line = item.price * item.qty;
        total += line;
        html += `
            <tr>
                <td>${item.name}</td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-secondary" onclick="changeQty('${item.id}', -1)">-</button>
                        <button class="btn btn-light" disabled>${item.qty}</button>
                        <button class="btn btn-outline-secondary" onclick="changeQty('${item.id}', 1)">+</button>
                    </div>
                </td>
                <td>₹${item.price}</td>
                <td>₹${line.toFixed(2)}</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="removeItem('${item.id}')">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </td>
            </tr>`;
    });
    $('#cart-table tbody').html(html);
    $('#total').text(total.toFixed(2));
}
</script>
@endsection
