@extends('auth.layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4>Daily Sales Entry</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('sales.store') }}"
                  method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-4">

                        <label>Menu Item</label>

                        <select name="menu_item_id"
                                class="form-control">

                            <option value="">Select Item</option>

                            @foreach($menuItems as $item)

                            <option value="{{ $item->id }}">
                                {{ $item->item_name }}
                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label>Qty Sold</label>

                        <input type="number"
                               step="0.01"
                               name="qty_sold"
                               class="form-control">

                    </div>

                    <div class="col-md-4">

                        <label>&nbsp;</label>

                        <button type="submit"
                                class="btn btn-success w-100">

                            Save Sales

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection