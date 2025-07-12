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
                            <button type="submit" class="btn btn-primary add-to-cart-button" disabled>Add to Cart</button>
                            <input type="button" class="quantity-input d-none" value="0">
                            <input type="hidden" class="btn btn-primary quantity-input qty" name="quantity" value="0">
                            <button class="btn btn-sm btn-danger decrease-quantity" type="button">-</button>
                        </form>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
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
                    updateCartCount(response.total_quantity ?? 0);
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('An error occurred while adding the item to the cart.');
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
                    updateCartCount(response.total_quantity);
                },
                error: function () {
                    alert('Failed to increase quantity.');
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
                        updateCartCount(0);
                    },
                    error: function () {
                        alert('Failed to remove item from cart.');
                    }
                });
            }
        });
    });
</script>