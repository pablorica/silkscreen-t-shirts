<?php
/*
Plugin Name: Configurador Productos
Plugin URI: http://roifacal.com
Description: Plugin para configurar diferentes combinaciones de productos.
Version: 3.1
Author: Roi Facal
Author URI: http://roifacal.com
*/

/*require 'inc/plugin-update-checker.php';
$MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'http://roifacal.com/plugins/configurador-productos/metadata.json',
	__FILE__,
	'rf-configurador-productos'
);*/


//	PLUGIN INIT
function pp_init()
{
	global $wpdb;
	$path = WP_CONTENT_DIR . '/pp_logos/';
	if (!is_dir($path) && wp_mkdir_p($path)) echo  ' --- Directorio creado en: ' . $path . ' --- ';
}
add_action('init', 'pp_init');


//	RESOURCES

function rf_pp_scripts()
{
	wp_enqueue_style('fontawesome',	'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.1');
	wp_enqueue_style('product_picker-plugin', plugins_url('res/style.css', __FILE__), array(), '1.0.2');

	wp_enqueue_script('rf_pp_script', plugins_url('res/scripts.js', __FILE__), array('jquery'), '1.2', true);
}
add_action('wp_enqueue_scripts', 'rf_pp_scripts');
add_action('admin_print_styles', 'rf_pp_scripts');
function pp_ajaxurl()
{
	global $post;
	$meta = get_post_meta($post->ID, 'ro_pp_meta', true);
	$min = (int) $meta['minimo'] > 0 ? (int) $meta['minimo'] : 50;
	$pid = get_queried_object_id();
	echo '<script type="text/javascript">var pp_pid = ' . $pid . ', pp_min = ' . $min . ', ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
}
add_action('wp_head', 'pp_ajaxurl');


//	ADMIN BLOCK

function pp_options_page()
{ ?>
	<div class="wrap">
		<form action="options.php" method="post">
			<?php settings_fields('pp_options'); ?>
			<?php do_settings_sections('rf-configurador-productos'); ?>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}
function pp_menu()
{
	add_options_page('Configurador Productos', 'Configurador Productos', 'administrator', 'rf-configurador-productos', 'pp_options_page');
}
add_action('admin_menu', 'pp_menu');
function pp_settings()
{
	function pp_main_text()
	{
	}
	function pp_power_str()
	{
		$options = get_option('pp_options');
		echo "<input name=\"pp_options[power]\" type=\"radio\" value=\"on\"" . ($options['power'] == 'on' ? ' checked' : '') . " /> Activado <input name=\"pp_options[power]\" type=\"radio\" value=\"off\"" . ($options['power'] != 'on' ? ' checked' : '') . " /> Desactivado";
	}
	function pp_embolsado_str()
	{
		$options = get_option('pp_options');
		echo "<input name=\"pp_options[embolsado]\" type=\"text\" value=\"{$options['embolsado']}\" style=\"width: 100%;\">";
	}
	function pp_tipos_estampacion_str()
	{
		$options = get_option('pp_options');
		echo '	<div id="tipo_el">
					<div class="pp_tipo_header">
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;"><p style="height: 4em;">Tipo de estampación</p></div>
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;"><p style="height: 4em;">Precio estampación por prenda (prendas blancas)</p></div>
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;"><p style="height: 4em;">Precio estampación por prenda (prendas de color)</p></div>
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;"><p style="height: 4em;">Precio adicional por pantalla y fotolitos</p></div>
					</div>';
		$i = 1;
		while (isset($options['tipo' . $i])) {
			echo '
					<div class="pp_tipo_group" style="margin-bottom: 15px;">
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;">
							<input class="pp_tipo_titulo" type="text" name="pp_options[tipo' . $i . ']" value="' . $options['tipo' . $i] . '" style="width: 100%;">
						</div>
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;">
							<input class="pp_tipo_pb" type="text" name="pp_options[precio_blanco' . $i . ']" value="' . $options['precio_blanco' . $i] . '" style="width: 100%;">
						</div>
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;">
							<input class="pp_tipo_pc" type="text" name="pp_options[precio_color' . $i . ']" value="' . $options['precio_color' . $i] . '" style="width: 100%;">
						</div>
						<div style="box-sizing: border-box;float: left;padding-right: 15px;width: 25%;">
							<input class="pp_tipo_pp" type="text" name="pp_options[precio_pantalla' . $i . ']" value="' . $options['precio_pantalla' . $i] . '" style="width: 100%;">
						</div>
						<a href="#" class="pp_delete_tipo" e="' . $i . '">Eliminar "<span>' . $options['tipo' . $i] . '</span>"</a>
					</div>';
			$i++;
		}
		echo '
				</div>
				<button id="pp_create_tipo" class="button button-default">Añadir Tipo de Estampación</button>
				<div style="clear: both;"></div>';
	}
	function pp_porcentajes_str()
	{
		$options = get_option('pp_options');
		echo "	<div style=\"box-sizing: border-box;float: left;padding-right: 15px;width: 33%;\">
					<p>Más de 1000 productos</p>
					<input type=\"text\" name=\"pp_options[p1000]\" value=\"{$options['p1000']}\" style=\"width: 100%;\">
				</div>
				<div style=\"box-sizing: border-box;float: left;padding-right: 15px;width: 33%;\">
					<p>Más de 750 productos</p>
					<input type=\"text\" name=\"pp_options[p750]\" value=\"{$options['p750']}\" style=\"width: 100%;\">
				</div>
				<div style=\"box-sizing: border-box;float: left;padding-right: 15px;width: 33%;\">
					<p>Más de 500 productos</p>
					<input type=\"text\" name=\"pp_options[p500]\" value=\"{$options['p500']}\" style=\"width: 100%;\">
				</div>
				<div style=\"box-sizing: border-box;float: left;padding-right: 15px;width: 33%;\">
					<p>Más de 250 productos</p>
					<input type=\"text\" name=\"pp_options[p250]\" value=\"{$options['p250']}\" style=\"width: 100%;\">
				</div>
				<div style=\"box-sizing: border-box;float: left;padding-right: 15px;width: 33%;\">
					<p>Más de 100 productos</p>
					<input type=\"text\" name=\"pp_options[p100]\" value=\"{$options['p100']}\" style=\"width: 100%;\">
				</div>
				<div style=\"box-sizing: border-box;float: left;padding-right: 15px;width: 33%;\">
					<p>Desde la cantidad mínima</p>
					<input type=\"text\" name=\"pp_options[p50]\" value=\"{$options['p50']}\" style=\"width: 100%;\">
				</div>
				<div style=\"clear: both;\"></div>";
	}
	function pp_minqty_str()
	{
		$options = get_option('pp_options');
		echo "	<div style=\"box-sizing: border-box;float: left;padding-right: 15px;width: 33%;\">
					<input type=\"number\" name=\"pp_options[min_qty]\" value=\"{$options['min_qty']}\" style=\"width: 100%;\">
				</div>
				<div style=\"clear: both;\"></div>";
	}
	function pp_rango_precios()
	{
		$options = get_option('pp_options');
		echo "	<div style=\"box-sizing: border-box;float: left;padding-right: 15px;width: 33%;\">
					<p>Rango del Precio Unidad</p>
					<input type=\"number\" name=\"pp_options[qty_price_unit]\" value=\"{$options['qty_price_unit']}\" style=\"width: 100%;\">
					<p>El precio unidad se aplica desde 1 hasta el número de unidades indicada en este campo.</p>
				</div>
				<div style=\"box-sizing: border-box;float: left;padding-right: 15px;width: 33%;\">
					<p>Rango del Precio Paquete</p>
					<input type=\"number\" name=\"pp_options[qty_price_pack]\" value=\"{$options['qty_price_pack']}\" style=\"width: 100%;\">
					<p>El Precio Paquete se aplica desde el número indicado en el campo anterior (más 1) hasta el número de unidades indicada en este campo.</p>
				</div>
				<div style=\"box-sizing: border-box;float: left;padding-right: 15px;width: 33%;\">
					<p>El Precio Caja se aplica desde el número indicado en el campo anterior (más 1) en adelante.</p>
				</div>
				<div style=\"clear: both;\"></div>";
	}

	function pp_texto_informativo()
	{
		$options = get_option('pp_options');
		$settings = array(
			'teeny' => true,
			'textarea_name' => 'pp_options[info_text]',
			'media_buttons' => false
		);
		wp_editor($options['info_text'], 'terms_wp_content', $settings);
	}

	register_setting('pp_options', 'pp_options');
	add_settings_section('pp_main', 'Product Picker', 'pp_main_text', 'rf-configurador-productos');
	add_settings_field('pp_power', 'Estado: ', 'pp_power_str', 'rf-configurador-productos', 'pp_main');
	add_settings_field('pp_embolsado', 'Precio de embolsado: ', 'pp_embolsado_str', 'rf-configurador-productos', 'pp_main');
	add_settings_field('pp_tipos_estampacion', 'Tipos de estampación: ', 'pp_tipos_estampacion_str', 'rf-configurador-productos', 'pp_main');
	add_settings_field('pp_porcentajes', 'Margen de beneficio por cantidades: ', 'pp_porcentajes_str', 'rf-configurador-productos', 'pp_main');
	add_settings_field('pp_minqty', 'Cantidad mínima pedido: ', 'pp_minqty_str', 'rf-configurador-productos', 'pp_main');
	add_settings_field('pp_rango_precios', 'Precio base: ', 'pp_rango_precios', 'rf-configurador-productos', 'pp_main');
	add_settings_field('pp_texto_informativo', 'Texto informativo: ', 'pp_texto_informativo', 'rf-configurador-productos', 'pp_main');
}
add_action('admin_init', 'pp_settings');


//	PRODUCT ADMIN TAB

function ro_pp_custom_meta_callback()
{
	global $post;
	$meta = get_post_meta($post->ID, 'ro_pp_meta', true); ?>
	<input type="hidden" name="pp_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>">
	<table>
		<tr>
			<th colspan="3" style="text-transform: uppercase;text-align: center;">Tipos de estampación disponibles para el producto</th>
		</tr>
		<?php
		$options = get_option('pp_options');
		$tipos = array();
		$tipos_full = array();
		$i = 1;
		while (isset($options['tipo' . $i])) {
			$tipos_full[] = $options['tipo' . $i];
			$i++;
		}
		foreach ($tipos_full as $key => $value) {
			$v = explode(' Tamaño ', $value);
			if (!in_array($v[0], $tipos)) $tipos[] = $v[0];
		}
		foreach ($tipos as $key => $value) { ?>
			<tr>
				<th style="text-align: right;padding: 0 15px;"><?php echo $value; ?></th>
				<td><input name="ro_pp_meta[<?php echo sanitize_title($value); ?>]" type="radio" value="on" <?php if ($meta[sanitize_title($value)] != 'off') echo ' checked'; ?> /> Habilitado</td>
				<td><input name="ro_pp_meta[<?php echo sanitize_title($value); ?>]" type="radio" value="off" <?php if ($meta[sanitize_title($value)] == 'off') echo ' checked'; ?> /> Inhabilitado</td>
			</tr>
		<?php
		} ?>
		<tr>
			<th style="text-transform: uppercase;padding: 15px 15px 0;text-align: right;">Cantidad mínima permitida</th>
			<td colspan="2" style="padding: 15px 0 0;"><input name="ro_pp_meta[minimo]" type="number" value="<?php echo (int) $meta['minimo'] > 0 ? (int) $meta['minimo'] : 0; ?>" /></td>
		</tr>
		<tr>
			<th style="text-transform: uppercase;padding: 15px 15px 0;text-align: right;">Se permite embolsado</th>
			<td style="padding: 15px 0 0;"><input name="ro_pp_meta[embolsado]" type="radio" value="on" <?php if ($meta['embolsado'] != 'off') echo ' checked'; ?> /> Habilitado</td>
			<td style="padding: 15px 0 0;"><input name="ro_pp_meta[embolsado]" type="radio" value="off" <?php if ($meta['embolsado'] == 'off') echo ' checked'; ?> /> Inhabilitado</td>
		</tr>
	</table>
<?php
}
function ro_pp_custom_meta()
{
	add_meta_box('ro_pp_meta', 'Datos de selección de productos', 'ro_pp_custom_meta_callback', 'product', 'normal', 'high');
}
add_action('add_meta_boxes', 'ro_pp_custom_meta');
function ro_pp_custom_meta_save($post_id)
{
	if (!wp_verify_nonce($_POST['pp_nonce'], basename(__FILE__))) return $post_id;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	if ('product' === $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) return $post_id;
		elseif (!current_user_can('edit_post', $post_id)) return $post_id;
	}
	$old = get_post_meta($post_id, 'ro_pp_meta', true);
	$new = $_POST['ro_pp_meta'];
	if ($new && $new !== $old) update_post_meta($post_id, 'ro_pp_meta', $new);
	elseif ('' === $new && $old) delete_post_meta($post_id, 'ro_pp_meta', $old);
}
add_action('save_post', 'ro_pp_custom_meta_save');


//	PRODUCT USER INFO

function pp_price_mobile()
{ ?>
	<div class="visible-xs-block text-center">
		<button class="btn btn-danger pp-presupuesto">Haz tu presupuesto y compra</button>
	</div>
<?php
}
function pp_price()
{
	global $wpdb;
	$options = get_option('pp_options');
	$min = false;
	$_product = wc_get_product(get_queried_object_id());
	$variations1 = $_product->get_children();
	foreach ($variations1 as $value) {
		$single_variation = new WC_Product_Variation($value);
		if (isset($single_variation->regular_price) && $single_variation->regular_price != '' && $single_variation->regular_price != 0 && ($min === false || (float) $single_variation->regular_price < $min))
			$min = (float) $single_variation->regular_price;
		if (isset($single_variation->price) && $single_variation->price != '' && $single_variation->price != 0 && ($min === false || (float) $single_variation->price < $min))
			$min = (float) $single_variation->price;
	}
	$mp = $min + ($min / 100 * (int) $options['p1000']); ?>

	<button class="btn btn-danger" type="button"><small>Desde</small> <?php echo wc_price($mp); ?> <small>und.</small></button>
	<button class="btn btn-danger pp-presupuesto">Haz tu presupuesto y compra</button>
<?php
}
function pp_box()
{
	global $post;
	$meta = get_post_meta($post->ID, 'ro_pp_meta', true);
	$options = get_option('pp_options');
	$tipo = array();
	$size = array();
	$tipos = array();
	$pricc = array();
	$pricb = array();
	$pripp = array();
	$pripe = array();
	$i = 1;
	while (isset($options['tipo' . $i])) {
		$t = explode(' Tamaño ', $options['tipo' . $i]);
		if (!in_array($t[0], $tipo)) $tipo[] = $t[0];
		if (!in_array($t[1], $size)) $size[] = $t[1];
		$tipos[] = $options['tipo' . $i];
		$pricc[] = $options['precio_color' . $i];
		$pricb[] = $options['precio_blanco' . $i];
		$pripp[] = $options['precio_pantalla' . $i];
		$i++;
	} ?>
	<div id="pp_popup" style="display: none;">
		<div class="white">
			<h3 class="text-center"></h3>
			<p class="text-center"></p>
			<div class="text-center buttons">
				<button class="btn btn-danger compra">Seguir comprando</button>
				<a href="<?php echo get_permalink(wc_get_page_id('cart')); ?>" class="btn btn-danger compra">Ir al carrito de compras</a>
				<button class="btn btn-danger cerrar">Cerrar</button>
			</div>
		</div>
	</div>
	<?php
	if ($meta['minimo'] >  0) : //Cantidad mínima establecida por producto.
		$minimo = $meta['minimo'];
	else :
		$minimo = get_option('pp_options')['min_qty']; //Cantidad mínima global.
	endif;
	?>
	<form id="pp_form" class="pp_box">
		<input type="hidden" id="pp_tipo_minqty" value="<?= $minimo ?>">
		<h3>Elige el tipo de tu estampación para hacer tu presupuesto</h3>
		<!-- FORMULARIO DE TIPOS DE ESTAMPACIÓN -->
		<table>
			<tr>
				<th><span>1</span> Tipo de estampación</th>
				<td>
					<div>
						<b>Tipo de estampación delantera</b><br>
						<select id="estampado_delantero" name="estampado_delantero">
							<option value="">Sin estampación</option>
							<?php
							foreach ($tipo as $key => $value) {
								if ($meta[sanitize_title($value)] != 'off') {
									echo '
							<option value="' . $value . '">' . $value . '</option>';
								}
							} ?>
						</select>
					</div>
					<div>
						<b>Tipo de estampación trasera</b><br>
						<select id="estampado_trasero" name="estampado_trasero">
							<option value="">Sin estampación</option>
							<?php
							foreach ($tipo as $key => $value) {
								if ($meta[sanitize_title($value)] != 'off') {
									echo '
							<option value="' . $value . '">' . $value . '</option>';
								}
							} ?>
						</select>
					</div>
					<div style="clear: both;float: none"></div>
				</td>
			</tr>
			<tr>
				<th><span>2</span> Área de estampación</th>
				<td>
					<div class="disabled">
						<b>Área de estampación delantera</b><br>
						<select id="tamano_delantero" name="tamano_delantero" disabled>
							<option value="">Primero seleccione tipo de estampación</option>
						</select>
					</div>
					<div class="disabled">
						<b>Área de estampación trasera</b><br>
						<select id="tamano_trasero" name="tamano_trasero" disabled>
							<option value="">Primero seleccione tipo de estampación</option>
						</select>
					</div>
					<div style="clear: both;float: none"></div>
				</td>
			</tr>
			<?php
			if ($meta['embolsado'] != 'off') { ?>
				<tr>
					<th><span>3</span> Embolsado</th>
					<td>
						<span style="display: inline-block;padding-right: 15px;"><input type="radio" name="embolsado" value="Si" class="pp_embolsado" style="width: 15px;height: 15px;vertical-align: sub;"> Si</span>
						<span style="display: inline-block;padding-right: 15px;"><input type="radio" name="embolsado" value="No" class="pp_embolsado" checked style="width: 15px;height: 15px;vertical-align: sub;"> No</span>
						<span style="display: inline-block;padding-right: 15px;">Si elijes la opción sin embolsado, te enviaremos tus prendas dobladas por la mitad y ordenadas por tallas.</span>
					</td>
				</tr>
			<?php
			} ?>
		</table>

		<div>
			<?php echo wpautop($options['info_text']); ?>
		</div>

		<h3>Elige colores y tallas para hacer tu presupuesto</h3>
		<div class="row sidebar_configurator">
			<!-- CAJETÍN DE TOTALES -->
			<div class="col-xs-12 col-lg-4 pull-right pp_total">
				<div class="loading" style="display: none;"><i class="fa fa-refresh fa-spin"></i></div>
				<div>
					<h3>Tu presupuesto</h3>
					<div class="unidades">
						<table id="tabla_precios">
							<tr>
								<th>Color</th>
								<th>Talla</th>
								<th>Uds</th>
								<th>p/u</th>
								<th class="text-right">Subtotal</th>
							</tr>

						</table>						
						<div style="clear: both;"></div>
					</div>
					<div class="total">
						<h4><small>Total:</small> <span id="totalPrice"></span> €</h4>
						<h4><small>IVA:</small> <span id="iva"></span> %</h4>
						<h5><span id="totalPriceIva"></span> <small>(IVA incluido)</small></h5>
					</div>
				</div>
				<button id="pp_add_products" class="btn btn-primary text-uppercase text-center">Añadir a la cesta</button>
				<p>&nbsp;</p>
				<input type="hidden" name="add-to-cart" value="<?php echo get_queried_object_id(); ?>">
				<input type="hidden" name="product_id" value="<?php echo get_queried_object_id(); ?>">
				<input id="pp_description" type="hidden" name="custom_options[description]" value=""><br>
				<input id="pp_price" type="hidden" name="custom_options[custom_price]" value="0"><br>
			</div>

			<!-- CAJETÍN DE TALLAS Y COLORES -->
			<div id="pp_sizes" class="col-xs-12 col-lg-8 sw_precio">
				<div class="pp_cbar">
					<div><span style="color: red">Cantidad mínima de pedido <?= $minimo . ' UDS'; ?></span></div>
				</div>
				<?php
				$colores = array();
				$_product = wc_get_product(get_queried_object_id());
				$variations1 = $_product->get_children();
				$variations2 = array();
				foreach ($variations1 as $value) {
					$single_variation = new WC_Product_Variation($value);
					$variations2[] = $single_variation;
					$v = $single_variation->attributes;
					$nk = false;
					foreach ($colores as $k => $vc) {
						if ($vc['color'] == $v['pa_color']) $nk = $k;
					}
					if ($nk === false) {
						$colores[] = array(
							'image'	=> wp_get_attachment_url($single_variation->image_id),
							'color'	=> $v['pa_color'],
							'sizes'	=> array()
						);
						end($colores);
						$nk = key($colores);
					}
					$colores[$nk]['sizes'][] = $v['pa_tamano'];
				}
				foreach ($colores as $key => $value) { ?>
					<div class="pp_product">
						<!-- Línea por color en el configurador -->
						<span>
							<div>
								<img src="<?php echo $value['image']; ?>" alt=""><br>
								<?php echo strtolower($value['color']); ?>
							</div>
						</span>
						<div>
						<!-- Línea de tallaje -->
							<div id="<?= $key + 1; ?>">
								<?php
								foreach ($value['sizes'] as $k => $v) {
									$vid = 0;
									foreach ($variations2 as $k2 => $v2) {
										if ($v2->attributes['pa_color'] == $value['color'] && $v2->attributes['pa_tamano'] == $v) $vid = $v2->slug;
									} ?>
									<span>
										<?php echo strtoupper($v); ?><br>
										<select class="select-css" name="<?php echo $vid; ?>" autocomplete="off">
											<option value="0">0</option>
											<?php
											for ($i = 1; $i < 500; $i++) { ?>
												<option value="<?= $i; ?>"><?= $i; ?></option>
											<?php }
											?>
										</select>
									</span>
									
								<?php } ?>
								
							</div>
						</div>
					</div>
					<input id="fila-<?=$key+1; ?>" type="hidden" value="0">
				<?php
				} ?>
			</div>
		</div>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
	</form>
<?php
}
function pp_load()
{
	$options = get_option('pp_options');
	if ($options['power'] == 'on') {
		add_action('woocommerce_before_single_product_summary', 'pp_price_mobile', 5);
		add_action('woocommerce_single_variation', 'pp_price', 5);
		add_action('woocommerce_after_single_product_summary', 'pp_box', 10);
	}
}
add_action('plugins_loaded', 'pp_load');

function get_sizes()
{
	global $wpdb;
	$name = (string) $_POST['name'];
	$options = get_option('pp_options');
	$size = array();
	$ret = '';
	$i = 1;
	while (isset($options['tipo' . $i])) {
		$t = explode(' Tamaño ', $options['tipo' . $i]);
		if ($t[0] == $name && !in_array($t[1], $size)) {
			$ret .= '
							<option value="' . $t[1] . '">' . $t[1] . '</option>';
		}
		$i++;
	}
	if ($ret == '')	echo '
							<option value="">Primero seleccione tipo de estampación</option>';
	else			echo '
							<option value="">Seleccione un área de estampación</option>';
	echo $ret;
	wp_die();
}
add_action('wp_ajax_get_sizes', 'get_sizes');
add_action('wp_ajax_nopriv_get_sizes', 'get_sizes');

function get_prices() //Método a ejecutar en el action del Ajax Call.
{
	global $wpdb;
	$options = get_option('pp_options');
	$tipos = array();
	$pricc = array();
	$pricb = array();
	$pripp = array();
	$_product = wc_get_product($_POST['pp_pid']);
	$get = explode('---', (string) $_POST['name']); //nombre y tamaños de los estampados.
	$variations1 = $_product->get_children();
	$embprice = $options['embolsado'];
	$colores = array();
	$description = '';
	$addpriceFrontal = 0;
	$addpriceTrasero = 0;
	$aprice = 0;
	$total = 0;
	$price = 0;
	$i = 1;
	while (isset($options['tipo' . $i])) {
		$tipos[$i] = $options['tipo' . $i];
		$pricc[$i] = $options['precio_color' . $i];
		$pricb[$i] = $options['precio_blanco' . $i];
		$pripp[$i] = $options['precio_pantalla' . $i];
		$i++;
	}
	foreach ($variations1 as $value) {
		$single_variation = new WC_Product_Variation($value);

		if (isset($_POST[$single_variation->slug])) {
			$nk = false;
			$attr = $single_variation->get_variation_attributes();
			foreach ($colores as $k => $vc) {
				if ($vc['color'] == $vc['pa_color']) $nk = $k;
			}
			if ($nk === false) {
				$colores[] = array(
					'color'	=> $attr['attribute_pa_color'],
					'sizes'	=> array()
				);
				end($colores);
				$nk = key($colores);
			}
			$colores[$nk]['sizes'][] = array($attr['attribute_pa_tamano'], (int) $_POST[$single_variation->slug]);
			$attr['attribute_pa_color'];
			$total += (int) $_POST[$single_variation->slug];
			/* Según las cantidades se aplicarán distintos precios */

			if ($total <= $options['qty_price_unit']) : //Se aplica precio unitario

				$price += (int) $_POST[$single_variation->slug] * (float) $single_variation->regular_price;
				$aprice += (int) $_POST[$single_variation->slug] * (float) $single_variation->regular_price;

			elseif ($total > $options['qty_price_unit'] && $total <= $options['qty_price_pack']) : //Precio por pack

				$price += (int) $_POST[$single_variation->slug] * (float) get_post_meta($value, '_pack_price', true);
				$aprice += (int) $_POST[$single_variation->slug] * (float) get_post_meta($value, '_pack_price', true);

			else : //Precio por caja.

				$price += (int) $_POST[$single_variation->slug] * (float) get_post_meta($value, '_box_price', true);
				$aprice += (int) $_POST[$single_variation->slug] * (float) get_post_meta($value, '_box_price', true);

			//error_log($value);

			endif;

			if (array_search(strtolower('blanco'), array_map('strtolower', $attr)) !== false) { //Prendas blancas.
				//$totalb = (int) $_POST[$single_variation->slug]; //Totales prendas blancas.
				foreach ($tipos as $key => $value) { //Tipos de estampación

					if ($get[0] == $value) { //Tenemos estampación frontal.
						$price += (int) $_POST[$single_variation->slug] * (float) $pricb[$key];
						if ($addpriceFrontal == 0) $addpriceFrontal = (float) $pripp[$key]; //Precio adicional por pantalla y fotolito.
					}
					if ($get[1] == $value) { //Tenemos estampación trasera.
						$porTrasera = $pricb[$key]; //Precio estampación por prenda.
						$price += (int) $_POST[$single_variation->slug] * (float) $porTrasera;
						if ($addpriceTrasero == 0) $addpriceTrasero = (float) $pripp[$key]; //Precio adicional por pantalla y fotolito.
					}
				}
			} else { //Prendas de color.
				foreach ($tipos as $key => $value) {

					if ($get[0] == $value) { //Frontal.
						$price += (int) $_POST[$single_variation->slug] * (float) $pricc[$key];
						if ($addpriceFrontal == 0) $addpriceFrontal = (float) $pripp[$key];
					}

					if ($get[1] == $value) { //Trasero.
						$price += (int) $_POST[$single_variation->slug] * (float) $pricc[$key];
						if ($addpriceTrasero == 0) $addpriceTrasero = (float) $pripp[$key];
					}
				}
			}
		}
	}
	$description .= $total . ' productos' . ($_POST['embolsado'] == 'Si' ? ' embolsados' : '') . (in_array($get[0], $tipos) ? ' con estampación en pecho (' . $get[0] . ')' : '') . (in_array($get[1], $tipos) ? ' con estampación en espalda (' . $get[1] . ')' : '') . '. ';

	foreach ($colores as $k => $v) {
		$color = ucfirst($v['color']);
		if($_POST['update'] != 1):
			$description .= ($k != 0 ? ' - ' : '') . ucfirst($v['color']) . ': ';
		endif;
		foreach ($v['sizes'] as $k2 => $v2) {
			$talla = strtoupper($v2[0]);
			if($_POST['update'] != 1):
				$description .= ($k2 != 0 ? ', ' : '') . strtoupper($v2[0]) . ' (' . $v2[1] . ')';
			endif;
		}
	}
	if ($total > 1000)		$percentprice = (int) $options['p1000'];
	else if ($total >= 750)	$percentprice = (int) $options['p750'];
	else if ($total >= 500)	$percentprice = (int) $options['p500'];
	else if ($total >= 250)	$percentprice = (int) $options['p250'];
	else if ($total >= 100)	$percentprice = (int) $options['p100'];
	else					$percentprice = (int) $options['p50'];
	$price += $aprice / 100 * $percentprice;

	if ($_POST['embolsado'] == 'Si')
		$price += $total * $embprice;
	$tax_p = 0;
	$tax_rates = WC_Tax::get_rates($_product->get_tax_class());
	if (!empty($tax_rates)) {
		$tax_rate = reset($tax_rates);
		$tax_p = sprintf(_x('%.2f', '', 'wptheme.foundation'), $tax_rate['rate']);
	}
	//tax_p es el IVA.
	$fotolito = $addpriceFrontal + $addpriceTrasero;

	$data = array(
		'color' => $color,
		'talla' => $talla,
		'subTotal' => strip_tags(html_entity_decode(wc_price($price))),
		'iva' => $tax_p,
		'fotolito' => $fotolito,
		'description' => $description
	);

	echo json_encode($data);

	wp_die();
}
add_action('wp_ajax_get_prices', 'get_prices');
add_action('wp_ajax_nopriv_get_prices', 'get_prices');

/* Función que añade productos al carrito */
function add_products()
{
	global $wpdb, $woocommerce;
	$woocommerce->cart->empty_cart();

	$woocommerce->cart->add_to_cart($_POST['product_id']);

	wp_die();
}
add_action('wp_ajax_add_products', 'add_products');
add_action('wp_ajax_nopriv_add_products', 'add_products');

function pp_add_data($cart_item_data, $product_id)
{
	global $woocommerce;
	$nv = array();
	$nv['_custom_options'] = $_POST['custom_options'];
	if (empty($cart_item_data))	return $nv;
	else						return array_merge($cart_item_data, $nv);
}
add_filter('woocommerce_add_cart_item_data', 'pp_add_data', 1, 10);
function pp_cart_items($item, $values, $key)
{
	if (array_key_exists('_custom_options', $values)) $item['_custom_options'] = $values['_custom_options'];
	return $item;
}
add_filter('woocommerce_get_cart_item_from_session', 'pp_cart_items', 1, 3);
function pp_add_usr($product_name, $values, $cart_item_key)
{
	$return_string = $product_name . "<br />" . $values['_custom_options']['description']; // . "<br />" . print_r($values['_custom_options']);
	return $return_string;
}
add_filter('woocommerce_cart_item_name', 'pp_add_usr', 1, 3);
function pp_add_values($item_id, $values)
{
	global $woocommerce, $wpdb;
	wc_add_order_item_meta($item_id, 'item_details', $values['_custom_options']['description']);
}
add_action('woocommerce_add_order_item_meta', 'pp_add_values', 1, 2);

/* Actualiza el precio customizado */
function update_custom_price($cart_object)
{
	foreach ($cart_object->cart_contents as $cart_item_key => $value) {
		// Version 2.x
		//$value['data']->price = $value['_custom_options']['custom_price'];
		// Version 3.x / 4.x
		$value['data']->set_price($value['_custom_options']['custom_price']);
	}
}
add_action('woocommerce_before_calculate_totals', 'update_custom_price', 1, 1);

function hide_standard_shipping_when_free_is_available($available_methods)
{
	if (isset($available_methods['local_delivery']) and isset($available_methods['flat_rate'])) unset($available_methods['flat_rate']);
	return $available_methods;
}
add_filter('woocommerce_available_shipping_methods', 'hide_standard_shipping_when_free_is_available', 10, 1);
