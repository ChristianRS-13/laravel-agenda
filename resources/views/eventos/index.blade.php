@extends ('layouts.app')

@section('scripts')

	<link rel="stylesheet" href="{{ asset('fullcalendar/core/main.css') }}" >
	<link rel="stylesheet" href="{{ asset('fullcalendar/daygrid/main.css') }}" >
	<link rel="stylesheet" href="{{ asset('fullcalendar/list/main.css') }}" >
	<link rel="stylesheet" href="{{ asset('fullcalendar/timegrid/main.css') }}" >
	

	<script src="{{ asset('fullcalendar/core/main.js') }}" defer></script>
	<script src="{{ asset('fullcalendar/interaction/main.js') }}" defer></script>
	<script src="{{ asset('fullcalendar/daygrid/main.js') }}" defer></script>
	<script src="{{ asset('fullcalendar/list/main.js') }}" defer></script>
	<script src="{{ asset('fullcalendar/timegrid/main.js') }}" defer></script>
	
	<script>
		var url_='{{ url("/eventos") }}';
		var url_show='{{ url("/eventos/show") }}';
	</script>
	<script src="{{ asset('js/main.js') }}" defer></script>
	
@endsection

@section('content')
	<div class="row">
		<div class="col"></div>
		<div class="col-7">
			<div id="calendar"></div>			
		</div>
		<div class="col"></div>
	</div>

	{{-- Modal --}}
	<div class="modal" tabindex="-1" role="dialog" id="myModal">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Datos del evento</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <input type="text" name="txtID" id="txtID" readonly hidden="true">
	        <input type="text" name="txtFecha" id="txtFecha" hidden="true">

	        <div class="form-row">

	        	<div class="form-group col-md-8">
					<label for="">Titulo:</label>
		        	<input type="text" class="form-control" name="txtTitulo" id="txtTitulo">	        		
	        	</div>
		        
		        <div class="form-group col-md-4">
			        <label for="">Hora:</label>
			        <input type="time" min="08:00" max="20:00" step="600" class="form-control" name="txtHora" id="txtHora">	        
			    </div>

			    <div class="form-group col-md-12">
			        <label for="">Descripcion:</label>
			        <textarea name="txtDescripcion" class="form-control" id="txtDescripcion" cols="30" rows="5"></textarea>
			    </div>

			    <div class="form-group col-md-12">
			        <label for="">Color:</label>
			        <input type="color" class="form-control" name="txtColor" id="txtColor">
			    </div>

	        </div>
	      </div>
	      <div class="modal-footer">
	      	<button id="btnAgregar" class="btn btn-success">Agregar</button>
	      	<button id="btnModificar" class="btn btn-success">Modificar</button>
	      	<button id="btnEliminar" class="btn btn-danger">Borrar</button>
	      	<button id="btnCancelar" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
	      </div>
	    </div>
	  </div>
	</div>
@endsection