<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Sabor a México</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      overflow-x: hidden;
    }

    .hero {
      position: relative;
      height: 100vh;
      background-image: url('{{ asset('images/interior.png') }}');
      background-size: cover;
      /* background-position: center; */
      background-repeat: no-repeat;
    }

    .hero-content {
      position: absolute;
      top: 50%;
      left: 10%; /* Ajusta según el diseño */
      transform: translateY(-50%);
      color: white;
    }

    .hero-content h1 {
      font-size: 3rem;
      font-weight: bold;
    }

    .hero-content ul {
      list-style: none;
      padding: 0;
    }

    .hero-content ul li {
      margin: 10px 0;
    }

    .hero-content ul li a {
      color: white;
      text-decoration: none;
      font-size: 1.2rem;
      transition: color 0.3s;
    }

    .hero-content ul li a:hover {
      color: green; /* Color dorado al pasar el mouse */
    }

    /* Estilos calendario */
    .calendar-container {
      display: grid;
      grid-template-columns: repeat(7, 1fr); /* 7 columnas para los días */
      gap: 10px;
      padding: 20px;
      text-align: center;
    }

    .calendar-header {
      grid-column: span 7; /* Abarca las 7 columnas */
      font-weight: bold;
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .day {
      background-color: #f8f9fa; /* Fondo claro */
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 5px;
      cursor: pointer;
      transition: 0.3s;
    }

    .day:hover {
      background-color: #f4f4f4;
      transform: scale(1.05);
    }

    .day.selected {
      background-color: #007bff;
      color: white;
    }

    .day.inactive {
      color: #aaa;
      pointer-events: none;
    }

    .loginButton {
      float: right;
      padding-right: 2%;
      padding-top: 2%;
      color: #ffffff;
    }
  </style>
</head>
<body>
  <!-- Hero Section -->
  <div class="hero">
    <a href="{{route('login')}}" class="loginButton">
      <i class="fa-solid fa-circle-arrow-right"></i>
    </a>
    <div class="hero-content">
      <!-- Logo o Nombre -->
      <h1>Sabor a México</h1>
      
      <!-- Opciones -->
      <ul>
        <li><a href="#reservar">Reservas</a></li>
        <li><a href="#donde_estamos">Dónde estamos</a></li>
        <li><a href="#FAQs">FAQs</a></li>
        <li><a href="#contacto">Contacto</a></li>
        {{-- <li><a href="#">Idioma</a></li> --}}
      </ul>
    </div>
  </div>
  
  <div class="container mt-5" id="reservar">
    <div class="card">
      <div class="card-body">
        <div class="row">
            <h3 class="card-title col-11">Reservaciones</h3>
            <a style="cursor: pointer;" class="col-1 btn btn-rounded btn-xm btn-primary" onclick="showSearchModal();">
              <i class="fas fa-search"></i>
            </a>
          </div>
        <div class="row">
          <div class="col-12">
            <div class="calendar-header text-center">
              <a id="prevMonth" class="btn btn-sm btn-secondary"><</a>
              <span id="monthYear"></span>
              <a id="nextMonth" class="btn btn-sm btn-secondary">></a>
            </div>
            <div class="calendar-container" id="calendar">
              <!-- Aquí se generará el calendario dinámicamente -->
            </div>

            <input type="hidden" value="1" id="dia">
          </div>

          <div class="col-12">
            <label for="comensales">Comensales</label>
            <select name="comensales" id="comensales" class="form-control">
              <option>1</option>
              <option selected>2</option>
              <option>3</option>
              <option>4</option>
            </select>
          </div>

          <div class="col-12 mt-2">
            <label for="hora">Hora</label>
            <select name="hora" id="hora" class="form-control" onchange="">
              @for ($h = 9; $h < 23; $h++)
                @for ($m = 0; $m < 60; $m+=15)
                <option>
                  {{ sprintf('%02d', $h) }}:{{ sprintf('%02d', $m) }}
                </option>
                @endfor
              @endfor
            </select>
          </div>

          <div class="col-12 mt-2 text-center">
            <button class="col-12 mt-2 btn btn-primary" onclick="enviar();">Reservar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container mt-5" id="donde_estamos">
    <h3>Donde encontrarnos</h3>
    <p>
      Nos puedes encontrar de lunes a domingo de 9:00am a 11:00pm en Av. 16 de Septiembre 710, Mexicaltzingo, 44180 Guadalajara, Jal.
    </p>
    <div id="map" style="width: 100%; height: 500px;"></div>
  </div>

  <hr>

  <div class="container mt-5" id="FAQs">
    <h3>FAQs</h3>
    <p>Hoy abrí un restaurante ficticio para pasar la materia de Programación para internet, hagan sus preguntas</p>
    <div class="row">
      <button class="col-12 mt-2 btn row" style="background-color: lightgray;" data-bs-toggle="collapse" data-bs-target="#quien" aria-expanded="false" aria-controls="quien">
        ¿Quien abrio un restaurante ficticio?
        <i class="fa-solid fa-chevron-down col-1"></i>
        <div id="quien" class="collapse">
          <div class="card-body">
            Yo.
          </div>
        </div>
      </button>

      <button class="col-12 mt-2 btn row" style="background-color: lightgray;" data-bs-toggle="collapse" data-bs-target="#despues" aria-expanded="false" aria-controls="despues">
        ¿Qué hiciste despues?
        <i class="fa-solid fa-chevron-down col-1"></i>
        <div id="despues" class="collapse">
          <div class="card-body">
            Deje de atender a la gente.
          </div>
        </div>
      </button>

      <button class="col-12 mt-2 btn row" style="background-color: lightgray;" data-bs-toggle="collapse" data-bs-target="#abierto" aria-expanded="false" aria-controls="abierto">
        ¿Se quedará abierto?
        <i class="fa-solid fa-chevron-down col-1"></i>
        <div id="abierto" class="collapse">
          <div class="card-body">
            Al parecer si.
          </div>
        </div>
      </button>

      <button class="col-12 mt-2 btn row" style="background-color: lightgray;" data-bs-toggle="collapse" data-bs-target="#tio" aria-expanded="false" aria-controls="tio">
        ¿Era tu tio?
        <i class="fa-solid fa-chevron-down col-1"></i>
        <div id="tio" class="collapse">
          <div class="card-body">
            Estaba seguro a un 25.19% seguro que no era el.
          </div>
        </div>
      </button>

      <button class="col-12 mt-2 btn row" style="background-color: lightgray;" data-bs-toggle="collapse" data-bs-target="#sucursales" aria-expanded="false" aria-controls="sucursales">
        ¿Tendrán más sucursales?
        <i class="fa-solid fa-chevron-down col-1"></i>
        <div id="sucursales" class="collapse">
          <div class="card-body">
            Espero que si.
          </div>
        </div>
      </button>

      <button class="col-12 mt-2 btn row" style="background-color: lightgray;" data-bs-toggle="collapse" data-bs-target="#volver" aria-expanded="false" aria-controls="volver">
        ¿Lo volverías a hacer?
        <i class="fa-solid fa-chevron-down col-1"></i>
        <div id="volver" class="collapse">
          <div class="card-body">
            <img src="{{asset('images/si.jpg')}}" alt="">
          </div>
        </div>
      </button>
    </div>
  </div>

  <div class="container mt-5" id="contacto">
    <h3 class="mb-4">Contacto</h3>
    <div class="row">
      <!-- Teléfono -->
      <div class="col-md-4 text-center mb-3">
        <i class="fas fa-phone fa-2x mb-2"></i>
        <h5>Teléfono</h5>
        <p><a href="#contacto" class="text-decoration-none text-dark">+52 33 33 33 33 33</a></p>
      </div>
      <!-- Correo -->
      <div class="col-md-4 text-center mb-3">
        <i class="fas fa-envelope fa-2x mb-2"></i>
        <h5>Correo Electrónico</h5>
        <p><a href="#contacto" class="text-decoration-none text-dark">contacto@ejemplo.com</a></p>
      </div>
      <!-- Redes Sociales -->
      <div class="col-md-4 text-center">
        <i class="fas fa-share-alt fa-2x mb-2"></i>
        <h5>Redes Sociales</h5>
        <div class="d-flex justify-content-center gap-3">
          <a href="https://facebook.com" target="_blank" class="text-decoration-none text-dark">
            <i class="fab fa-facebook fa-2x"></i>
          </a>
          <a href="https://instagram.com" target="_blank" class="text-decoration-none text-dark">
            <i class="fab fa-instagram fa-2x"></i>
          </a>
          <a href="https://tiktok.com" target="_blank" class="text-decoration-none text-dark">
            <i class="fab fa-tiktok fa-2x"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="searchBookingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Buscador de reservas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <label for="no_reserva">#Reserva</label>
              <input type="text" class="form-control" id="no_reserva">
            </div>

            <div class="col-12 mt-3" id="reserva_content">
              
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="searchBooking();">Buscar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    // Variables globales
    const calendar = document.getElementById("calendar");
    const monthYear = document.getElementById("monthYear");
    const map = L.map('map').setView([20.66647, -103.35369], 20);
    let currentDate = new Date();
    let daySelected = 0;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
  
    L.marker([20.66647, -103.35369]).addTo(map)
      .bindPopup('<b>Sabor a México</b><br>Guadalajara, Jalisco.')
      .openPopup();

    const update = () => {
      data.customers = document.getElementById("comensales").value;
      data.hout = document.getElementById("hora").value;
    }

    // Función para generar el calendario
    const generateCalendar = (date) => {
      calendar.innerHTML = ""; // Limpiar el contenido del calendario
      const year = date.getFullYear();
      const month = date.getMonth();
      const firstDay = new Date(year, month, 1).getDay(); // Día de la semana del primer día
      const daysInMonth = new Date(year, month + 1, 0).getDate(); // Días del mes

      // Actualizar encabezado
      const months = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
      ];
      monthYear.textContent = `${months[month]} ${year}`;

      // Rellenar días vacíos antes del primer día
      for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement("div");
        emptyCell.classList.add("day", "inactive");
        calendar.appendChild(emptyCell);
      }

      // Generar días del mes
      for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement("div");
        dayCell.classList.add("day");
        dayCell.textContent = day;
        dayCell.addEventListener("click", () => {
          document.querySelectorAll(".day").forEach(d => d.classList.remove("selected"));
          dayCell.classList.add("selected");
          document.getElementById('dia').value = day;
          daySelected = `${year}-${month+1}-${day}`;
        });
        calendar.appendChild(dayCell);
      }
    };

    const enviar = async () => {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const hora = document.getElementById('hora').value;
      
      await fetch(`{{ route('reservar') }}`, {
        method: 'POST',
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": token
        },
        body: JSON.stringify({
          date: daySelected + ` ${hora}`,
          customers: document.getElementById('comensales').value,
        })
      })
      .then(res => res.json())
      .then(res => {
        let title = '¡Error!';
        let text = res.message;
        let icon = 'error';

        if (res.code == 200) {
          title = '¡Bien!';
          icon = 'success';
        }

        Swal.fire({
          title: title,
          text: text,
          icon: icon,
          confirmButtonText: 'Aceptar'
        });
      });
    };

    const showSearchModal = () => {
      var myModal = new bootstrap.Modal(document.getElementById('searchBookingModal'));
      myModal.show();
    };

    const searchBooking = async () => {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const no_reserva = document.getElementById('no_reserva').value;
      const  url = "{{ route('reserva', ':id') }}".replace(':id', no_reserva);

      await fetch(url)
      .then(res => res.json())
      .then(res => {
        if (res.code == 400) {
          Swal.fire({
            title: '¡Error!',
            text: res.message,
            icon: 'error',
            confirmButtonText: 'Aceptar'
          });
          return;
        }

        const contenidoReserva = document.getElementById('reserva_content');
        contenidoReserva.innerHTML = "";
        
        const reserva = res.data;

        const card = document.createElement('div');
        const row = document.createElement('div');
        const cardBody = document.createElement('div');
        const cancelar = document.createElement('div');
        const cardTitle = document.createElement('h4');
        const cardContent = document.createElement('div');
        let estatus = "Activa";
        if (reserva.estatus == 'C') estatus = "Cancelada";
        else if (reserva.estatus == "T") estatus = "Terminado";

        const contenido = `Comensales: ${reserva.comensales}
        <br>Fecha y hora: ${reserva.fecha_hora}
        <br>Estatus: ${estatus}`;

        card.classList.add('card');
        row.classList.add('row');
        cancelar.classList.add('col-1');
        
        cardBody.classList.add('card-body');
        cardBody.classList.add('col-11');

        cardTitle.classList.add('card-title');
        cardTitle.innerHTML = `#Reserva ${reserva.id}`;

        cardContent.classList.add('card-content');

        cardContent.innerHTML = contenido;
        cancelar.innerHTML = `<a style="cursor: pointer;" class="text-danger" onclick="cancelBooking(${reserva.id});"><i class="fas fa-close"></i></a>`;
        cardBody.appendChild(cardTitle);
        cardBody.appendChild(cardContent);
        row.appendChild(cardBody);
        row.appendChild(cancelar);
        card.appendChild(row);

        contenidoReserva.appendChild(card);
      });
    };

    const cancelBooking = (id) => {
      Swal.fire({
        title: '¡¡¡Advertencia!!!',
        text: '¿Estas seguro de que quieres cancelar tu reservación?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
      }).then(result => {
        if (result.isConfirmed) {
          cancel(id);
        }
      });
    };

    const cancel = async (id) => {
      const hora = document.getElementById('hora').value;
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      await fetch(`{{ route('reservaCancelar') }}`, {
        method: 'POST',
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": token
        },
        body: JSON.stringify({
          reserva_id: id
        })
      })
      .then(res => res.json())
      .then(res => {
        let title = "¡Bien!";
        let text = res.message;
        let icon = "success";

        if (res.code != 200) {
          title = '¡Lo sentimos!';
          icon = 'error';
        }

        Swal.fire({
          title: title,
          text: text,
          icon: icon,
          confirmButtonText: 'Aceptar'
        }).then((result) => {
          if (result.isConfirmed) {
            location.reload();
          }
        });
      });
    }

    // Eventos para cambiar de mes
    document.getElementById("prevMonth").addEventListener("click", () => {
      currentDate.setMonth(currentDate.getMonth() - 1);
      generateCalendar(currentDate);
    });

    document.getElementById("nextMonth").addEventListener("click", () => {
      currentDate.setMonth(currentDate.getMonth() + 1);
      generateCalendar(currentDate);
    });

    // Generar el calendario al cargar la página
    generateCalendar(currentDate);
  </script>
</body>
</html>
