(function($){
	if($('#tipo_el').length > 0){
		var _pp_admin_reload = function(){
			$('.pp_tipo_titulo').keyup(function(e){
				$(this).closest('.pp_tipo_group').find('.pp_delete_tipo span').html($(this).val());
			});
		};
			$('.pp_delete_tipo').on('click', function(e){
				e.preventDefault();
				_e = +$(this).attr('e');

				$(this).closest('.pp_tipo_group').remove();

				return false;
			});

		$('#pp_create_tipo').on('click', function(){
			var _e = 1;
			if($('#tipo_el .pp_tipo_group').length > 0) _e = +$('#tipo_el .pp_tipo_group:last .pp_delete_tipo').attr('e') + 1;
			$('#tipo_el').append('<div class="pp_tipo_group" style="margin-bottom: 15px;">\
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;">\
							<input class="pp_tipo_titulo" type="text" name="pp_options[tipo'+_e+']" value="" style="width: 100%;">\
						</div>\
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;">\
							<input class="pp_tipo_pb" type="text" name="pp_options[precio_blanco'+_e+']" value="" style="width: 100%;">\
						</div>\
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;">\
							<input class="pp_tipo_pc" type="text" name="pp_options[precio_color'+_e+']" value="" style="width: 100%;">\
						</div>\
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;">\
							<input class="pp_tipo_pp" type="text" name="pp_options[precio_pantalla'+_e+']" value="" style="width: 100%;">\
						</div>\
						<a href="#" class="pp_delete_tipo" e="'+_e+'">Eliminar "<span></span>"</a>\
					</div>');
			return false;
		});
	}

	if($('#estampado_delantero, #estampado_trasero').length > 0){

		$('.pp_total').css('visibility', 'hidden');
		var confCantMin = $('#pp_tipo_minqty').val();
		var _letSend = false,
		
		updatePrice	= function(e){

			if($('#tamano_delantero').val() != '' || $('#tamano_trasero').val() != ''){
				var _val = $('#estampado_delantero').val()+' Tamaño '+$('#tamano_delantero').val()+'---'+$('#estampado_trasero').val()+' Tamaño '+$('#tamano_trasero').val();
			} else {
				var _val = '';
			}
			
			_action	= { 'action': 'get_prices', 'pp_pid': pp_pid, 'name': _val, 'embolsado': $('input[name=embolsado]:checked').val() };

			_action[$(this).attr('name')] = $(this).val(); //Valor del input actual.

			//var input = $('#fila-'+$(this).closest('div').attr('id'));

			/* Suma las cantidades por línea. */
		/*	var selector = $(this).closest('div').attr('id');
			var cantSelectores = 0;
			$('#'+selector+ ' select').each(function(){
				cantSelectores += parseInt($(this).val());
			});
				input.val(cantSelectores);*/
			/* Cantidades por talla */
			var cantidad = $(this).val();

			/* Variación del producto */
			var variacion = $(this).attr("name");

			var cantidadMinima = 0;
			/* Suma todas las cantidades. */
			$('#pp_sizes input').each(function(){
				if($(this).val() != 0){
					cantidadMinima += parseInt($(this).val());
				}
			});

			/* Activa el cajetín de presupuesto solo si
			* se alcanza la cantidad mínima.
			*/
			if(cantidadMinima >= confCantMin){

				$('.pp_total').css({'visibility':'visible'});
			} else {
				$('.pp_total').css({'visibility':'hidden'});
			}

			/* Petición Ajax */
			if(_letSend !== false)	_letSend.abort();

				_letSend = $.ajax({
					url: ajaxurl,
					method: "POST",
					data: _action,
					beforeSend: function() {
						$('#pp_form .pp_total .loading').stop(true).fadeIn(500);
					},
					success: function( response ) {
						var data = JSON.parse(response);
						//console.log(data);
						var tipo = data.color.toLowerCase();
						var talla = data.talla;
						var subTotal = parseFloat(data.subTotal);
						var pUnit = parseFloat(subTotal) / parseInt(cantidad);
						var precioFotolito = data.fotolito;

						/* Precio fotolitos */
						if(precioFotolito > 0 && $("#fotolito").length == 0) {//Crea nuevo item
							$('#tabla_precios').append('<tr id="fotolito"><td></td><td>Precio fotolitos</td><td></td><td></td><td></td><td class="text-right"><input id="fotoValor" type="text" value="'+precioFotolito+'" readonly></td></tr>');
						} else if(precioFotolito > 0 && $("#fotolito").length > 0){ //Actualiza las cantidades.
							$('#fotoValor').val(precioFotolito);
						} else {//Elimina item.
							$("#fotolito").remove();
						}	

						/* Añade o quita líneas del cajetín */
						if($("#" + tipo + '-' + talla).length == 0) {//Crea nuevo item
							$('#tabla_precios').append('<tr id="'+tipo+'-'+talla+'"><td><input class="desc" id="d-'+tipo+'-'+talla+'" type="hidden" value="' + data.description + '"></td><td>' + data.color + '</td><td>' + talla + '</td><td id="cant-' + tipo + '-' + talla + '">' + cantidad + '</td><td id="pu-' + tipo + '-' + talla + '">'+pUnit.toFixed(2)+'</td><td class="text-right"><input id="st-'+tipo+'-'+talla+'" type="text" value="'+subTotal+'" readonly></td></tr>');
						} else if($("#" + tipo + '-' + talla).length > 0 && cantidad > 0){ //Actualiza las cantidades.
							$('#cant-'+tipo+'-'+talla).html(cantidad);
							$('#d-'+tipo+'-'+talla).val(data.description);
							$('#st-'+tipo+'-'+talla).val(subTotal);
						} else {//Elimina item.
							$("#" + tipo + '-' + talla).remove();
						}

						/* Cálculo de la base imponible iva y total */
						var baseImp = 0;
						$('#tabla_precios input[type=text]').each(function(){
								baseImp += parseFloat($(this).val());
						});
						
						$('#totalPrice').html(baseImp.toFixed(2));
						$('#pp_price').val(baseImp.toFixed(2));
						$('#iva').html(parseInt(data.iva));


						var importeIva = (baseImp.toFixed(2) * data.iva)/100;

						$('#totalPriceIva').html( (parseFloat(baseImp.toFixed(2))+parseFloat(importeIva)).toFixed(2) );
						
						$('#pp_form .pp_total .loading').stop(true).fadeOut(0);
						
						_letSend = false;
					},
					error: function(err){
						console.log(err);
					}
				});
			
			
		};

		configurePrice = function(e){

			/* Valores de los estampados */
			if($('#tamano_delantero').val() != '' || $('#tamano_trasero').val() != ''){
				var _val = $('#estampado_delantero').val()+' Tamaño '+$('#tamano_delantero').val()+'---'+$('#estampado_trasero').val()+' Tamaño '+$('#tamano_trasero').val();
			} else {
				var _val = '';
			}
			

			/* Proceso de recálculo para cada item. */
			$('#pp_sizes input[type="number"]').each(function(){
				if($(this).val() != 0){//Calcula solo si hay valor.
					var cantidad = $(this).val();
					_action	= { 'action': 'get_prices', 'pp_pid': pp_pid, 'name': _val, 'embolsado': $('input[name=embolsado]:checked').val() };
					_action[$(this).attr('name')] = cantidad; //Valor del selector actual.
					
					$.ajax({
						url: ajaxurl,
						method: "POST",
						data: _action,
						beforeSend: function() {
							$('#pp_form .pp_total .loading').stop(true).fadeIn(500);
						},
						success: function( response ) {
							var data = JSON.parse(response);
	
							var tipo = data.color.toLowerCase();
							var talla = data.talla;
							var subTotal = data.subTotal;
							var pUnit = parseFloat(subTotal) / parseInt(cantidad);
							var precioFotolito = data.fotolito;
	
							if($("#" + tipo + '-' + talla).length > 0 && cantidad > 0){ //Actualiza las cantidades.
								$('#cant-'+tipo+'-'+talla).html(cantidad);
								$('#pu-'+tipo+'-'+talla).html(pUnit.toFixed(2));
								$('#st-'+tipo+'-'+talla).val(subTotal);
								$('#d-'+tipo+'-'+talla).val(data.description);
							}

							/* Precio fotolitos */
							if(precioFotolito > 0 && $("#fotolito").length == 0) {//Crea nuevo item
								$('#tabla_precios').append('<tr id="fotolito"><td></td><td>Precio fotolitos</td><td></td><td></td><td></td><td class="text-right"><input id="fotoValor" type="text" value="'+precioFotolito+'" readonly></td></tr>');
								$('#d-'+tipo+'-'+talla).val(data.description);
							} else if(precioFotolito > 0 && $("#fotolito").length > 0){ //Actualiza las cantidades.
								$('#fotoValor').val(precioFotolito);
							} else {//Elimina item.
								$("#fotolito").remove();
							}	

							/* Cálculo de la base imponible iva y total */
							var baseImp = 0;
							$('#tabla_precios input[type=text]').each(function(){
									baseImp += parseFloat($(this).val());
							});
							
							$('#totalPrice').html(baseImp.toFixed(2));
							$('#pp_price').val(baseImp.toFixed(2));
							$('#iva').html(parseInt(data.iva));

	
							var importeIva = (baseImp.toFixed(2) * data.iva)/100;
	
							$('#totalPriceIva').html( (parseFloat(baseImp.toFixed(2))+parseFloat(importeIva)).toFixed(2) );

							$('#pp_form .pp_total .loading').stop(true).fadeOut(0);

							_letSend = false;
							
						},
						error: function(err){
							console.log(err);
						}
					});
					
				}
			});
			
			
		};


		$('.pp_embolsado').change(configurePrice);//Evento al activar el embolsado.
		//$('#pp_sizes input').keyup(updatePrice);//Evento al seleccionar un artículo.
		$('#pp_sizes input').change(updatePrice);//Evento al seleccionar un artículo.
		$('#tamano_delantero, #tamano_trasero, #pp_sizes input').change(configurePrice);//Evento al seleccionar una estampación.
		$('#estampado_delantero, #estampado_trasero').change(function(){//Evento al seleccionar el tipo de estampación.
			var _val	= $(this).val(),
			_target		= $(this).attr('id') == 'estampado_delantero' ? 'tamano_delantero' : 'tamano_trasero';
			if(_letSend !== false)	_letSend.abort();
			$('#pp_form .pp_total .loading').stop(true).fadeIn(500);
			_letSend = $.post(ajaxurl, { 'action': 'get_sizes', 'pp_pid': pp_pid, 'name': _val }, function(response){
				if(_val == '')	$('#'+_target).attr('disabled', true).parent().addClass('disabled');
				else 			$('#'+_target).removeAttr('disabled').parent().removeClass('disabled');
				$('#'+_target).html(response);
					//		console.log(response);

				configurePrice();
			});
		});

		/* Envío de los datos al carrito */
		$('#pp_add_products').on('click', function(){

			var descriptionInput = $('#pp_description');
			$('.desc').each(function(){
				descriptionInput.val( descriptionInput.val() + $(this).val() );
			});

			if(_letSend === false){
				$('#pp_form .pp_total .loading').stop(true).fadeIn(500);

				_letSend = $.post(ajaxurl, 'action=add_products&'+$('#pp_form').serialize(), function(response){

					$('#pp_popup h3').html('Carrito de compras');
					$('#pp_popup p').html('El carrito se ha actualizado exitosamente.');
					$('#pp_popup .buttons .btn').hide();
					$('#pp_popup .buttons .compra').show();
					$('#pp_popup').stop(true).fadeIn(500);
					$('#pp_form .pp_total .loading').stop(true).fadeOut(0);
					
					//console.log(response);
				_letSend = false; 
				}); 
				
			}
			//console.log($('#pp_form').serialize());
			return false;
		});
		$('#pp_popup button').on('click', function(){
			$('#pp_popup').stop(true).fadeOut(500);
			return false;
		});
		$('.pp-presupuesto').on('click', function(){
			var _t = $('#pp_form').offset().top + ($('body').hasClass('admin-bar') ? -32 : 0);
			$('html, body').stop(true).animate({scrollTop: _t}, 500);
			return false;
		});
	}

	/* sticky div */
	if ($(window).width() > 1024) {
		
		$(window).scroll(function () {
			var marginTop = 0;
			if($(window).scrollTop() > 1150){
				marginTop = $(window).scrollTop() - 1150;
			}
			
			var limit = $(".sw_precio").height() - $(".pp_total").height();
			//console.log(limit + '-' + marginTop);
			if(marginTop < limit )
				$(".pp_total").css("top", marginTop);	
		});
	
	}

})(jQuery);
