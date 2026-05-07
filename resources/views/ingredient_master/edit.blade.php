@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Edit Ingredient</h4>
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

            {{-- ✅ IMPORTANT CHANGE --}}
            <form action="{{ route('ingredient.update', $ingredient->id) }}" method="POST">
                @csrf

                <div class="row">

                    {{-- Ingredient Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ingredient Name</label>
                        <input type="text" 
                               name="ingredient_name" 
                               class="form-control"
                               value="{{ old('ingredient_name', $ingredient->ingredient_name) }}">
                    </div>

                       {{-- Unit --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Unit</label>
                        <select name="unit" class="form-control">

                            <option value="">Select Unit</option>

                            <option value="kg" 
                                {{ old('unit', $ingredient->unit) == 'kg' ? 'selected' : '' }}>
                                Kg
                            </option>

                            <option value="gram" 
                                {{ old('unit', $ingredient->unit) == 'gram' ? 'selected' : '' }}>
                                Gram
                            </option>

                            <option value="liter" 
                                {{ old('unit', $ingredient->unit) == 'liter' ? 'selected' : '' }}>
                                Liter
                            </option>

                            <option value="ml" 
                                {{ old('unit', $ingredient->unit) == 'ml' ? 'selected' : '' }}>
                                ML
                            </option>

                            <option value="nos" 
                                {{ old('unit', $ingredient->unit) == 'nos' ? 'selected' : '' }}>
                                Nos
                            </option>

                        </select>
                    </div>

                    {{-- Rate --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Rate</label>
                        <input type="text" 
                               name="rate" 
                               class="form-control"
                               value="{{ old('rate', $ingredient->rate) }}">
                    </div>

                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        Update
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