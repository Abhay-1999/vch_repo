@extends('auth.layouts.app')
@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="card text-center">
                <div class="card-body" style="background-color: aliceblue;">
                    @if(session('success'))
                        <div class="alert alert-success text-center" id="successMsg">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h3 class="card-title mb-4">DISCOUNT</h3>
                    <form id="cashbookForm" action="{{ route('disc_update') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="zomato">Zomato</label>
                                <input type="text" id="zomato" name="zomato" class="form-control mt-2 numbers" value="{{ $chainMaster->zomato }}" maxlength=5>
                            </div>
                            <div class="col-md-6">
                                <label for="swiggy">Swiggy</label>
                                <input type="text" id="swiggy" name="swiggy" class="form-control mt-2 numbers" value="{{ $chainMaster->swiggy }}" maxlength=5>
                            </div>
                        </div>
                        <input type="submit" value="Update" class="btn btn-primary w-25 mt-3 btn-sm" id="updDisc">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    document.addEventListener('input', function(event) {
        const target = event.target;
        if (target.classList.contains('numbers')) {
            target.value = target.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const msg = document.getElementById('successMsg');
        if (msg) {
            setTimeout(() => {
                msg.style.display = 'none';
            }, 3000);
        }
    });
</script>
@endsection