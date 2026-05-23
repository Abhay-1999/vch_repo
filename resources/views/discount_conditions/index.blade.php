@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <h4 class="mb-0">Discount Conditions List</h4>

            <a href="{{ route('discount-conditions.create') }}"
               class="btn btn-light btn-sm">
                Add Condition
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead style="background:#1f3b73; color:#fff;">

                        <tr>
                            <th>Condition ID</th>
                            <th>Discount Code</th>
                            <th>Discount Name</th>
                            <th>Condition Type</th>
                            <th>Operator</th>
                            <th>Value</th>
                            <th>Description</th>
                            <th>Active</th>
                            <th width="150">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($conditions as $condition)

                        <tr>

                            <td>
                                {{ 'CND-' . str_pad($condition->condition_id, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <td>{{ $condition->discount_id }}</td>

                            <td>
                                {{ $condition->discount->name ?? '-' }}
                            </td>

                            <td>{{ $condition->condition_type }}</td>

                            <td>{{ $condition->operator }}</td>

                            <td>{{ $condition->value }}</td>

                            <td>{{ $condition->note ?? '-' }}</td>

                            <td>
                                @if(isset($condition->active))
                                    {{ $condition->active ? 'Yes' : 'No' }}
                                @else
                                    Yes
                                @endif
                            </td>

                            <td>

    <a href="{{ route('discount-conditions.edit', $condition->condition_id) }}"
       class="btn btn-primary btn-sm">
        Edit
    </a>

    <form action="{{ route('discount-conditions.destroy', $condition->condition_id) }}"
          method="POST"
          style="display:inline-block;">

        @csrf
        @method('DELETE')

        <button type="submit"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Are you sure?')">

            Delete

        </button>

    </form>

</td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="8" class="text-center text-danger">
                                No Data Found
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