@extends('template.app')

@section('title', 'Mesas')

@section('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid w-100">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-2 col-12">
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
        <div class="col-12 col-lg-2 mt-4">
          <a style="cursor: pointer;" class="btn btn-secondary form-control" onclick="filtrar();">
            <i class="fa fa-filter"></i>
            Filtrar
          </a>
        </div>
        <div class="col-12 col-lg-2 mt-4">
          <a style="cursor: pointer;" class="btn btn-primary form-control" onclick="editTable();">
            <i class="fa fa-plus"></i>
            Agregar
          </a>
        </div>
      </div>
      <h2 class="card-title">Reservas</h2>
      <div class="card-content row">
        <table class="table table-striped table-bordered display no-wrap" style="width:100%" id="table">
          <thead>
            <tr>
              <th scope="col">Mesa</th>
              <th scope="col">Nombre</th>
              <th scope="col">Descripción</th>
              <th scope="col">Espacios</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($mesas as $mesa)
              <tr>
                <th scope="row">{{$mesa->id}}</th>
                <td>{{$mesa->nombre}}</td>
                <td>{{$mesa->descripcion}}</td>
                <td>{{$mesa->espacios}}</td>
                <td class="text-center">
                  <a style="cursor: pointer;" onclick="editTable({{$mesa->id}}, '{{$mesa->nombre}}', '{{$mesa->descripcion}}', '{{$mesa->espacios}}');">
                    <i class="fas fa-edit text-primary"></i>
                  </a>
                  <a style="cursor: pointer;" onclick="deleteTable({{$mesa->id}});">
                    <i class="fas fa-trash text-danger"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalTable" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Mesa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="mesa_id">
        <div class="row">
          <div class="col-12">
            <label for="nom">Nombre</label>
            <input type="text" class="form-control" id="nom">
          </div>

          <div class="col-12 mt-2">
            <label for="espacios">Espacios</label>
            <input type="number" min="1" max="4" value="1" class="form-control" id="espacios">
          </div>

          <div class="col-12 mt-2">
            <label for="desc">Descripción</label>
            <input type="text" id="desc" class="form-control">
          </div>

          <div class="col-12 mt-3" id="reserva_content">
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="send();" id="botonModal">Buscar</button>
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

    const editTable = (id = null, nombre = null, descripcion = null, espacios = null) => {
      $("#modalTable").modal('show');

      $("#mesa_id").val(id);
      $("#nom").val(nombre);
      $("#espacios").val(espacios);
      $("#desc").val(descripcion);

      $("#botonModal").html(id != null? "Editar" : "Registrar");
    };

    const send = async () => {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      await fetch(`{{route('appMesasEdit')}}`, {
        method: 'POST',
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": token
        },
        body: JSON.stringify({
          nom: $("#nom").val(),
          mesa_id: $("#mesa_id").val(),
          espacios: $("#espacios").val(),
          desc: $("#desc").val()
        })
      })
      .then(res => res.json())
      .then(res => {
        let title = "¡Bien!";
        let text = res.message;
        let icon = "success";

        console.log(res);

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
    };
  </script>
@endsection