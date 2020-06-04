(function($){
	if($('#tipo_el').length > 0){
		var _pp_admin_reload = function(){
			$('.pp_tipo_titulo').keyup(function(e){
				$(this).closest('.pp_tipo_group').find('.pp_delete_tipo span').html($(this).val());
			});
		};
			$('.pp_delete_tipo').on('click', function(e){
				e.preventDefault();
				//var _ne	= 1,
				_e = +$(this).attr('e');
				//console.log(_e);
				$(this).closest('.pp_tipo_group').remove();
			/*	$('.pp_tipo_group').each(function(){
					$(this).find('.pp_delete_tipo').attr('e', _ne);
					$(this).find('.pp_tipo_pc').attr('name', 'precio_color[tipo'+_ne+']');
					$(this).find('.pp_tipo_pb').attr('name', 'precio_blanco[tipo'+_ne+']');
					$(this).find('.pp_tipo_titulo').attr('name', 'pp_options[tipo'+_ne+']');
					$(this).find('.pp_tipo_pp').attr('name', 'precio_pantalla[tipo'+_ne+']');
					_ne++;
				});*/
				return false;
			});
	//	};
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
			//_pp_admin_reload();
			return false;
		});
	}

	if($('#estampado_delantero, #estampado_trasero').length > 0){

		$('.pp_total').css('display', 'none');
		var confCantMin = $('#pp_tipo_minqty').val();

		var _letSend	= false,
		
		updatePrice		= function(e){
			var _val	= $('#estampado_delantero').val()+' Tamaño '+$('#tamano_delantero').val()+'---'+$('#estampado_trasero').val()+' Tamaño '+$('#tamano_trasero').val();

			_action		= { 'action': 'get_prices', 'pp_pid': pp_pid, 'name': _val, 'embolsado': $('input[name=embolsado]:checked').val() };

			var cantidadMinima = 0;
			$('#pp_sizes input').each(function(){
				
				if($(this).val() != 0){
					_action[$(this).attr('name')] = $(this).val();
					cantidadMinima += parseInt($(this).val());
				}
			});

			if(_letSend !== false)	_letSend.abort();

			if(cantidadMinima >= confCantMin){

				$('.pp_total').css('display', 'block');

				_letSend = $.ajax({
					url: ajaxurl,
					method: "POST",
					data: _action,
					beforeSend: function() {
						$('#pp_form .pp_total .loading').stop(true).fadeIn(500);
					},
					success: function( response ) {
						var r = response.split('---');

							$('#pp_total').html(r[0]);
							$('#pp_description').val(r[7]);
							$('#TotalPriceIVA').html(r[5]);
							$('#TotalPrice').html(r[4]);
							$('#pp_price_u').val(r[3]);
							$('#pp_total_b').val(r[1]+' und'+(+r[1] != 1 ? 's' : '')+'.');
							$('#pp_total_c').val(r[2]+' und'+(+r[2] != 1 ? 's' : '')+'.');
							$('#pp_total').val(r[0]+' und'+(+r[0] != 1 ? 's' : '')+'.');
							$('#pp_total50').html(+r[0] > pp_min ? +r[0] : pp_min);
							$('#pp_price').val(r[6]);
							$('#pp_total').html(r[0]);	
							
							total = r[0];
						
						$('#pp_form .pp_total .loading').stop(true).fadeOut(0);
						
						_letSend = false;
					}
				});
			} else {
				$('.pp_total').css('display', 'none');
			}
			
		};
		$('#tamano_delantero, #tamano_trasero, #pp_sizes input').keydown(function(e){
			if(typeof e.keyCode != 'undefined' && e.keyCode == 13) return false;
		});
		$('.pp_embolsado').change(updatePrice);
		$('#tamano_delantero, #tamano_trasero, #pp_sizes input').keyup(updatePrice);
		$('#tamano_delantero, #tamano_trasero, #pp_sizes input').change(updatePrice);
		$('#estampado_delantero, #estampado_trasero').change(function(){
			var _val	= $(this).val(),
			_target		= $(this).attr('id') == 'estampado_delantero' ? 'tamano_delantero' : 'tamano_trasero';
			if(_letSend !== false)	_letSend.abort();
			$('#pp_form .pp_total .loading').stop(true).fadeIn(500);
			_letSend = $.post(ajaxurl, { 'action': 'get_sizes', 'pp_pid': pp_pid, 'name': _val }, function(response){
				if(_val == '')	$('#'+_target).attr('disabled', true).parent().addClass('disabled');
				else 			$('#'+_target).removeAttr('disabled').parent().removeClass('disabled');
				$('#'+_target).html(response);
					//		console.log(response);

				updatePrice();
			});
		});
		$('#pp_add_products').on('click', function(){

			if(_letSend === false && total >= confCantMin){
				$('#pp_form .pp_total .loading').stop(true).fadeIn(500);
				_letSend = $.post(ajaxurl, 'action=add_products&'+$('#pp_form').serialize(), function(response){
					$('#pp_popup h3').html('Carrito de compras');
					$('#pp_popup p').html('El carrito se ha actualizado exitosamente.');
					$('#pp_popup .buttons .btn').hide();
					$('#pp_popup .buttons .compra').show();
					$('#pp_popup').stop(true).fadeIn(500);
					$('#pp_form .pp_total .loading').stop(true).fadeOut(0);
					_letSend = false;
				});
			}else{
				$('#pp_popup h3').html('Productos insuficientes');
				$('#pp_popup p').html('Deben ser mínimo '+confCantMin+' productos.');
				$('#pp_popup .buttons .btn').hide();
				$('#pp_popup .buttons .cerrar').show();
				$('#pp_popup').stop(true).fadeIn(500);
			}
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

	/* FUNCIÓN PARA SUBIR LOGOS DE LAS CAMISETAS */
	if($('#pp_add_logo').length > 0){
		$('#pp_add_logo').on('click', function(){
			$('#pp_logo').click();
			return false;
		});
		$('#pp_logo').bind('change', function(){
			var data	= new FormData(),
			total		= +($('#pp_files tr').length > 0 ? $('#pp_files tr:last').attr('t') : 0);
			$.each($('#pp_logo')[0].files, function(i, file){
				if(typeof file != 'undefined' && file.size <= 5000000){
					var _size = file.size > 1000000 ? (file.size / 1000000).toFixed(2)+'Mb' : Math.ceil(file.size / 1000)+'Kb';
					total++;
					$('#pp_files').append('<tr id="pp_file'+total+'" t="'+total+'" url="" name="" ext=""><td><a><i class="fa fa-spinner fa-spin"></i> '+file.name+' ('+_size+')</a></td></tr>');
					data.append('file', file);
					data.append('fid', total);
				}else{
					total++;
					$('#pp_files').append('<tr id="pp_file'+total+'" t="'+total+'" url="" name="" ext=""><td><a style="color: red;"><i class="fa fa-times"></i> El archivo es demasiado pesado.</a></td></tr>');
					setTimeout(function(){
						$('#pp_file'+total).fadeOut(500, function(){
							$('#pp_file'+total).remove();
						});
					}, 3000);
				}
			});
			data.append('action', 'save_logo');
			$('#pp_archivos').val('');
			$('#pp_logo').val('');
			$.ajax({
				url: ajaxurl,
				data: data,
				type: 'POST',
				cache: false,
				method: 'POST',
				contentType: false,
				processData: false,
				success: function(response){
					var _files	= '',
					_i			= 'picture-o',
					_data		= response.split('---'),
					_fid		= _data[0].replace(/\s/g, '');
					if(_data[1] != 'archivoinvalido' && _data[1] != 'errorsubida' && _data[1] != 'maxsize'){
						var _size = _data[3] > 1000000 ? (_data[3] / 1000000).toFixed(2)+'Mb' : Math.ceil(_data[3] / 1000)+'Kb';
						if(_data[4] == 'psd')												_i	= 'file-o';
						else if(_data[4] == 'pdf')											_i	= 'file-pdf-o';
						else if(_data[4] == 'ai' || _data[4] == 'eps' || _data[4] == 'cdr')	_i	= 'bookmark-o';
						$('#pp_file'+_fid).attr({'url': _data[1], 'ext': _data[4], 'name': _data[2]}).find('a').attr({'href': _data[1], 'download': _data[2]}).html('<i class="fa fa-'+_i+'"></i> '+_data[2]+' ('+_size+')');
						$('#pp_files tr').each(function(){
							_files += (_files == '' ? '' : '+++')+$(this).attr('url')+'---'+$(this).attr('name');
						});
						$('#pp_archivos').val(_files);
					}else if(_data[1] == 'archivoinvalido'){
						$('#pp_file'+_fid+' a').css({'color': 'red'}).html('<i class="fa fa-times"></i> El archivo subido no es válido.');
						setTimeout(function(){
							$('#pp_file'+_fid).fadeOut(500, function(){
								$('#pp_file'+_fid).remove();
							});
						}, 3000);
					}else if(_data[1] == 'maxsize'){
						$('#pp_file'+_fid+' a').css({'color': 'red'}).html('<i class="fa fa-times"></i> El archivo es demasiado pesado.');
						setTimeout(function(){
							$('#pp_file'+_fid).fadeOut(500, function(){
								$('#pp_file'+_fid).remove();
							});
						}, 3000);
					}else $('#pp_file'+_fid).remove();
				}
			});
		});
	}
})(jQuery);
