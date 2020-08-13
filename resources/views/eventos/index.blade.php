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
		
		document.addEventListener('DOMContentLoaded', function(){
			var calendarEl = document.getElementById('calendar');

			var calendar = new FullCalendar.Calendar(calendarEl,{

				defaultDay : new Date(2020,7,20),
				plugins: ['dayGrid', 'interaction', 'timeGrid', 'list'],

				header: {
					left: 'prev,next, today',
					center: 'title',
					right: 'dayGridMonth, timeGridWeek, timeGridDay'
				},

				defaultView: 'dayGridMonth',

				dateClick: function(info) {

					limpiarFormulario();
					$('#txtFecha').val(info.dateStr)
					$('#btnAgregar').prop('disabled', false);
					$('#btnModificar').prop('disabled', true);
					$('#btnEliminar').prop('disabled', true);
    				$('#myModal').modal('show')

    			},

    			eventClick:function(info){

    				$('#txtID').val(info.event.id);
    				$('#txtTitulo').val(info.event.title);
    				$('#txtDescripcion').val(info.event.extendedProps.descripcion);
    				$('#txtColor').val(info.event.backgroundColor);

    				anio = (info.event.start.getFullYear());
    				mes = (info.event.start.getMonth()+1);    			
    				dia= (info.event.start.getDate());

					minutos=info.event.start.getMinutes();
					hora=info.event.start.getHours();

					minutos=(minutos<10)?"0"+minutos:minutos;
    				hora=(hora<10)?"0"+hora:hora;

    				horario = (hora+":"+minutos);

    				mes=(mes<10)?"0"+mes:mes;
    				dia=(dia<10)?"0"+dia:dia;

    				$('#txtFecha').val(anio+"-"+mes+"-"+dia);
    				$('#txtHora').val(horario);

					$('#btnAgregar').prop('disabled', true);
					$('#btnModificar').prop('disabled', false);
					$('#btnEliminar').prop('disabled', false);

    				$('#myModal').modal()	
    				
    			},

    			events:'{{ url("/eventos/show") }}'

			});

			calendar.setOption('locale', 'es');

			calendar.render();

			$('#btnAgregar').click(function(){
				objEvento=recolectarDatosGUI('POST');
				enviarInformacion('', objEvento);
			});

			$('#btnEliminar').click(function(){
				objEvento=recolectarDatosGUI('DELETE');
				enviarInformacion('/'+$('#txtID').val(), objEvento);
			});

			$('#btnModificar').click(function(){
				objEvento=recolectarDatosGUI('PATCH');
				enviarInformacion('/'+$('#txtID').val(), objEvento);
			});

			function recolectarDatosGUI(method){
				nuevoEvento={
					id: $('#txtID').val(),
					title: $('#txtTitulo').val(),
					descripcion: $('#txtDescripcion').val(),
					color: $('#txtColor').val(),
					textColor: '#FFFFFF',
					start: $('#txtFecha').val()+' '+$('#txtHora').val(),
					end: $('#txtFecha').val()+' '+$('#txtHora').val(),
					'_token':$("meta[name='csrf-token']").attr("content"),
					'_method':method
				}

				// console.log(nuevoEvento);
				return nuevoEvento;
			};

			function enviarInformacion(accion, objEvento){
				$.ajax({
					type: 'POST',
					url: '{{ url("/eventos") }}' + accion,
					data: objEvento,
					success: function(msg){
						// console.log(msg);
						$('#myModal').modal("toggle");
						calendar.refetchEvents();

					},
					error: function(){alert('Hay un error');}
				});
			}

			function limpiarFormulario(){
				$('#txtID').val("");
				$('#txtTitulo').val("");
				$('#txtDescripcion').val("");
				$('#txtColor').val("");
				$('#txtFecha').val("");
				$('#txtHora').val("07:00");

			}

		});

	</script>

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