@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

            <h4>Create Report</h4>

        </div>

        <div class="card-body">

            <form action="{{ route('report-catalogues.store') }}"
                  method="POST">

                @csrf

                @include('report_catalogues.form')

            </form>

        </div>

    </div>

</div>

@endsection