@extends('auth.layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-info text-white">
            <h4>Menu Engineering Report</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead class="table-dark">

                    <tr>

                        <th>Item</th>
                        <th>Qty Sold</th>
                        <th>Sales Mix %</th>
                        <th>Contribution Margin</th>
                        <th>Class</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($report as $row)

                    <tr>

                        <td>{{ $row['item_name'] }}</td>

                        <td>{{ $row['qty_sold'] }}</td>

                        <td>{{ $row['sales_mix'] }}</td>

                        <td>
                            ₹ {{ number_format($row['contribution_margin'],2) }}
                        </td>

                        <td>

                            @if($row['menu_class'] == 'STAR')

                                <span class="badge bg-success">
                                    STAR
                                </span>

                            @elseif($row['menu_class'] == 'PUZZLE')

                                <span class="badge bg-warning">
                                    PUZZLE
                                </span>

                            @elseif($row['menu_class'] == 'PLOWHORSE')

                                <span class="badge bg-primary">
                                    PLOWHORSE
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    DOG
                                </span>

                            @endif

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection