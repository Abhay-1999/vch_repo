@extends('auth.layouts.app')
@section('content')
<style>
   html, body {
   height: 100%;
   margin: 0;
   padding: 0;
   overflow-y: auto;
   background: #f4f6f9;
   }
   .page-header {
   background: #fff;
   padding: 15px 25px;
   border-radius: 12px;
   margin-bottom: 20px;
   box-shadow: 0 2px 10px rgba(0,0,0,.08);
   display: flex;
   justify-content: space-between;
   align-items: center;
   }
   .page-header h1 {
   margin: 0;
   font-size: 28px;
   font-weight: 700;
   color: #343a40;
   }
   .sound-btn {
   border: none;
   background: #28a745;
   color: #fff;
   padding: 10px 18px;
   border-radius: 8px;
   font-weight: 600;
   transition: .3s;
   }
   .sound-btn:hover {
   background: #218838;
   }
   .approval-card {
   background: #fff;
   border-radius: 12px;
   overflow: hidden;
   box-shadow: 0 2px 10px rgba(0,0,0,.08);
   margin-bottom: 20px;
   }
   .approval-header {
   background: #ffc107;
   padding: 15px 20px;
   font-size: 18px;
   font-weight: 600;
   }
   .approval-count {
   background: #dc3545;
   color: #fff;
   border-radius: 50%;
   padding: 4px 10px;
   margin-left: 10px;
   font-size: 13px;
   }
   .status-badge {
   padding: 5px 12px;
   border-radius: 20px;
   font-size: 12px;
   font-weight: 600;
   }
   .status-admin {
   background: #dc3545;
   color: white;
   }
   .status-manager {
   background: #0d6efd;
   color: white;
   }
   .status-both {
   background: #6f42c1;
   color: white;
   }
   .table th {
   background: #343a40;
   color: white;
   text-align: center;
   vertical-align: middle;
   }
   .table td {
   vertical-align: middle;
   }
   .dd-dashboard-right-flex {
   overflow-y: auto;
   max-height: 75vh;
   }
   a.disabled {
   pointer-events: none;
   opacity: .6;
   cursor: not-allowed;
   }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<audio id="newOrderSound" src="{{ asset('sounds/bell.wav') }}" preload="auto"></audio>
<div class="container-fluid">
   {{-- Header --}}
   <div class="page-header">
      <h1>
         <i class="fa fa-shopping-cart"></i>
         Orders Dashboard
      </h1>
      <button id="unlockSoundBtn" class="sound-btn">
      🔊 Enable Notifications
      </button>
   </div>
   @if(isset($approvalRules) && $approvalRules->count() > 0)
   <div class="approval-card">
      <div class="approval-header">
         <i class="fa fa-bell"></i>
         Pending Discount Approval Requests
         <span class="approval-count">
         {{ $approvalRules->count() }}
         </span>
      </div>
      <div class="table-responsive">
         <table class="table table-bordered table-hover mb-0">
            <thead>
               <tr>
                  <th>Rule ID</th>
                  <th>Condition</th>
                  <th>Threshold</th>
                  <th>Approval Required</th>
                  <th>OTP Type</th>
                  <th>Status</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach($approvalRules as $rule)
               <tr>
                  <td>{{ $rule->rule_id }}</td>
                  <td>{{ $rule->condition }}</td>
                  <td>{{ $rule->threshold }}</td>
                  <td>
                     @if($rule->approval_required == 'ADMIN')
                     <span class="badge bg-danger">ADMIN</span>
                     @elseif($rule->approval_required == 'MANAGER')
                     <span class="badge bg-primary">MANAGER</span>
                     @else
                     <span class="badge bg-warning text-dark">
                     ADMIN + MANAGER
                     </span>
                     @endif
                  </td>
                  <td>{{ $rule->otp_password }}</td>
                  <td>
                   @if($rule->approval_status == 'PENDING')
    <span class="badge bg-warning">PENDING</span>

@elseif($rule->approval_status == 'RUNNING')
    <span class="badge bg-info">RUNNING</span>

@elseif($rule->approval_status == 'APPROVED')
    <span class="badge bg-success">APPROVED</span>
@endif
                  </td>
                  <td>
                     @if($rule->approval_status == 'APPROVED')
                     <button class="btn btn-secondary btn-sm" disabled>
                     Verified
                     </button>
                     @else
                     <button
                        type="button"
                        class="btn btn-success btn-sm"
                        onclick="openVerifyModal('{{ $rule->rule_id }}')">
                     Verify
                     </button>
                     @endif
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
   @endif
   <!-- Verify Modal -->
   <div class="modal fade" id="verifyModal" tabindex="-1">
      <div class="modal-dialog modal-sm">
         <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
               <h6 class="modal-title mb-0">
                  Verify Approval
               </h6>
               <button type="button"
                  class="btn-close btn-close-white"
                  data-bs-dismiss="modal">
               </button>
            </div>
            <div class="modal-body p-3">
               <input type="hidden" id="rule_id">
               <div class="mb-2">
                  <label class="form-label mb-1">
                  Mobile Number
                  </label>
                  <input type="text"
                     id="mobile_number"
                     class="form-control form-control-sm"
                     placeholder="Enter Mobile Number">
               </div>
               <div class="mb-2 text-center">
                  <button type="button"
                     class="btn btn-primary btn-sm"
                     onclick="sendApprovalOtp()">
                  Send OTP
                  </button>
               </div>
               <div class="mb-2">
                  <label class="form-label mb-1">
                  OTP
                  </label>
                  <input type="text"
                     id="verify_otp"
                     class="form-control form-control-sm"
                     placeholder="Enter OTP">
               </div>
            </div>
            <div class="modal-footer py-2">
               <button type="button"
                  class="btn btn-secondary btn-sm"
                  data-bs-dismiss="modal">
               Close
               </button>
               <button type="button"
                  class="btn btn-success btn-sm"
                  onclick="submitApproval()">
               Verify
               </button>
            </div>
         </div>
      </div>
   </div>
   <input type="hidden" id="orderType" value="A">
   {{-- Orders --}}
   <div class="dd-dashboard-right-flex" id="orderTable">
      @include('orders.orders_table', [
      'orders' => $orders,
      'order_arr' => $order_arr,
      'role' => $role
      ])
   </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   function openVerifyModal(ruleId)
   {
       $('#rule_id').val(ruleId);
       $('#mobile_number').val('');
       $('#verify_otp').val('');
   
       let modal = new bootstrap.Modal(
           document.getElementById('verifyModal')
       );
   
       modal.show();
   }
   function markDelivered(tran_no) {
   
       fetch("{{ route('orders.deliver') }}", {
           method: 'POST',
           headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           body: JSON.stringify({
               tran_no: tran_no
           })
       })
       .then(response => response.json())
       .then(data => {
   
           if (data.success) {
               reloadOrders();
           } else {
               alert('Failed to update order.');
           }
   
       })
       .catch(error => {
           console.error(error);
           alert('Something went wrong.');
       });
   }
   
   function updateStatus(tran_no, flag) {
   
       fetch("{{ route('orders.flag') }}", {
           method: 'POST',
           headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           body: JSON.stringify({
               tran_no: tran_no,
               flag: flag
           })
       })
       .then(response => response.json())
       .then(data => {
   
           if (data.success) {
               reloadOrders();
           } else {
               alert('Failed to update order.');
           }
   
       })
       .catch(error => {
           console.error(error);
           alert('Something went wrong.');
       });
   }
   
   function reloadOrders() {
   
       let orderType = $('#orderType').val();
   
       fetch("{{ route('orders.refresh') }}", {
           method: 'POST',
           headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
           },
           body: JSON.stringify({
               orderType: orderType
           })
       })
       .then(response => response.text())
       .then(html => {
           document.getElementById('orderTable').innerHTML = html;
       });
   }
   
   setInterval(reloadOrders, 10000);
   
   $(document).on('change', '.item-checkbox', function () {
   
       if (!this.checked) {
           return;
       }
   
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
               item_code: item_code
           },
   
           success: function () {
   
               let allCheckboxes = checkbox.closest('ul')
                                           .find('input[type="checkbox"]');
   
               let allChecked = allCheckboxes.filter(':checked').length;
   
               if (allChecked === total) {
   
                   $.ajax({
                       url: "{{ route('order.complete') }}",
                       method: "POST",
                       data: {
                           _token: "{{ csrf_token() }}",
                           tran_no: tran_no
                       },
   
                       success: function () {
                           reloadOrders();
                       }
                   });
               }
           }
       });
   });
   
</script>
<script>
   const unlockBtn = document.getElementById('unlockSoundBtn');
   const sound = document.getElementById('newOrderSound');
   
   let soundUnlocked = false;
   let lastOrderId = 0;
   
   unlockBtn.addEventListener('click', function() {
   
       if (soundUnlocked) return;
   
       sound.play()
           .then(() => {
   
               sound.pause();
               sound.currentTime = 0;
   
               soundUnlocked = true;
   
               unlockBtn.innerHTML = '✅ Notifications Enabled';
               unlockBtn.disabled = true;
   
           })
           .catch(err => {
               console.warn(err);
           });
   
   });
   
   function checkNewOrders() {
   
       fetch("{{ route('check.new.orders') }}")
           .then(res => res.json())
           .then(data => {
   
               if (
                   soundUnlocked &&
                   data.latest_order_id > lastOrderId &&
                   lastOrderId !== 0
               ) {
   
                   sound.currentTime = 0;
                   sound.volume = 1;
   
                   sound.play()
                       .catch(err => console.error(err));
               }
   
               lastOrderId = data.latest_order_id;
           });
   }
   
   setInterval(checkNewOrders, 5000);
   checkNewOrders();
   
   function sendApprovalOtp()
   {
       $.ajax({
           url: "{{ route('approval.send.otp') }}",
           type: "POST",
           data: {
               _token: "{{ csrf_token() }}",
               rule_id: $('#rule_id').val(),
               mobile_number: $('#mobile_number').val()
           },
           success: function(response)
           {
               alert(response.message);
           }
       });
   }
  function submitApproval()
{
    $.ajax({
        url: "{{ route('approval.verify') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            rule_id: $('#rule_id').val(),
            mobile_number: $('#mobile_number').val(),
            otp: $('#verify_otp').val()
        },

        success: function(response)
        {
            alert(response.message);

            if(response.success){
                location.reload();
            }
        },

        error: function(xhr)
        {
            console.log(xhr.responseText);

            let msg = "Something went wrong";

            if(xhr.responseJSON && xhr.responseJSON.message){
                msg = xhr.responseJSON.message;
            }

            alert(msg);
        }
    });
}
</script>
@endsection