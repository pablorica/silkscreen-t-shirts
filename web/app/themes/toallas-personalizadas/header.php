<?php
/**
 * @package ToallasPersonalizaas
 * @subpackage ToallasPersonalizaas
 * @since Toallas Personalizadas 2.0
 */ ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport"			content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
<?php
if(is_singular() && pings_open(get_queried_object())): ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php
endif;
wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<header>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-5 col-md-4">
					<div class="logo"><?php the_custom_logo(); ?></div>
				</div>
				<div class="col-xs-12 col-sm-5 col-md-4">
					<div class="info-cabe">
						<p>
						Estampación de camisetas personalizadas. Camisetas serigrafiadas para hombres y mujeres. Serigrafiamos con el logotipo de tu empresa  o imagen corporativa todo tipo de prendas y ropa laboral. Camisetas personalizadas al por mayor, de calidad, baratas y siempre al mejor precio.</p>
					</div>
				</div>
					<div class="col-xs-12 col-sm-5 col-md-4">
					<div class="contact-info"><?php echo do_shortcode('[contact-info]'); ?></div></div>
			</div>
		</div>
		<a href="#" class="mobile-menu"><span></span> <b>Menú</b></a>
		<nav>
			<div class="container">
				<div class="row">
<?php
if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
	$count = WC()->cart->cart_contents_count; ?>
					<a class="cart-contents pull-right" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>" style="font-size: 20px;display: inline-block;padding: 5px;">
						<i class="fa fa-shopping-cart"></i>
<?php
	if($count > 0){	?>
						<span class="cart-contents-count" style="color: white;display: inline-block;vertical-align: middle;font-size: 0.6em;">(<?php echo esc_html( $count ); ?>)</span>
<?php
	} ?>
					</a>
 <?php
}
wp_nav_menu(array(
	'container'			=> '',
	'theme_location'	=> 'principal',
	'menu_class'		=> 'menu-principal',
)); ?>
				</div>
			</div>
		</nav>
		<!-- Global site tag (gtag.js) - Google Analytics -->

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-34857826-1"></script>

<script>

  window.dataLayer = window.dataLayer || [];

  function gtag(){dataLayer.push(arguments);}

  gtag('js', new Date());

 

  gtag('config', 'UA-34857826-1');

</script>
		<!--Start of LiveBeep Script-->
<script type="text/javascript">
(function(d,s,id){
if(d.getElementById(id)){return;}
var u='//www.livebeep.com/'+d.domain+'/eye.js';
if((h=d.location.href.split(/#ev!/)[1])) u += '?_e=' +h;
else if((r=/.*\_evV=(\w+)\b.*/).test(c=d.cookie) ) u += '?_v='+c.replace(r,'$1');
var js = d.createElement(s);
js.src = u;js.id = id;
var fjs = d.getElementsByTagName(s)[0];
fjs.parentNode.insertBefore(js, fjs);
})(document,'script','livebeep-script');
</script>
<!--End of LiveBeep Script-->
	</header>
