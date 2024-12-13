<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('/profile') }}" class="nav-link">Profile</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Di layout navbar -->
      @php
      $notification = \App\Models\NotifikasiModel::where('user_id', auth()->user()->user_id)
          ->where('is_read', false)
          ->orderBy('created_at', 'desc')
          ->get();
      @endphp
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">{{ $notification->count() }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">{{ $notification->count() }} Notifikasi</span>
            <div class="dropdown-divider"></div>
            @foreach($notification as $notif)
                <a href="{{ $notif->type === 'pengajuan_peserta' ? url('/acc_daftar') : url('/list_pelatihan') }}" 
                  class="dropdown-item notification-item" 
                  data-notification-id="{{ $notif->id }}">
                    <i class="fas fa-{{ $notif->type === 'pengajuan_peserta' ? 'user-plus' : 'check' }} mr-2"></i>
                    {{ Str::limit($notif->message, 40) }}
                    <span class="float-right text-muted text-sm">
                        {{ $notif->created_at->diffForHumans() }}
                    </span>
                </a>
                <div class="dropdown-divider"></div>
            @endforeach
            @if($notification->count() > 0)
                <a href="#" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
            @else
                <a href="#" class="dropdown-item">Tidak ada notifikasi baru</a>
            @endif
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
