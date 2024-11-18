<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Sabor a México</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      overflow-x: hidden;
    }

    .hero {
      position: relative;
      height: 100vh;
      background-image: url('images/interior.png');
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
  </style>
</head>
<body>

  <!-- Hero Section -->
  <div class="hero">
    <div class="hero-content">
      <!-- Logo o Nombre -->
      <h1>Sabor a México</h1>
      
      <!-- Opciones -->
      <ul>
        <li><a href="#reservar">Reservas</a></li>
        <li><a href="#">Dónde estamos</a></li>
        <li><a href="#">FAQs</a></li>
        <li><a href="#">Contacto</a></li>
        {{-- <li><a href="#">Idioma</a></li> --}}
      </ul>
    </div>
  </div>
  
  <div class="container mt-5" id="reservar">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Reservaciones</h3>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Variables globales
    const calendar = document.getElementById("calendar");
    const monthYear = document.getElementById("monthYear");
    let currentDate = new Date();

    let daySelected = 0;

    const update = () => {
      data.customers = document.getElementById("comensales").value;
      data.hout = document.getElementById("hora").value;
    }

    // Función para generar el calendario
    function generateCalendar(date) {
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
    }

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
      .then(res => {
        if (!res.ok)
          throw new Error('Ocurrio un error');

        return res.json();
      })
      .then(res => {
        alert(`La respuesta es ${res}`);
      })
      .catch(error => {
        alert(error);
      });
    };

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
