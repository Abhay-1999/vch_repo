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

</script>


   

@endsection
