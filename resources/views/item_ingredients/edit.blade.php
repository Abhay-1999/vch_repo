@extends('auth.layouts.app')
@section('content')
<div class="container mt-4">
   <div class="card shadow">
      <div class="card-header d-flex justify-content-between align-items-center">
         <h4 class="mb-0">Edit Item Ingredient</h4>
         <a href="{{ route('item_ingredients.index') }}" class="btn btn-secondary btn-sm">
         Back
         </a>
      </div>
      <div class="card-body">
         {{-- Success --}}
         @if(session('success'))
         <div class="alert alert-success">
            {{ session('success') }}
         </div>
         @endif
         {{-- Errors --}}
         @if ($errors->any())
         <div class="alert alert-danger">
            <ul class="mb-0">
               @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
         @endif
         <form action="{{ route('item_ingredients.update', $item->id) }}" method="POST">
            @csrf
            <div class="row">
               {{-- Item --}}
               <div class="col-md-4 mb-3">
                  <label class="form-label">Item</label>
                  <select name="item_code" class="form-control">
                     <option value="">Select Item</option>
                     @foreach($items as $it)
                     <option value="{{ $it->item_code }}"
                     {{ old('item_code', $item->item_code) == $it->item_code ? 'selected' : '' }}>
                     {{ $it->item_code }} - {{ $it->item_desc }}
                     </option>
                     @endforeach
                  </select>
               </div>
               {{-- Ingredient --}}
               <div class="col-md-4 mb-3">
                  <label class="form-label">Ingredient</label>
                  <select name="ingredient_id" class="form-control">
                     <option value="">Select Ingredient</option>
                     @foreach($ingredients as $ing)
                     <option value="{{ $ing->id }}"
                     {{ old('ingredient_id', $item->ingredient_id) == $ing->id ? 'selected' : '' }}>
                     {{ $ing->ingredient_name }}
                     </option>
                     @endforeach
                  </select>
               </div>
               {{-- Qty --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">Quantity</label>
                  <input type="text" 
                     name="qty" 
                     class="form-control"
                     value="{{ old('qty', $item->qty) }}">
               </div>
               {{-- ✅ Unit Dropdown --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">Unit</label>
                  <select name="unit" class="form-control">
                     <option value="">Select</option>
                     <option value="kg" 
                     {{ old('unit', $item->unit) == 'kg' ? 'selected' : '' }}>
                     Kg
                     </option>
                     <option value="gram" 
                     {{ old('unit', $item->unit) == 'gram' ? 'selected' : '' }}>
                     Gram
                     </option>
                     <option value="liter" 
                     {{ old('unit', $item->unit) == 'liter' ? 'selected' : '' }}>
                     Liter
                     </option>
                     <option value="ml" 
                     {{ old('unit', $item->unit) == 'ml' ? 'selected' : '' }}>
                     ML
                     </option>
                     <option value="nos" 
                     {{ old('unit', $item->unit) == 'nos' ? 'selected' : '' }}>
                     Nos
                     </option>
                  </select>
               </div>
            </div>
            <div class="mt-3">
               <button type="submit" class="btn btn-primary">
               Update
               </button>
               <a href="{{ route('item_ingredients.index') }}" class="btn btn-secondary">
               Cancel
               </a>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection