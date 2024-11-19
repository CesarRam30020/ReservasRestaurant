@extends('template.app')

@section('title', 'Reservas')

@section('css')
  <style>
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

    .day.inactive {
      color: #aaa;
      pointer-events: none;
    }

    .day.expanded {
      grid-column: 1 / -1;
      grid-row: 1 / -1;
      font-size: 2em;
    }
  </style>
@endsection

@section('content')
  <div class="container-fluid w-100">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-3 col-12">
            <label for="comensales">No. Comensales</label>
            <input type="number" class="form-control" min="0" max="4" id="comensales" value="{{$comensales??0}}">
          </div>
          <div class="col-lg-4 col-12">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" id="fecha" value="{{$fecha}}">
          </div>
          <div class="col-lg-3 col-12">
            <label for="estatus">Estatus</label>
            <select name="estatus" id="estatus" class="form-control">
              <option value="-1">Selecciona un estatus</option>
              <option value="A"@if ($estatus == 'A') selected @endif>Activa</option>
              <option value="T"@if ($estatus == 'T') selected @endif>Terminada</option>
              <option value="C"@if ($estatus == 'C') selected @endif>Cancelada</option>
            </select>
          </div>
          <a style="cursor: pointer;" class="btn btn-secondary col-lg-2 col-12 mt-4" onclick="filtrar();">
            <i class="fa fa-filter"></i>
            Filtrar
          </a>
        </div>
        <h2 class="card-title">Reservas</h2>
        <div class="card-content row">
          <table class="table table-striped table-bordered display no-wrap" style="width:100%" id="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Mesa</th>
                <th scope="col">Comensales</th>
                <th scope="col">Fecha y Hora</th>
                <th scope="col">Estatus</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($reservas as $reserva)
                <tr>
                  <th scope="row">{{$reserva->id}}</th>
                  <td>{{$reserva->mesa_id}}</td>
                  <td>{{$reserva->comensales}}</td>
                  <td>{{\Carbon\Carbon::parse($reserva->fecha_hora)->format('d/m/Y h:m:i a')}}</td>
                  <td>
                    @switch($reserva->estatus)
                      @case('C')
                        Cancelada
                        @break
                      @case('T')
                        Terminada
                        @break
                      @default
                        Activa
                    @endswitch
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script>
    $('#table').DataTable();

    const filtrar = () => {
      const comensales = document.getElementById('comensales').value;
      const fecha = document.getElementById('fecha').value;
      const estatus = document.getElementById('estatus').value;
      window.location.href = `{{ route('appIndex') }}?comensales=${comensales}&fecha=${fecha}&estatus=${estatus}`;
    };
  </script>
@endsection