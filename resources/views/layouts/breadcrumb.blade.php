<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6"><h1>{{ $breadcrumb->title }}</h1></div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        @foreach ($breadcrumb->list as $key => $value)
      @if (is_array($value))
          <li class="breadcrumb-item">{{ htmlspecialchars($value['name']) }}</li>
      @else
          <li class="breadcrumb-item">{{ htmlspecialchars($value) }}</li>
      @endif
        @endforeach
      </ol>
    </div>
  </div>
</div>
</section>
>>>>>>> 36f4efc281f5e42587aed3203a0bb4c0346bab32
