@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Add Ingredient</h4>
            <a href="{{ route('ingredient.index') }}" class="btn btn-secondary btn-sm">
                Back
            </a>
        </div>

        <div class="card-body">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('ingredient.store') }}" method="POST">
                @csrf

                <div class="row">

                    {{-- Ingredient Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ingredient Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="ingredient_name" 
                               class="form-control"
                               value="{{ old('ingredient_name') }}"
                               placeholder="Enter ingredient name">
                    </div>

                        {{-- Unit --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Unit</label>
                                <select name="unit" class="form-control">
                                    <option value="">Select Unit</option>

                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kg</option>
                                    <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram</option>
                                    <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>ML</option>
                                    <option value="nos" {{ old('unit') == 'nos' ? 'selected' : '' }}>Nos</option>

                                </select>
                            </div>

                    {{-- Price --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Price</label>
                        <input type="text" 
                               name="rate" 
                               class="form-control"
                               value="{{ old('rate') }}"
                               placeholder="Enter Rate">
                    </div>

                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">
                        Save
                    </button>

                    <a href="{{ route('ingredient.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection