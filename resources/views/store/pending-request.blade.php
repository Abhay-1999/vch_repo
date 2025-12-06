@extends('auth.layouts.app')

@section('content')

<div class="container-fluid mt-3">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Pending Kitchen Requests</h5>
        </div>
        @if(!$pending->isEmpty())
        <div class="card-body p-0">
      
            <table class="table table-bordered table-striped m-0">
                <thead class="table-dark">
                    <tr>
                        <th>Req. No</th>
                        <th>Req Date</th>
                        <th>Restaurant</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($pending as $p)
                    <tr>
                        <td>{{ $p->requstion_no }}</td>
                        <td>{{ $p->requstion_date }}</td>
                        <td>{{ $p->rest_cd }}</td>
                        <td>
                            <a href="{{ route('store.request.view', $p->trans_no) }}" 
                               class="btn btn-sm btn-success">
                                View & Issue
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
          
        </div>
        @else
            <h1 class="text-center">No Pending Request Found</h1>
            @endif
    </div>

</div>

@endsection
