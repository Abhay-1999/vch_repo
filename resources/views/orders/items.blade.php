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
    <h1 class="text-center">Items</h1>
    <div style="overflow-y: auto; max-height: calc(100vh - 100px); padding-bottom: 50px;">

    <div class="content">
                <div class="container-fluid py-4">
    <div class="row g-4">
    
        <!-- Right Side: Item List -->
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-center">📋 Item List</h5>
                </div>
                <div class="card-body">
                    <div id="items" class="d-flex flex-wrap gap-2 justify-content-start"></div>
                </div>
            </div>
        </div>
    </div>
</div>


            </div>
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    // Load item buttons
    $.get('/all-items', function (res) {
        let html = '';
        res.items.forEach(item => {
            let btnClass = 'btn-outline-dark'; // default

    if (item.item_status === 'A') {
        btnClass = 'btn-success'; // Green
    } else if (item.item_status === 'D') {
        btnClass = 'btn-danger'; // Red
    }

    html += `
        <button class="btn ${btnClass} item-button" style="min-width:100px;" 
            data-code="${item.item_code}" 
            data-desc="${item.item_desc}" 
            data-rate="${item.item_rate}"
            data-rest_code="${item.rest_code}"
            data-status="${item.item_status}">
            <strong>${item.item_desc}</strong><br><small>₹${item.item_rate}</small>
        </button>`;
        });
        $('#items').html(html);
    });

    // Handle item click
    $(document).on('click', '.item-button', function () {
    const itemCode = $(this).data('code');
    const itemDesc = $(this).data('desc');
    const itemRate = $(this).data('rate');
    const itemStatus = $(this).data('status'); // get current status
    const restCode = $(this).data('rest_code'); // get current status

    const $btn = $(this);
    $btn.prop('disabled', true).text('Checking...');

    $.ajax({
        url: "{{ route('update.item.status') }}",
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            item_code: itemCode,
            item_status: itemStatus, // send to backend
            rest_code: restCode // send to backend
        },
        success: function (response) {
            alert(response.message);
            // if (response.updated) {
            //     $btn.removeClass('btn-outline-dark').addClass('btn-success').text(itemDesc + " ✓");
            // } else {
            //     $btn.prop('disabled', false).text(itemDesc);
            // }

            $.get('/all-items', function (res) {
        let html = '';
        res.items.forEach(item => {
            let btnClass = 'btn-outline-dark'; // default

    if (item.item_status === 'A') {
        btnClass = 'btn-success'; // Green
    } else if (item.item_status === 'D') {
        btnClass = 'btn-danger'; // Red
    }

    html += `
        <button class="btn ${btnClass} item-button" style="min-width:100px;" 
            data-code="${item.item_code}" 
            data-desc="${item.item_desc}" 
            data-rate="${item.item_rate}"
            data-rest_code="${item.rest_code}"
            data-status="${item.item_status}">
            <strong>${item.item_desc}</strong><br><small>₹${item.item_rate}</small>
        </button>`;
        });
        $('#items').html(html);
    });
        },
        error: function () {
            alert("❌ Failed to update item status.");
            $btn.prop('disabled', false).text(itemDesc);
        }
    });
});

});
</script>


   

@endsection
