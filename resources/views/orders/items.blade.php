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
            <!-- Time Selection Modal -->
<!-- Modal -->
<div class="modal fade" id="timeSelectModal" tabindex="-1" aria-labelledby="timeSelectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Deactivation Time</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="modal_item_code">
        <input type="hidden" id="modal_rest_code">
        <input type="hidden" id="modal_item_status">

        <div class="mb-2">
        <label><input type="radio" name="durationOption" value="none" checked> For Not Set Minutes</label><br>
          <label><input type="radio" name="durationOption" value="30"> 30 Minutes</label><br>
          <label><input type="radio" name="durationOption" value="60"> 1 Hour</label><br>
          <label><input type="radio" name="durationOption" value="120"> 2 Hours</label><br>
          <label><input type="radio" name="durationOption" value="1440"> Next Day</label>
        </div>

        <hr>

        <div class="mb-2">
          <label for="start_time">Start Time (optional)</label>
          <input type="time" id="start_time" name="start_time" class="form-control" />
        </div>

        <div class="mb-2">
          <label for="end_time">End Time (optional)</label>
          <input type="time" id="end_time" name="end_time" class="form-control" />
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" id="saveTimeBtn" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    // Load item buttons
    $.get('/all-items-status', function (res) {
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
            data-status="${item.item_status}"
            data-start="${item.start_time }"
            data-end="${item.end_time}">
            <strong>${item.item_desc}</strong><br><small>₹${item.item_rate}</small>
        </button>`;
        });
        $('#items').html(html);
    });

    $(document).on('click', '.item-button', function () {
    const itemCode = $(this).data('code');
    const restCode = $(this).data('rest_code');
    const itemStatus = $(this).data('status');
    const startTime = $(this).data('start');
    const endTime = $(this).data('end');

    // Set modal form fields
    $('#modal_item_code').val(itemCode);
    $('#modal_rest_code').val(restCode);
    $('#modal_item_status').val(itemStatus);


// Set start and end time if available
    $('#start_time').val(startTime || '');
    $('#end_time').val(endTime || '');

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('timeSelectModal'));
    modal.show();
});


$('#saveTimeBtn').click(function () {
    const itemCode = $('#modal_item_code').val();
    const restCode = $('#modal_rest_code').val();
    const itemStatus = $('#modal_item_status').val();
    const duration = $('input[name="durationOption"]:checked').val();
    const startTime = $('#start_time').val();
    const endTime = $('#end_time').val();

    $.ajax({
        url: "{{ route('update.item.status') }}",
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            item_code: itemCode,
            rest_code: restCode,
            item_status: itemStatus,
            minutes: duration,
            start_time: startTime,
            end_time: endTime
        },
        success: function (response) {
            alert(response.message);
            $('#timeSelectModal').modal('hide');
            location.reload(); // or re-fetch item list
        },
        error: function () {
            alert("Failed to save data.");
        }
    });
});


});
</script>


   

@endsection
