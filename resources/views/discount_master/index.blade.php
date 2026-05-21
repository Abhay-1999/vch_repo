@extends('auth.layouts.app')

@section('content')

<style>

    .discount-table th{
        background:#1f3b73;
        color:#fff;
        text-align:center;
        vertical-align:middle;
        font-size:13px;
        white-space:nowrap;
    }

    .discount-table td{
        font-size:13px;
        vertical-align:middle;
    }

    .table-responsive{
        overflow-x:auto;
        overflow-y:auto;
        max-height:700px;
    }

    .sticky-header{
        position:sticky;
        top:0;
        z-index:10;
    }

    .info-title{
        font-weight:700;
        color:#1f3b73;
    }

    .badge-active{
        background:#198754;
        color:#fff;
        padding:4px 8px;
        border-radius:4px;
        font-size:12px;
    }

    .badge-inactive{
        background:#dc3545;
        color:#fff;
        padding:4px 8px;
        border-radius:4px;
        font-size:12px;
    }

</style>

<div class="container-fluid">

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    <div class="card shadow">

        {{-- Header --}}
        <div class="card-header text-white d-flex justify-content-between align-items-center"
             style="background:#1f3b73;">

            <h4 class="mb-0">
                Discount Master List
            </h4>

            <a href="{{ route('discount-master.create') }}"
               class="btn btn-light btn-sm">

                Add Discount

            </a>

        </div>

        {{-- Body --}}
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover discount-table">

                    <thead class="sticky-header">

                        <tr>

                            <th>S.No</th>

                            <th>Discount Info</th>

                            <th>Type</th>

                            <th>Value</th>

                            <th>Max / Min Bill</th>

                            <th>Applies To</th>

                            <th>Discount Settings</th>

                            <th>Validity</th>

                            <th>Days / Hours</th>

                            <th>Channel / Outlet</th>

                            <th>Status</th>

                            <th>Created Info</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($discounts as $discount)

                        <tr>

                            {{-- Serial No --}}
                            <td class="text-center">

                                {{ $loop->iteration }}

                            </td>

                            {{-- Discount Code + Name --}}
                            <td>

                                <div>

                                    <span class="info-title">
                                        Code :
                                    </span>

                                    {{ $discount->discount_id }}

                                </div>

                                <div class="mt-1">

                                    <span class="info-title">
                                        Name :
                                    </span>

                                    {{ $discount->name }}

                                </div>

                            </td>

                            {{-- Type --}}
                            <td>

                                {{ $discount->type }}

                            </td>

                            {{-- Value + Unit --}}
                            <td class="text-center">

                                <b>
                                    {{ number_format($discount->value,2) }}
                                </b>

                                <br>

                                {{ $discount->unit }}

                            </td>

                            {{-- Max Cap + Min Bill --}}
                            <td>

                                <div>

                                    <span class="info-title">
                                        Max :
                                    </span>

                                    {{ number_format($discount->max_cap,2) }}

                                </div>

                                <div class="mt-1">

                                    <span class="info-title">
                                        Min :
                                    </span>

                                    {{ number_format($discount->min_bill,2) }}

                                </div>

                            </td>

                            {{-- Applies To --}}
                            <td>

                                {{ $discount->applies_to }}

                            </td>

                            {{-- Settings --}}
                            <td>

                                <div>

                                    <span class="info-title">
                                        Stack :
                                    </span>

                                    @if($discount->stackable == 1)

                                        Yes

                                    @else

                                        No

                                    @endif

                                </div>

                                <div>

                                    <span class="info-title">
                                        Auto :
                                    </span>

                                    @if($discount->auto_apply == 1)

                                        Yes

                                    @else

                                        No

                                    @endif

                                </div>

                                <div>

                                    <span class="info-title">
                                        Approval :
                                    </span>

                                    @if($discount->approval_req == 1)

                                        Yes

                                    @else

                                        No

                                    @endif

                                </div>

                                <div>

                                    <span class="info-title">
                                        Level :
                                    </span>

                                    {{ $discount->approval_level }}

                                </div>

                            </td>

                            {{-- Validity --}}
                            <td>

                                <div>

                                    <span class="info-title">
                                        From :
                                    </span>

                                    {{ date('d-m-Y', strtotime($discount->valid_from)) }}

                                </div>

                                <div class="mt-1">

                                    <span class="info-title">
                                        To :
                                    </span>

                                    {{ date('d-m-Y', strtotime($discount->valid_to)) }}

                                </div>

                            </td>

                            {{-- Days + Hours --}}
                            <td>

                                <div>

                                    <span class="info-title">
                                        Days :
                                    </span>

                                    {{ $discount->active_days }}

                                </div>

                                <div class="mt-1">

                                    <span class="info-title">
                                        Hours :
                                    </span>

                                    {{ $discount->active_hours }}

                                </div>

                            </td>

                            {{-- Channel + Outlet --}}
                            <td>

                                <div>

                                    <span class="info-title">
                                        Channel :
                                    </span>

                                    {{ $discount->channel }}

                                </div>

                                <div class="mt-1">

                                    <span class="info-title">
                                        Outlet :
                                    </span>

                                    {{ $discount->outlet }}

                                </div>

                            </td>

                            {{-- Status --}}
                            <td class="text-center">

                                @if($discount->status == 'Active')

                                    <span class="badge-active">

                                        Active

                                    </span>

                                @else

                                    <span class="badge-inactive">

                                        Inactive

                                    </span>

                                @endif

                            </td>

                            {{-- Created Info + Remarks --}}
                            <td>

                                <div>

                                    <span class="info-title">
                                        By :
                                    </span>

                                    {{ $discount->created_by }}

                                </div>

                                <div class="mt-1">

                                    <span class="info-title">
                                        On :
                                    </span>

                                    {{ date('d-m-Y H:i', strtotime($discount->created_on)) }}

                                </div>

                                <div class="mt-1">

                                    <span class="info-title">
                                        Remarks :
                                    </span>

                                    {{ $discount->remarks ?? '-' }}

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="12"
                                class="text-center text-danger">

                                No Discount Found

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