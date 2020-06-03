<?php
/**
 * @package toallas-personalizadas
 * @subpackage toallas-personalizadas
 * @since Toallas Personalizadas 2.0
 */
/*		SUPPORT		*/
add_theme_support('custom-logo');
add_theme_support( 'title-tag' );

register_nav_menus( array(
	'footer' => __('Menú pie de página', 'toallas-personalizadas'),
	'principal' => __('Menú principal', 'toallas-personalizadas')
));

function toallas_personalizadas_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Blog Sidebar', 'toallas-personalizadas' ),
		'id' => 'blog-sidebar',
	));
}
add_action( 'widgets_init', 'toallas_personalizadas_widgets_init' );

function toallas_personalizadas_add_woocommerce_support(){
	add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'toallas_personalizadas_add_woocommerce_support');


/*		ENQUEUE		*/
function mp_scripts(){
	wp_enqueue_style('bootstrap',			'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), '3.3.7' );
	wp_enqueue_style('font-awesome',		'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0' );
	wp_enqueue_style('prettyphoto',			'https://cdnjs.cloudflare.com/ajax/libs/prettyPhoto/3.1.6/css/prettyPhoto.min.css', array(), '3.1.6' );
	wp_enqueue_style('mp-style',			get_stylesheet_uri());

	wp_enqueue_script('prettyphoto',		'https://cdnjs.cloudflare.com/ajax/libs/prettyPhoto/3.1.6/js/jquery.prettyPhoto.min.js', array('jquery'), '3.1.6', true);
	wp_enqueue_script('theme_scripts',		get_template_directory_uri().'/res/scripts.js', array('jquery'), '3', true);
	wp_enqueue_script('woo_scripts',		get_template_directory_uri().'/res/woo.js', array('jquery'), '2.9', true);
}
add_action('wp_enqueue_scripts', 'mp_scripts');

/*		FIXES		*/
function all_posts($query){
	if(!is_admin() && is_main_query() && is_post_type_archive('post'))
		return false;
	else if(!is_admin() && is_main_query() && !is_front_page() && (!isset($query->queried_object->term_id) || $query->queried_object->term_id != 17))
		$query->set('posts_per_page', -1);
	if(!is_admin() && is_product_category() && isset($query->queried_object->taxonomy) && $query->queried_object->taxonomy == 'product_cat'){
		$tax_query = $query->tax_query->queries;
		$tax_query[0]['include_children'] = 0;
		$query->set('orderby', 'meta_value_num');
		$query->set('tax_query', $tax_query);
		$query->set('meta_key', '_price');
		$query->set('order', 'ASC');
	}
	//echo '<pre>';
	//print_r($query->query_vars);
	//echo '</pre>';
	return $query;
}
add_action('pre_get_posts', 'all_posts');

add_action('wp_head', 'remove_actions');
function remove_actions(){
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
}

function woocommerce_toallas_title(){
	$cats = wp_get_post_terms(get_the_ID(), 'product_cat');
	$cat = isset($_GET['material']) ? get_term_by('slug', $_GET['material'], 'product_cat') : $cats[0];
	echo '<h2 class="text-uppercase">'.$cat->name.'</h2>';
	the_title('<h1 class="text-uppercase">', '</h1>');
	echo '<p>&nbsp;</p>';
	//echo '<h2 class="pt20">Descripción</h2>';
	//the_content();
	the_excerpt();
}
add_action('woocommerce_single_product_summary', 'woocommerce_toallas_title', 5);

function ficha_producto(){
	global $product;
	if(get_field('ficha_de_producto', $product->id) != '')
		echo '<a href="'.get_field('ficha_de_producto', $product->id).'" target="_blank" class="btn btn-danger">Ver Ficha de producto</a>';
}
add_action('woocommerce_single_variation', 'ficha_producto', 4);

add_filter('woocommerce_show_variation_price', function(){ return TRUE;});


/*		SHORTCODES		*/
function shorthtml($atts, $content = ""){
	$atts = shortcode_atts(array(
		'tag'	=>	'div',
		'class'	=>	false
	), $atts);
	$class = ($atts['class']) ? " class=\"{$atts['class']}\"" : "";
	return "<{$atts['tag']}{$class}>".do_shortcode($content)."</{$atts['tag']}>";
}
add_shortcode('html', 'shorthtml');

function shortcontact(){
	return get_field('informacion_de_contacto', 6);
}
add_shortcode('contact-info', 'shortcontact');


/*		WOOCOMMERCE		*/
function woo_composition_tab(){
	echo '<h2>Composición</h2>';
	echo '<p>'.get_field('Composición').'</p>';
}
function woo_observation_tab(){
	echo '<h2>Observación</h2>';
	echo '<p>'.get_field('Observación').'</p>';
}
function woo_new_product_tabs($tabs){
	$tabs['composition'] = array(
		'title' 	=> __('Composición', 'woocommerce' ),
		'priority' 	=> 10,
		'callback' 	=> 'woo_composition_tab'
	);
	$tabs['observation'] = array(
		'title' 	=> __('Observación', 'woocommerce' ),
		'priority' 	=> 15,
		'callback' 	=> 'woo_observation_tab'
	);
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woo_new_product_tabs' );


function agrega_mi_campo_personalizado($checkout){
	//echo '<div id="additional_checkout_field"><h2>'.__('Información adicional').'</h2>';
	woocommerce_form_field( 'nif', array(
		'required'		=> true,
		'type'			=> 'text',
		'label'			=> __('NIF-DNI'),
		'placeholder'	=> __('Introduzca el Nº NIF-DNI'),
		'class'			=> array('my-field-class form-row-wide'),
	), $checkout->get_value('nif'));
	//echo '</div>';
}
add_action('woocommerce_before_checkout_billing_form', 'agrega_mi_campo_personalizado');
function comprobar_campo_nif() {
	if(!$_POST['nif'])
		wc_add_notice(__('NIF-DNI, es un campo requerido. Debe de introducir su NIF DNI para finalizar la compra.'), 'error');
}
add_action('woocommerce_checkout_process', 'comprobar_campo_nif');
function actualizar_info_pedido_con_nuevo_campo($order_id){
	if(!empty($_POST['nif'])){
		update_post_meta($order_id, 'NIF', sanitize_text_field($_POST['nif']));
	}
}
add_action('woocommerce_checkout_update_order_meta', 'actualizar_info_pedido_con_nuevo_campo');
function mostrar_campo_personalizado_en_admin_pedido($order){
	echo '<p><strong>'.__('NIF').':</strong> '.get_post_meta($order->id, 'NIF', true).'</p>';
}
add_action('woocommerce_admin_order_data_after_billing_address', 'mostrar_campo_personalizado_en_admin_pedido', 10, 1);
function muestra_campo_personalizado_email($keys){
	$keys[] = 'NIF';
	return $keys;
}
add_filter('woocommerce_email_order_meta_keys', 'muestra_campo_personalizado_email');
function incluir_nif_en_factura($address){
	global $wpo_wcpdf;
	echo $address.'<p>';
	$wpo_wcpdf->custom_field('NIF', 'NIF: ');
	echo '</p>';
}
add_filter('wpo_wcpdf_billing_address', 'incluir_nif_en_factura');

/* Elimina pestaña Descripción e Información adicional */
/*
add_filter( 'woocommerce_product_tabs', 'jp_remove_tabs', 20, 1 );

function jp_remove_tabs( $tabs ) {
	if ( isset( $tabs['description'] ) ) unset( $tabs['description'] );
	if ( isset( $tabs['additional_information'] ) ) unset( $tabs['additional_information'] );    	    
    return $tabs;
}
*/

//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
//add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60 );



//PRICE UNIT FIELD
// https://wpdirecto.com/como-crear-un-campo-de-precio-nuevo-en-los-productos-de-woocommerce/
// https://stackoverflow.com/questions/49984805/change-regular-price-text-in-woocommerce-admin-product-pages-settings
/*
add_filter('gettext', 'change_backend_product_regular_price', 100, 3 );
function change_backend_product_regular_price( $translated_text, $text, $domain ) {
    global $pagenow, $post_type;

    if ( is_admin() && in_array( $pagenow, ['post.php', 'post-new.php'] )
    && 'product' === $post_type && 'Regular price' === $text  && 'woocommerce' === $domain )
    {
        $translated_text =  __( 'Precio Unidad', $domain );
    }
    return $translated_text;
}
*/


//PRICE BOX FIELDS
// Backend Variation - Add / Display Custom Price Field
add_action( 'woocommerce_variation_options_pricing', 'add_variation_options_pricing_box_price', 10, 3 );
function add_variation_options_pricing_box_price( $loop, $variation_data, $variation ){

/**/
//PRICE UNIT FIELD and //PRICE PACK FIELD
	echo '
<style>
label[for="variable_regular_price_'.$loop.'"] {
	visibility: hidden;
    position: relative;
}
label[for="variable_regular_price_'.$loop.'"]:after{
    visibility: visible;
    position: absolute;
    top: 0;
    left: 0;
    min-width: 140px;
    content:"Precio Unidad (€)";
}

label[for="variable_sale_price'.$loop.'"] {
	visibility: hidden;
    position: relative;
}
label[for="variable_sale_price'.$loop.'"]:after{
    visibility: visible;
    position: absolute;
    top: 0;
    left: 0;
    min-width: 140px;
    content:"Precio Paquete (€)";
}
</style>
	';
/**/

	//PRICE BOX FIELD
    woocommerce_wp_text_input( array(
        'id' => 'variable_box_price_'.$loop,
        'name' => 'variable_box_price['.$loop.']',
        'wrapper_class' => 'form-row form-row-first',
        'class' => 'short wc_input_price',
        'label' => __( 'Precio Caja', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
        'value' => wc_format_localized_price( get_post_meta( $variation->ID, '_box_price', true ) ),
        'data_type' => 'price',
    ) );
}

// Backend Variation - Save Custom Price Field value
add_action( 'woocommerce_save_product_variation','save_variation_options_pricing_box_price',10 ,2 );
function save_variation_options_pricing_box_price( $variation_id, $loop ){
    if( isset($_POST['variable_box_price'][$loop]) ) {
        update_post_meta( $variation_id, '_box_price', wc_clean( wp_unslash( str_replace( ',', '.', $_POST['variable_box_price'][$loop] ) ) ) );
    }
}

// Frontend Variation - Custom Price display
//add_filter( 'woocommerce_available_variation', 'display_variation_box_price', 10, 3 );
function display_variation_box_price( $data, $product, $variation ) {

    if( $bprice = $variation->get_meta('_box_price') ) {
        $data['price_html'] = '<div class="woocommerce_box_price">' . __( 'Precio Caja: ', 'woocommerce' ) .
        '<span class="box_price">' . wc_price( $bprice ) . '</span></div>' . $data['price_html'];
    }

    //echo wc_format_localized_price( get_post_meta( $variation->ID, '_box_price', true ) );

    return $data;
}

/* Getting prices

Price Unit:
echo wc_format_localized_price( $variation_object->get_regular_price() );
Price Unit:
echo wc_format_localized_price( $variation_object->get_sale_price() );
Price Box:
echo wc_format_localized_price( get_post_meta( $variation->ID, '_box_price', true ) );
*/



