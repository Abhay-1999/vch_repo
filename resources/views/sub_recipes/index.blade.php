@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                Sub Recipes
            </h4>

            <a href="{{ route('sub-recipes.create') }}"
               class="btn btn-light">

                <i class="fa fa-plus"></i>

                Add Sub Recipe

            </a>

        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Code</th>
                        <th>Recipe Name</th>
                        <th>Batch Output</th>
                        <th>Total Cost</th>
                        <th>Cost / GM</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($subRecipes as $recipe)

                    <tr>

                        <td>
                            {{ $recipe->id }}
                        </td>

                        <td>
                            {{ $recipe->sub_recipe_code }}
                        </td>

                        <td>
                            {{ $recipe->sub_recipe_name }}
                        </td>

                        <td>
                            {{ $recipe->batch_output }}
                        </td>

                        <td>

                            ₹ {{ number_format($recipe->total_cost,2) }}

                        </td>

                        <td>

                            ₹ {{ number_format($recipe->cost_per_gram,4) }}

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6"
                            class="text-center text-danger">

                            No Sub Recipes Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection