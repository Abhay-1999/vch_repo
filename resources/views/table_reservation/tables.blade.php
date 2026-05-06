@extends('auth.layouts.app')

@section('content')

<style>
body {
    background: #f4f6f9;
}

.table-container {
    padding: 20px;
}

.table-box {
    width: 75px;
    height: 75px;
    border: 2px dashed #cfcfcf;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 8px;
    border-radius: 10px;
    font-weight: 600;
    color: #333;
    background: #fff;
}

.active-table {
    background: #ffeaa7;
    border: 2px solid #e1b12c;
}

.section-title {
    font-weight: 600;
    margin-top: 20px;
    margin-bottom: 10px;
}

.top-buttons button {
    margin-right: 10px;
    border-radius: 6px;
}

.legend {
    margin-top: 10px;
    margin-bottom: 15px;
}

.legend span {
    margin-right: 20px;
    font-size: 14px;
}

.legend i {
    margin-right: 5px;
}

.footer-text {
    text-align: center;
    color: red;
    font-weight: bold;
    margin-top: 30px;
}

.small-modal {
    max-width: 280px;
}
</style>

<div class="container table-container">

    <h4><b>Table View</b></h4>
    <br>
    <!-- Buttons -->
    <div class="top-buttons mb-2">
        <button class="btn btn-danger">+ Table Reservation</button>
       <button class="btn btn-danger" onclick="window.location='{{ route('create.order') }}'">
    + Contactless
</button>
    </div>

    <!-- Legend -->
    <div class="legend">
        <span><i>⚪</i> Blank Table</span>
        <span style="color:#0984e3;"><i>🔵</i> Running Table</span>
        <span style="color:green;"><i>🟢</i> Printed Table</span>
        <span style="color:#f1c40f;"><i>🟡</i> Paid Table</span>
        <span style="color:orange;"><i>🟠</i> Running KOT Table</span>
    </div>

    <!-- Ground Floor -->
    <div class="section-title">Ground Floor</div>
    <div style="display:flex; flex-wrap:wrap;">
        @for($i=1; $i<=10; $i++)
        <div class="table-box {{ in_array($i,[4,5,6]) ? 'active-table' : '' }}"
     data-bs-toggle="modal"
     data-bs-target="#memberModal"
     onclick="setTable({{ $i }})">
                <div style="text-align:center;">
                    <div>{{ $i }}</div>
                    @if(in_array($i,[4,5,6]))
                        <div style="font-size:12px;">0 Min</div>
                        <div style="font-size:13px;">₹{{ [4=>325,5=>155,6=>180][$i] }}</div>
                    @endif
                </div>
            </div>
        @endfor
    </div>

 <div class="section-title">Basement</div>
  <div style="display:flex; flex-wrap:wrap;">
    @for($i=11; $i<=20; $i++)
        <div class="table-box"
             data-bs-toggle="modal"
             data-bs-target="#memberModal"
             onclick="setTable('{{ $i }}')">
            {{ $i }}
        </div>
    @endfor
</div>

    <!-- Party Hall -->
<div class="section-title">Party Hall</div>
 <div style="display:flex;">
    <div class="table-box"
         data-bs-toggle="modal"
         data-bs-target="#memberModal"
         onclick="setTable('Hall 1')">
        Hall 1
    </div>

    <div class="table-box"
         data-bs-toggle="modal"
         data-bs-target="#memberModal"
         onclick="setTable('Hall 2')">
        Hall 2
    </div>
</div>
 

</div>
<!-- Member Modal --><div class="modal fade" id="memberModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered small-modal">
    <div class="modal-content">
      
      <div class="modal-header py-2">
        <h6 class="modal-title">Members</h6>
      
      </div>

      <div class="modal-body py-2">
        <form id="memberForm">
          <input type="hidden" id="table_id">

          <label style="font-size:13px;">How many?</label>
          <input type="number" class="form-control form-control-sm mt-1" id="members" min="1" required>
        </form>
      </div>

      <div class="modal-footer py-2">
        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-sm btn-primary" onclick="submitMembers()">OK</button>
      </div>

    </div>
  </div>
</div>
<script>
function setTable(id) {
    document.getElementById('table_id').value = id;
}

function submitMembers() {
    let tableId = document.getElementById('table_id').value;
    let members = document.getElementById('members').value;

    if(!members) {
        alert("Please enter members");
        return;
    }

    console.log("Table:", tableId, "Members:", members);

    // Optional: redirect or send via AJAX
    // window.location = `/order/create?table=${tableId}&members=${members}`;

    let modal = bootstrap.Modal.getInstance(document.getElementById('memberModal'));
    modal.hide();
}

function submitMembers() {
    let tableId = document.getElementById('table_id').value;
    let members = document.getElementById('members').value;

    if (!members) {
        alert("Please enter members");
        return;
    }

    let url = "{{ route('create.order') }}";
    window.location.href = url + "?table=" + tableId + "&members=" + members;
}
</script>
@endsection