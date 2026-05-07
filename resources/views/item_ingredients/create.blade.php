@extends('auth.layouts.app')

@section('content')
<div class="container mt-4">

   <div class="card shadow">

      {{-- Header --}}
      <div class="card-header d-flex justify-content-between align-items-center">
         <h4 class="mb-0">Add Item Ingredient</h4>
         <a href="{{ route('item_ingredients.index') }}" class="btn btn-secondary btn-sm">
            Back
         </a>
      </div>

      <div class="card-body">

         {{-- Success --}}
         @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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

         <form action="{{ route('item_ingredients.store') }}" method="POST">
            @csrf

            {{-- ITEM + ADD BUTTON SAME ROW --}}
            <div class="row mb-3 align-items-end">

               <div class="col-md-3">
                  <label class="form-label">Item</label>
                  <select name="item_code" class="form-control form-control-sm">
                     <option value="">Select Item</option>
                     @foreach($items as $it)
                        <option value="{{ $it->item_code }}">
                           {{ $it->item_code }} - {{ $it->item_desc }}
                        </option>
                     @endforeach
                  </select>
               </div>

               <div class="col-md-3">
                  <button type="button" id="addRow" class="btn btn-success btn-sm mt-4">
                     + Add Ingredient
                  </button>
               </div>

            </div>

            {{-- ROW CONTAINER --}}
            <div id="ingredientRows">

               {{-- FIRST ROW --}}
               <div class="row mb-3 ingredient-row align-items-end">

                  <div class="col-md-3">
                     <label class="form-label">Ingredient</label>
                     <select name="ingredient_id[]" class="form-control form-control-sm">
                        <option value="">Select</option>
                        @foreach($ingredients as $ing)
                        <option value="{{ $ing->id }}">
                           {{ $ing->ingredient_name }}
                        </option>
                        @endforeach
                     </select>
                  </div>

                  <div class="col-md-2">
                     <label class="form-label">Qty</label>
                     <input type="text" name="qty[]" class="form-control form-control-sm" placeholder="Qty">
                  </div>

                  <div class="col-md-2">
                     <label class="form-label">Unit</label>
                     <select name="unit[]" class="form-control form-control-sm">
                        <option value="">Select</option>
                        <option value="kg">Kg</option>
                        <option value="gram">Gram</option>
                        <option value="liter">Liter</option>
                        <option value="ml">ML</option>
                        <option value="nos">Nos</option>
                     </select>
                  </div>

                  <div class="col-md-2">
                     <button type="button" class="btn btn-danger btn-sm removeRow mt-4">
                        ✕
                     </button>
                  </div>

               </div>

            </div>

            {{-- SUBMIT --}}
            <div class="text-end">
               <button type="submit" class="btn btn-primary">
                  Save All
               </button>
            </div>

         </form>

      </div>
   </div>

</div>

{{-- JS --}}
<script>
document.getElementById('addRow').addEventListener('click', function () {

    let firstRow = document.querySelector('.ingredient-row');
    let newRow = firstRow.cloneNode(true);

    // clear values
    newRow.querySelectorAll('input').forEach(i => i.value = '');
    newRow.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

    document.getElementById('ingredientRows').appendChild(newRow);
});

document.addEventListener('click', function(e){
    if(e.target.classList.contains('removeRow')){
        let rows = document.querySelectorAll('.ingredient-row');
        if(rows.length > 1){
            e.target.closest('.ingredient-row').remove();
        }
    }
});
</script>

@endsection