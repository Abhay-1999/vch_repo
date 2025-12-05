@extends('auth.layouts.app')

@section('content')
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow-y: auto; /* ensures vertical scrolling */
    }

    .dd-dashboard-right-flex {
        overflow-y: auto;
        max-height: 80vh; /* or adjust based on your layout */
    }

    a.disabled {
        pointer-events: none;
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
    <h1 class="text-center">Orders</h1>
    <audio id="newOrderSound" src="{{ asset('sounds/bell.wav') }}" preload="auto"></audio>

<!-- Button to unlock sound -->
<button id="unlockSoundBtn" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
    Click here to enable sound notifications
</button>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div style="overflow-y: auto; max-height: calc(100vh - 100px); padding-bottom: 50px;">
    <input type="hidden" id="orderType" value="A">

    <div class="dd-dashboard-right-flex" id="orderTable">

         @include('orders.orders_table', ['orders' => $orders,'order_arr'=>$order_arr,'role'=>$role])
         </div>
         </div>
      
     
         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>


    function markDelivered(tran_no) {

        fetch("{{ route('orders.deliver') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tran_no: tran_no })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                reloadOrders(); // Reload full order list
            } else {
                alert('Failed to update order.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong.');
        });
    }

    function updateStatus(tran_no,flag) {

        fetch("{{ route('orders.flag') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tran_no: tran_no,flag:flag })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                reloadOrders(); // Reload full order list
            } else {
                alert('Failed to update order.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong.');
        });
    }

    function reloadOrders() {
        var orderType = $('#orderType').val();
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("{{ route('orders.refresh') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ orderType: orderType })
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('orderTable').innerHTML = html;
        });
    }

    setInterval(reloadOrders, 10000);

    $(document).on('change', '.item-checkbox', function () {
        if (this.checked) {
            

            let checkbox = $(this);
            let tran_no = checkbox.data('tran');
            let item_code = checkbox.data('item_code');
            let item_index = checkbox.data('index');
            let total = checkbox.data('total');
            checkbox.prop('disabled', true);
            $.ajax({
                url: "{{ route('order.item.update') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    tran_no: tran_no,
                    item_index: item_index,
                    item_code: item_code,
                },
                success: function (response) {
                    checkbox.prop('disabled', true);

                    // If last item is checked
                    let allCheckboxes = checkbox.closest('ul').find('input[type="checkbox"]');
                    let allChecked = allCheckboxes.filter(':checked').length;

                    if (allChecked === total) {
                        // Call another ajax to mark full order complete
                        $.ajax({
                            url: "{{ route('order.complete') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                tran_no: tran_no
                            },
                            success: function (res) {
                                reloadOrders(); // Reload full order list
                            }
                        });
                    }
                }
            });
        }
    });
    
</script>

<script>
const unlockBtn = document.getElementById('unlockSoundBtn');
const sound = document.getElementById('newOrderSound');
let soundUnlocked = false;

unlockBtn.addEventListener('click', function() {
    if (soundUnlocked) return;

    sound.play().then(() => {
        sound.pause();
        sound.currentTime = 0;
        soundUnlocked = true;
        console.log('🔊 Sound unlocked by user');
        unlockBtn.style.display = 'none'; // Hide button after click
        // Start your polling or other logic here if needed
    }).catch(err => {
        console.warn('Sound unlock failed:', err);
    });
});

// Example polling for new orders after unlock
// You can move this to run only after soundUnlocked = true if you prefer
let lastOrderId = 0;
function checkNewOrders() {
    fetch("{{ route('check.new.orders') }}")
        .then(res => res.json())
        .then(data => {
            if (soundUnlocked && data.latest_order_id > lastOrderId && lastOrderId !== 0) {
                sound.volume = 1;
                sound.currentTime = 0;
                sound.play()
                    .then(() => console.log('🔔 New order detected - sound played'))
                    .catch(e => console.error('Sound playback error:', e));
            }
            lastOrderId = data.latest_order_id;
        })
        .catch(err => console.error('Fetching orders error:', err));
}

// Poll every 5 seconds, you can start this only after unlock if required
setInterval(checkNewOrders, 5000);
checkNewOrders();
</script>
@endsection
