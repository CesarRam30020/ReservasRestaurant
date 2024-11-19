@extends('template.app')

@section('title', 'Mesas')

@section('css')
  
@endsection

@section('content')
<div class="container-fluid w-100">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-3 col-12">
          <label for="comensales">No. Comensales</label>
          <input type="number" class="form-control" min="0" max="4" id="comensales" value="0">
        </div>
        <div class="col-lg-3 col-12">
          <label for="nombre">Nombre</label>
          <input type="text" class="form-control" id="nombre" list="mesas">
          <datalist id="mesas">
            @foreach ($mesas->pluck('nombre') as $mesa)
              <option>{{$mesa}}</option>
            @endforeach
          </datalist>
        </div>
        <div class="col-lg-3 col-12">
          <label for="descripcion">Descripción</label>
          <input type="text" class="form-control" id="descripcion">
        </div>
        <a style="cursor: pointer;" class="btn btn-secondary col-lg-3 col-12 mt-4" onclick="filtrar();">
          <i class="fa fa-filter"></i>
          Filtrar
        </a>
      </div>
      <h2 class="card-title">Reservas</h2>
      <div class="card-content row">
        <table class="table table-striped table-bordered display no-wrap" style="width:100%" id="table">
          <thead>
            <tr>
              <th scope="col">Mesa</th>
              <th scope="col">Nombre</th>
              <th scope="col">Descripción</th>
              <th scope="col">Comensales</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($mesas as $mesa)
              <tr>
                <th scope="row">{{$mesa->id}}</th>
                <td>{{$mesa->nombre}}</td>
                <td>{{$mesa->descripcion}}</td>
                <td>{{$mesa->espacios}}</td>
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
      const comensales = $('#comensales').val();
      const nombre = $("#nombre").val();
      const descripcion = $("#descripcion").val();
      window.location.href = `{{ route('appMesas') }}?comensales=${comensales}&nombre=${nombre}&descripcion=${descripcion}`;
    };
  </script>
@endsection