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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <h1 class="text-center">Dashboard</h1>
    <div style="overflow-y: auto; max-height: calc(100vh - 100px); padding-bottom: 50px;">
        <div class="" id="orderTable">

            <div class="container my-4">
                <div class="row">
                    <!-- Day-wise Sales Chart -->
                    <div class="col-md-12 mb-4">
                        <h5 class="text-center">📅 Day-wise Sales items</h5>
                        <canvas id="dayWiseSalesChart"></canvas>

                    </div>

                    <!-- Today's Sales Total -->
                    <div class="col-md-6 mb-4">
                        <h5 class="text-center">💰 Today's Total Sales Mode Wise</h5>
                        <canvas id="todaySalesChart"></canvas>
                    </div>

                    <!-- Overall Sales Summary -->
                    <div class="col-12">
                        <h5 class="text-center">📈 Overall Sales Summary</h5>
                        <canvas id="overallSalesChart"></canvas>
                    </div>
                </div>
            </div>

         </div>
         <div class="container mt-5">

    <h4 class="mb-3">
        Pending Discount Approval Requests
        <span class="badge bg-danger">
            {{ $approvalRules->where('status','PENDING')->count() }}
        </span>
    </h4>

    <div class="table-responsive">

        <table class="table table-bordered table-hover">

            <thead class="table-dark text-center">
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

                @forelse($approvalRules as $rule)

                    <tr>

                        <td class="text-primary fw-bold">
                            {{ $rule->rule_id }}
                        </td>

                        <td>{{ $rule->condition }}</td>

                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ $rule->threshold }}
                            </span>
                        </td>

                        <td class="text-center">
                            {{ $rule->approval_required }}
                        </td>

                        <td class="text-center">
                            {{ $rule->otp_password }}
                        </td>

                        <td class="text-center">

                            @if($rule->approval_status == 'PENDING')
                                <span class="badge bg-warning text-dark">
                                    PENDING
                                </span>
                            @elseif($rule->approval_status == 'APPROVED')
                                <span class="badge bg-success">
                                    APPROVED
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    {{ $rule->approval_status }}
                                </span>
                            @endif

                        </td>

                        <td class="text-center">

    @if($rule->approval_status == 'APPROVED')

        <button class="btn btn-secondary btn-sm" disabled>
            Verified
        </button>

    @else

        <button type="button"
                class="btn btn-success btn-sm"
                onclick="openVerifyModal('{{ $rule->rule_id }}')">

            Verify

        </button>

    @endif

</td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No approval requests found
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>
<!-- Verify Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title">
                    Verify Approval
                </h6>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="rule_id">

                <div class="mb-2">
                    <label>Mobile Number</label>

                    <input type="text"
                           id="mobile_number"
                           class="form-control"
                           placeholder="Enter Mobile Number">
                </div>

                <div class="text-center mb-2">
                    <button type="button"
                            class="btn btn-primary btn-sm"
                            onclick="sendApprovalOtp()">

                        Send OTP

                    </button>
                </div>

                <div>
                    <label>OTP</label>

                    <input type="text"
                           id="verify_otp"
                           class="form-control"
                           placeholder="Enter OTP">
                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Close
                </button>

                <button type="button"
                        class="btn btn-success"
                        onclick="submitApproval()">
                    Verify
                </button>

            </div>

        </div>
    </div>
</div>
</div><script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
    // Sample dynamic data (replace with backend API or Blade variables)
    const dayWiseSales = {
        labels: {!! json_encode($itemWiseSales->pluck('item_desc')) !!},
        data: {!! json_encode($itemWiseSales->pluck('total')) !!}
    };

    const todaySales = {{ $todaySales }};
    const overallSales = {
        labels: {!! json_encode($monthLabels) !!},
        data: {!! json_encode($monthTotals) !!}
    };

    const paymodeLabels = {!! json_encode($paymodeLabels) !!};
    const paymodeTotals = {!! json_encode($paymodeTotals) !!};

    

    // Day-wise Sales Chart
    new Chart(document.getElementById('dayWiseSalesChart'), {
        type: 'bar',
        data: {
            labels: dayWiseSales.labels,
            datasets: [{
                label: 'Sales (₹)',
                data: dayWiseSales.data,
                backgroundColor: '#42a5f5'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

   // 🔄 Dynamically show today's sales by paymode
new Chart(document.getElementById('todaySalesChart'), {
    type: 'doughnut',
    data: {
        labels: paymodeLabels,
        datasets: [{
            data: paymodeTotals,
            backgroundColor: ['#42a5f5', '#66bb6a', '#ffca28', '#ef5350'], // 4 colors
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.label}: ₹${context.parsed}`;
                    }
                }
            },
            legend: {
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Today\'s Sales by Payment Mode'
            }
        }
    }
});


   // 🟢 Monthly Sales Line Chart
new Chart(document.getElementById('overallSalesChart'), {
    type: 'line',
    data: {
        labels: overallSales.labels,
        datasets: [{
            label: 'Monthly Sales (₹)',
            data: overallSales.data,
            borderColor: '#29b6f6',
            backgroundColor: 'rgba(41,182,246,0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});




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
        },

        error: function()
        {
            alert('OTP send failed');
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

            if(response.success)
            {
                location.reload();
            }
        },

        error: function()
        {
            alert('Verification failed');
        }
    });
}


</script>


   

@endsection
