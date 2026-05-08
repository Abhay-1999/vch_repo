@extends('auth.layouts.app')

@section('content')
<div class="container">

<form method="POST" action="{{ url('admin/raw-materials') }}">
@csrf

<div class="row">

<div class="col-md-4">
<label>Material Name</label>
<input type="text" name="material_name" class="form-control">
</div>

<div class="col-md-4">
<label>Material Code</label>
<input type="text" name="material_code" class="form-control">
</div>

<div class="col-md-4">
<label>Unit</label>
<select name="unit_id" class="form-control">
@foreach($units as $unit)
<option value="{{ $unit->id }}">
{{ $unit->unit_name }}
</option>
@endforeach
</select>
</div>

<div class="col-md-4 mt-3">
<label>Opening Stock</label>
<input type="number" step="0.01" name="opening_stock" class="form-control">
</div>

<div class="col-md-4 mt-3">
<label>Purchase Rate</label>
<input type="number" step="0.01" name="purchase_rate" class="form-control">
</div>

<div class="col-md-4 mt-3">
<label>Minimum Alert Stock</label>
<input type="number" step="0.01" name="min_stock_alert" class="form-control">
</div>

<div class="col-md-12 mt-4">
<button class="btn btn-primary">Save</button>
</div>

</div>
</form>
</div>
@endsection