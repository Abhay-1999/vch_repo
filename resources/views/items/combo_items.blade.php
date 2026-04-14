<div class="row">
@foreach($comboItems as $item)
  <div class="col-md-4 mb-3">
    <div class="card p-2 text-center">
     <img src="{{ route('image.show', [$item->item_code, $item->item_grpcode]) }}"
     alt="{{ $item->item_desc }}"
     class="card-img-top"
     loading="lazy"
     width="50" height="50">
      <h6>{{ $item->item_desc }}</h6>
      <p>₹ {{ $item->item_rate }}</p>

      <button class="btn btn-success add-combo-item" 
              data-id="{{ $item->item_code }}">
        Add
      </button>
    </div>
  </div>
@endforeach
</div>