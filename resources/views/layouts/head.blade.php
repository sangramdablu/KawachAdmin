<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'KawachTech Solutions')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.core.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
  @php
    $__broadcastDriver = config('broadcasting.default');
    $__broadcastConn = config("broadcasting.connections.$__broadcastDriver", []);
    $__broadcastConfig = [
        'key'     => $__broadcastConn['key'] ?? null,
        'host'    => $__broadcastConn['options']['host'] ?? null,
        'port'    => $__broadcastConn['options']['port'] ?? null,
        'scheme'  => $__broadcastConn['options']['scheme'] ?? null,
        'cluster' => $__broadcastConn['options']['cluster'] ?? null,
    ];
  @endphp
  @auth
    @if(in_array($__broadcastDriver, ['reverb', 'pusher']))
      <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
      <script>
        window.__authUserId = {{ auth()->id() }};
        window.__broadcastDriver = '{{ $__broadcastDriver }}';
        window.__broadcastConfig = @json($__broadcastConfig);
      </script>
    @endif
  @endauth
  <script>
    (function () {
      const theme = localStorage.getItem('theme');
      if (theme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
      } else {
        document.documentElement.setAttribute('data-theme', 'light');
      }
    })();
  </script>
  <script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    });
  </script>

</head>