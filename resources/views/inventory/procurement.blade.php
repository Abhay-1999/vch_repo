@extends('auth.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .toolbar {
        background: #b59a5b;
        padding: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .toolbar button {
        background: #8c743c;
        color: #fff;
        border: none;
        padding: 6px 10px;
        font-size: 12px;
        cursor: pointer;
    }

    .toolbar button:hover {
        background: #6e5a2e;
    }

    .form-section {
        background: #eee;
        padding: 10px;
        border-bottom: 2px solid #ccc;
    }

    .form-section input,
    .form-section select {
        padding: 4px;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    thead {
        background: #2c2c2c;
        color: #fff;
    }

    th, td {
        border: 1px solid #ccc;
        padding: 4px;
        text-align: left;
    }

    tbody input {
        width: 100%;
        border: 1px solid #ccc;
        padding: 3px;
    }

    .small-input {
        width: 60px;
    }

    .center {
        text-align: center;
    }
</style>

<!-- Toolbar -->
<div class="toolbar">
    <button type="button" id="openPoModal">
        Create PO
    </button>
    <button>Add to PO</button>
    <button>Create REQ</button>
    <button>Add to REQ</button>
    <button>Create RFQ</button>
    <button>Add to RFQ</button>
    <button>Add to order guide</button>
    <button>Add to cart</button>
    <button>Add to Recipe</button>
    <button>Clear Items</button>
</div>

<!-- Supplier + Currency -->
<div class="form-section">
   
</div>

<!-- Table -->
 <table>
   <thead>
        <tr>
            <th>All</th>
            <th>Part #</th>
            <th>Part description</th>
            <th>On hand qty</th>
            <th>Inv UOM</th>
            <th>Inv unit cost</th>
            <th>Total value</th>
            <th>Order Qty</th>
            <th>Par levels</th>
            <th>Location</th>
            <th>Shelf</th>
            <th>Bin</th>
            <th>Category</th>
            <th>Commodity</th>
            <th>Detail code</th>
        </tr>
    </thead>

    <tbody>
    @forelse($items as $item)
    <tr>
        <td></td>

        <td>{{ $item->part_number }}</td>
        <td>{{ $item->part_description }}</td>
        <td>{{ $item->on_hand_qty }}</td>
        <td>{{ $item->inv_uom }}</td>
        <td>{{ $item->inv_unit_cost }}</td>
        <td>{{ $item->total_value }}</td>
        <td>{{ $item->order_qty }}</td>
        <td>{{ $item->par_levels }}</td>
        <td>{{ $item->location }}</td>
        <td>{{ $item->shelf }}</td>
        <td>{{ $item->bin }}</td>
        <td>{{ $item->category }}</td>
        <td>{{ $item->commodity }}</td>
        <td>{{ $item->detail_code }}</td>
    </tr>
    @empty
  
    @endforelse
    </tbody>
</table>

<div id="modalContainer"></div>
<script>
document.getElementById('openPoModal').addEventListener('click', function () {

    fetch("{{ route('create.po') }}")
        .then(res => res.text())
        .then(html => {

            document.getElementById('modalContainer').innerHTML = html;

            let modalEl = document.getElementById('poModal');

            let modal = new bootstrap.Modal(modalEl);
            modal.show();

        })
        .catch(err => {
            console.log(err);
            alert("Modal load failed - check console");
        });

});
</script>
@endsection