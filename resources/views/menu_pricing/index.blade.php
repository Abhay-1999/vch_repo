@extends('auth.layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-success text-white">
            <h4>Menu Pricing</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead class="table-dark">

                    <tr>

                        <th>Item</th>
                        <th>Plate Cost</th>
                        <th>Target FC %</th>
                        <th>Suggested Price</th>
                        <th>GST</th>
                        <th>Rounded Price</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($menuItems as $item)

                    <tr>

                        <td>{{ $item->item_name }}</td>

                        <td>
                            ₹ {{ number_format($item->plate_cost,2) }}
                        </td>

                        <td>

                            <form action="{{ url('menu-pricing/'.$item->id) }}"
                                  method="GET">

                                <input type="number"
                                       step="0.01"
                                       name="target_fc"
                                       value="{{ $item->target_food_cost_percent }}"
                                       class="form-control">

                        </td>

                        <td>
                            ₹ {{ number_format($item->suggested_price,2) }}
                        </td>

                        <td>

                            <input type="number"
                                   step="0.01"
                                   name="gst_rate"
                                   value="{{ $item->gst_rate }}"
                                   class="form-control">

                        </td>

                        <td>
                            ₹ {{ number_format($item->rounded_price,2) }}
                        </td>

                        <td>

                            <button type="submit"
                                    class="btn btn-primary">

                                Calculate

                            </button>

                            </form>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection