
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- TOAST -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="successToast" class="toast text-bg-success border-0">
        <div class="d-flex">
            <div class="toast-body">
                Item saved successfully
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">



<div class="modal fade" id="poModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 600px;">
        <div class="modal-content">

            <!-- FORM START -->
<form method="POST" action="{{ route('po.store') }}" id="poForm">                @csrf

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">Create Purchase Order</h5>
                    <span style="cursor:pointer;font-size:22px;"
                          data-bs-dismiss="modal">&times;</span>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <!-- TOP INFO -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Supplier</label>
                            <input type="text" name="supplier_name" class="form-control" placeholder="Enter Supplier">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">PO Date</label>
                            <input type="date" name="po_date" class="form-control">
                        </div>
                    </div>

                    <hr>

                    <!-- ITEM FORM -->
                    <div class="row g-3">

                        <div class="col-md-3">
                            <label>Part #</label>
                            <input type="text" name="items[0][part_number]" class="form-control">
                            <div class="text-danger error" data-field="items.0.part_number"></div>
                        </div>

                        <div class="col-md-3">
                            <label>Part Description</label>
                            <input type="text" name="items[0][part_description]" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>On Hand Qty</label>
                            <input type="number" name="items[0][on_hand_qty]" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Inv UOM</label>
                            <input type="text" name="items[0][inv_uom]" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Inv Unit Cost</label>
                            <input type="number" name="items[0][inv_unit_cost]" class="form-control">
                            <div class="text-danger error" data-field="items.0.inv_unit_cost"></div>
                        </div>

                        <div class="col-md-2">
                            <label>Total Value</label>
                            <input type="number" name="items[0][total_value]" class="form-control" readonly>
                        </div>

                        <div class="col-md-2">
                            <label>Order Qty</label>
                            <input type="number" name="items[0][order_qty]" class="form-control">
                            <div class="text-danger error" data-field="items.0.order_qty"></div>
                        </div>

                        <div class="col-md-2">
                            <label>Par Levels</label>
                            <input type="text" name="items[0][par_levels]" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Location</label>
                            <input type="text" name="items[0][location]" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Shelf</label>
                            <input type="text" name="items[0][shelf]" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Bin</label>
                            <input type="text" name="items[0][bin]" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Category</label>
                            <input type="text" name="items[0][category]" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Commodity</label>
                            <input type="text" name="items[0][commodity]" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Detail Code</label>
                            <input type="text" name="items[0][detail_code]" class="form-control">
                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Purchase Order</button>
                </div>

            </form>
            <!-- FORM END -->

        </div>
    </div>
</div>


<!-- SCRIPT -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script>
document.getElementById('poForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json' // ✅ VERY IMPORTANT
        },
        body: formData
    })
    .then(async res => {

        let data = await res.json();

        // ❌ VALIDATION ERROR
        if (!res.ok) {
            let errors = data.errors;
            let msg = '';

            for (let field in errors) {
                msg += errors[field][0] + '\n';
            }

            showToast(msg, 'danger');
            return;
        }

        // ✅ SUCCESS
        showToast(data.message, 'success');

        setTimeout(() => {
            let modal = bootstrap.Modal.getInstance(document.getElementById('poModal'));
            modal.hide();
        }, 1500);

        form.reset();
    })
    .catch(err => {
        console.log(err);
        showToast('Something went wrong!', 'danger');
    });
});

// 🔥 reusable toast function
function showToast(message, type = 'success') {
    let toastEl = document.getElementById('successToast');

    toastEl.classList.remove('text-bg-success', 'text-bg-danger');
    toastEl.classList.add(type === 'success' ? 'text-bg-success' : 'text-bg-danger');

    toastEl.querySelector('.toast-body').innerText = message;

    let toast = new bootstrap.Toast(toastEl);
    toast.show();
}
</script>