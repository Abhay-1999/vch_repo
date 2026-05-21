@extends('auth.layouts.app')
@section('content')
<div class="container-fluid">
   <div class="card shadow">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
         <h4 class="mb-0">
            Create Discount Master
         </h4>
         <a href="{{ route('discount-master.index') }}"
            class="btn btn-light btn-sm">
         Back
         </a>
      </div>
      <div class="card-body">
         {{-- All Validation Errors --}}
         @if ($errors->any())
         <div class="alert alert-danger">
            <ul class="mb-0">
               @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
         @endif
         <form action="{{ route('discount-master.store') }}"
            method="POST">
            @csrf
            <div class="row">
               {{-- Discount Code --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Discount Code
                  </label>
                  <input type="text"
                     name="discount_id"
                     class="form-control"
                     value="{{ $discount_id }}"
                     readonly>
               </div>
               {{-- Discount Name --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">
                  Discount Name
                  </label>
                  <input type="text"
                     name="name"
                     value="{{ old('name') }}"
                     class="form-control @error('name') is-invalid @enderror"
                     maxlength="80"
                     >
                  @error('name')
                  <small class="text-danger">
                  {{ $message }}
                  </small>
                  @enderror
               </div>
               {{-- Discount Type --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Type
                  </label>
                  <select name="type"
                     class="form-control @error('type') is-invalid @enderror"
                     >
                     <option value="">Select</option>
                     <option value="FLAT_PCT"
                     {{ old('type') == 'FLAT_PCT' ? 'selected' : '' }}>
                     FLAT_PCT
                     </option>
                     <option value="MIN_BILL"
                     {{ old('type') == 'MIN_BILL' ? 'selected' : '' }}>
                     MIN_BILL
                     </option>
                     <option value="HAPPY_HOUR"
                     {{ old('type') == 'HAPPY_HOUR' ? 'selected' : '' }}>
                     HAPPY_HOUR
                     </option>
                     <option value="BIRTHDAY"
                     {{ old('type') == 'BIRTHDAY' ? 'selected' : '' }}>
                     BIRTHDAY
                     </option>
                     <option value="COUPON_PCT"
                     {{ old('type') == 'COUPON_PCT' ? 'selected' : '' }}>
                     COUPON_PCT
                     </option>
                     <option value="BOGO"
                     {{ old('type') == 'BOGO' ? 'selected' : '' }}>
                     BOGO
                     </option>
                     <option value="LOYALTY"
                     {{ old('type') == 'LOYALTY' ? 'selected' : '' }}>
                     LOYALTY
                     </option>
                     <option value="STAFF"
                     {{ old('type') == 'STAFF' ? 'selected' : '' }}>
                     STAFF
                     </option>
                     <option value="BULK"
                     {{ old('type') == 'BULK' ? 'selected' : '' }}>
                     BULK
                     </option>
                     <option value="AGGREGATOR"
                     {{ old('type') == 'AGGREGATOR' ? 'selected' : '' }}>
                     AGGREGATOR
                     </option>
                     <option value="OPEN"
                     {{ old('type') == 'OPEN' ? 'selected' : '' }}>
                     OPEN
                     </option>
                     <option value="COMBO"
                     {{ old('type') == 'COMBO' ? 'selected' : '' }}>
                     COMBO
                     </option>
                  </select>
                  @error('type')
                  <small class="text-danger">
                  {{ $message }}
                  </small>
                  @enderror
               </div>
               {{-- Discount Value --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Value
                  </label>
                  <input type="number"
                     step="0.01"
                     min="0"
                     name="value"
                     value="{{ old('value') }}"
                     class="form-control @error('value') is-invalid @enderror"
                     >
                  @error('value')
                  <small class="text-danger">
                  {{ $message }}
                  </small>
                  @enderror
               </div>
               {{-- Unit --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Value Unit
                  </label>
                  <select name="unit"
                     class="form-control @error('unit') is-invalid @enderror"
                     >
                     <option value="%">%</option>
                     <option value="Rs">Rs</option>
                     <option value="qty">qty</option>
                  </select>
                  @error('unit')
                  <small class="text-danger">
                  {{ $message }}
                  </small>
                  @enderror
               </div>
               {{-- Max Cap --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">
                  Max Cap (Rs)
                  </label>
                  <input type="number"
                     step="0.01"
                     min="0"
                     name="max_cap"
                     value="{{ old('max_cap',0) }}"
                     class="form-control @error('max_cap') is-invalid @enderror">
                  @error('max_cap')
                  <small class="text-danger">
                  {{ $message }}
                  </small>
                  @enderror
               </div>
               {{-- Min Bill --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">
                  Min Bill (Rs)
                  </label>
                  <input type="number"
                     step="0.01"
                     min="0"
                     name="min_bill"
                     value="{{ old('min_bill',0) }}"
                     class="form-control @error('min_bill') is-invalid @enderror">
                  @error('min_bill')
                  <small class="text-danger">
                  {{ $message }}
                  </small>
                  @enderror
               </div>
               {{-- Applies To --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">
                  Applies To
                  </label>
                  <select name="applies_to"
                     class="form-control @error('applies_to') is-invalid @enderror"
                     >
                     <option value="Bill Total">
                        Bill Total
                     </option>
                     <optgroup label="Items">
                        @foreach($items as $item)
                        <option value="Item: {{ $item->item_name }}">
                           Item: {{ $item->item_name }}
                        </option>
                        @endforeach
                     </optgroup>
                  </select>
                  @error('applies_to')
                  <small class="text-danger">
                  {{ $message }}
                  </small>
                  @enderror
               </div>
               {{-- Stackable --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Stackable
                  </label>
                  <select name="stackable"
                     class="form-control">
                     <option value="1">Yes</option>
                     <option value="0">No</option>
                  </select>
               </div>
               {{-- Auto Apply --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Auto Apply
                  </label>
                  <select name="auto_apply"
                     class="form-control">
                     <option value="1">Yes</option>
                     <option value="0">No</option>
                  </select>
               </div>
               {{-- Approval Req --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Approval Req
                  </label>
                  <select name="approval_req"
                     class="form-control">
                     <option value="0">No</option>
                     <option value="1">Yes</option>
                  </select>
               </div>
               {{-- Approval Level --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">
                  Approval Level
                  </label>
                  <select name="approval_level"
                     class="form-control">
                     <option value="Cashier">Cashier</option>
                     <option value="Manager">Manager</option>
                     <option value="Admin">Admin</option>
                     <option value="Auto">Auto</option>
                  </select>
               </div>
               {{-- Valid From --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Valid From
                  </label>
                  <input type="date"
                     name="valid_from"
                     value="{{ old('valid_from') }}"
                     class="form-control @error('valid_from') is-invalid @enderror"
                     >
                  @error('valid_from')
                  <small class="text-danger">
                  {{ $message }}
                  </small>
                  @enderror
               </div>
               {{-- Valid To --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Valid To
                  </label>
                  <input type="date"
                     name="valid_to"
                     value="{{ old('valid_to') }}"
                     class="form-control @error('valid_to') is-invalid @enderror"
                     >
                  @error('valid_to')
                  <small class="text-danger">
                  {{ $message }}
                  </small>
                  @enderror
               </div>
               {{-- Active Days --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">
                  Active Days
                  </label>
                  <select name="active_days"
                     class="form-control">
                     <option value="All">All</option>
                     <option value="Mon-Fri">Mon-Fri</option>
                     <option value="Sat-Sun">Sat-Sun</option>
                     <option value="Monday">Monday</option>
                     <option value="Tuesday">Tuesday</option>
                     <option value="Wednesday">Wednesday</option>
                     <option value="Thursday">Thursday</option>
                     <option value="Friday">Friday</option>
                     <option value="Saturday">Saturday</option>
                     <option value="Sunday">Sunday</option>
                  </select>
               </div>
               {{-- Active Hours --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">
                  Active Hours
                  </label>
                  <select name="active_hours"
                     class="form-control">
                     <option value="All">All</option>
                     <option value="11:00-15:00">11:00-15:00</option>
                     <option value="15:00-18:00">15:00-18:00</option>
                     <option value="18:00-23:00">18:00-23:00</option>
                  </select>
               </div>
               {{-- Channel --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Channel
                  </label>
                  <select name="channel"
                     class="form-control">
                     <option value="All">All</option>
                     <option value="Dine-In">Dine-In</option>
                     <option value="Takeaway">Takeaway</option>
                     <option value="Online-Zomato">
                        Online-Zomato
                     </option>
                     <option value="Online-Swiggy">
                        Online-Swiggy
                     </option>
                  </select>
               </div>
               {{-- Outlet --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">
                  Outlet Scope
                  </label>
                  <input type="text"
                     name="outlet"
                     value="{{ old('outlet','All Outlets') }}"
                     class="form-control">
               </div>
               {{-- Status --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Status
                  </label>
                  <select name="status"
                     class="form-control @error('status') is-invalid @enderror"
                     >
                     <option value="Active">Active</option>
                     <option value="Inactive">Inactive</option>
                  </select>
                  @error('status')
                  <small class="text-danger">
                  {{ $message }}
                  </small>
                  @enderror
               </div>
               {{-- Created By --}}
               <div class="col-md-2 mb-3">
                  <label class="form-label">
                  Created By
                  </label>
                  <input type="text"
                     class="form-control"
                     value="{{ auth()->guard('admin')->user()->name ?? 'Admin' }}"
                     readonly>
               </div>
               {{-- Created On --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">
                  Created On
                  </label>
                  <input type="text"
                     class="form-control"
                     value="{{ date('Y-m-d H:i:s') }}"
                     readonly>
               </div>
               {{-- Remarks --}}
               <div class="col-md-12 mb-3">
                  <label class="form-label">
                  Remarks
                  </label>
                  <textarea name="remarks"
                     rows="3"
                     maxlength="160"
                     class="form-control">{{ old('remarks') }}</textarea>
               </div>
               {{-- Submit --}}
               <div class="col-md-12 text-center">
                  <button type="submit"
                     class="btn btn-success px-5">
                  Save Discount
                  </button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection