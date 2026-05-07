@extends('auth.layouts.app')
@section('content')
<div class="container mt-4">
   {{-- Header --}}
   <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Item Ingredients</h3>
      <a href="{{ route('item_ingredients.create') }}" class="btn btn-success">
      + Add Item Ingredient
      </a>
   </div>
   {{-- Success --}}
   @if(session('success'))
   <div class="alert alert-success">{{ session('success') }}</div>
   @endif
   <div class="card shadow">
      <div class="card-body">
         <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
               <thead class="table-dark">
                  <tr>
                     <th>#</th>
                     <th>Item Code</th>
                     <th>Item Name</th>
                     <th>Ingredient</th>
                     <th>Qty</th>
                     <th>Unit</th>
                     <th width="150">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($data as $key => $row)
                  <tr>
                     <td>{{ $key + 1 }}</td>
                     {{-- Item Code --}}
                     <td>{{ $row->item_code }}</td>
                     {{-- Item Description --}}
                     <td>
                        {{ optional($row->item)->item_desc ?? '-' }}
                     </td>
                     {{-- Ingredient --}}
                     <td>
                        {{ optional($row->ingredient)->ingredient_name ?? '-' }}
                     </td>
                     <td>{{ $row->qty }}</td>
                     <td>{{ $row->unit }}</td>
                     <td>
                        <a href="{{ route('item_ingredients.edit', $row->id) }}" 
                           class="btn btn-sm btn-primary">
                        Edit
                        </a>
                        <form action="{{ route('item_ingredients.delete', $row->id) }}" 
                           method="POST" 
                           style="display:inline;">
                           @csrf
                           <button type="submit" 
                              class="btn btn-sm btn-danger"
                              onclick="return confirm('Delete?')">
                           Delete
                           </button>
                        </form>
                     </td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="7" class="text-center text-muted">
                        No Records Found
                     </td>
                  </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
@endsection