<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Sabor a México')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

  <style>
    .sidebar {
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      background-image: url('{{ asset('images/sidebar.jpg') }}');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      padding-top: 20px;
      color: white;
    }
    .sidebar a {
      color: black;
      text-decoration: none;
      padding: 10px 15px;
      display: block;
    }
    .sidebar a:hover {
      background: linear-gradient(90deg, rgba(0,104,71,.5) 0%, rgba(255,255,255,.5) 35%, rgba(206,17,38,.5) 100%);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    }
    .resaltado {
      background: rgba(255, 255, 255, 0.2); /* Fondo semitransparente */
      border: 1px solid rgba(255, 255, 255, 0.3); /* Borde sutil */
      border-radius: 0 50px 50px 0; /* Forma redondeada */
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25); /* Sombra ligera */
      backdrop-filter: blur(10px); /* Difumina el fondo detrás del botón */
      padding: 15px 30px;
      font-size: 16px;
      color: white;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .content {
      margin-left: 250px;
      padding: 20px;
    }
  </style>

@yield('css')
</head>
<body>

  <!-- Menú lateral -->
  <div class="sidebar">
    <h3 class="text-white text-center">Sabor a México</h3>
    <a href="{{ route('appIndex') }}" class="resaltado">Reservas</a>
    <a href="{{ route('appMesas') }}" class="resaltado mt-2">Mesas</a>
  </div>

  <!-- Contenido principal -->
  <div class="content">
    <!-- Barra superior -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">¡Bienbenido a Sabor a México!</a>
        <div class="d-flex">
        </div>
      </div>
    </nav>

    <!-- Contenido de la página -->
    <div class="container">
      {{-- <a href="#" class="">adsdasd</a> --}}
      @yield('content')
    </div>

    <footer class="footer text-center text-muted">
      Diseñado y Desarrollado por <a href="#" target="_blank">César Ramírez</a>.
  </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>


  @yield('js')
</body>
</html>