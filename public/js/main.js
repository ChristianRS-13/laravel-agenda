
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

    			events:url_show

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
					url: url_ + accion,
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