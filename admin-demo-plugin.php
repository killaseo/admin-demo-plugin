<?php

/*
Plugin Name: Admin Demo Plugin
Plugin URI: http://themeisle.com
Description: Wordpress plugin for creating demo accounts for admin dashboard
Version: 1.1
Author: Marius Cristea
Author URI: https://github.com/mariusalex20/
License: A "Slug" license name e.g. GPLv3
*/

define("ROLE_NAME","ti_demo_user");

//creating the new role for the demo user

add_role(
	ROLE_NAME,
	__( 'Demo user' ),
	array(
		"edit_theme_options"=>true,
		"edit_others_posts"=>true,
		"edit_pages"=>true,
		"edit_others_pages"=>true,
		"edit_published_pages"=>true,
		"edit_published_posts"=>true,
		"edit_posts"=>true,
		"read"=>true,
	)
);

//adding the custom dashboard

add_action('admin_menu', "ti_register_menu_custom_dashboard" );

//page restrictions

add_action('load-index.php',  'ti_redirect_dashboard'  );
add_action('load-toplevel_page_acf-options',  'ti_redirect_dashboard'  );
add_action('load-profile.php',  'ti_redirect_dashboard'  );
add_action('load-tools.php',  'ti_redirect_dashboard'  );
add_action('load-edit-comments.php',  'ti_redirect_dashboard'  );
add_action("admin_init","ti_redirect_restrict");
function ti_register_menu_custom_dashboard(){
	if(ti_get_role() == ROLE_NAME ) {
		 add_dashboard_page( 'Dashboard', 'Dashboard', 'read', 'custom-dashboard', 'ti_render_custom_dashboard' );
		add_submenu_page(null, 'Restricted area', 'Restricted area', 'read', 'restricted-area', 'ti_render_restricted_area' );
		remove_menu_page( 'profile.php' );
		remove_menu_page( 'tools.php' );
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'admin.php?page=acf-options' );
		remove_submenu_page( "admin.php?page=acf-options", "admin.php?page=acf-options" );
	}
}
function ti_redirect_restrict(){

	if(ti_get_role() == ROLE_NAME ) {
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

			wp_redirect( admin_url( 'index.php?page=restricted-area' ) );
			die();
		}
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			die();
		}
	}

}
// redirect the demo users to the custom dashboard
function ti_redirect_dashboard() {

	if(ti_get_role() == ROLE_NAME ) {
		$screen = get_current_screen();

		if( $screen->base == 'dashboard' ||  $screen->base == "toplevel_page_acf-options" || $screen->base == "profile" || $screen->base == "tools"|| $screen->base == "edit-comments") {
			if($screen->base=="dashboard")
				wp_redirect( admin_url( 'index.php?page=custom-dashboard' ) );
			else

				wp_redirect( admin_url( 'index.php?page=restricted-area' ) );
		}
	}

}

//return the current user role
function ti_get_role(  ) {
	$user = get_userdata(get_current_user_id());
	$roles =  empty( $user ) ? array() : $user->roles;
	return empty($roles) ? "" : $roles[0];
}



function ti_render_restricted_area(){
	$theme = wp_get_theme(  );
	?>
	<div id="wrap">
		<header id="header">
			<div class="wrapper cf">
				<div class="logo-box">
				 	<?php echo $theme->get("Name"); ?>
				</div><!--/div .logo-box-->
				<div class="header-right cf">
					<div class="header-right-text">
						<p class="header-title">
							<span>	<?php echo $theme->get("Name"); ?></span> Theme Admin Demo
						</p><!--/p .header-title-->
						<p class="header-description">
							This is a just a demo section, changes are not allowded.
						</p><!--/p .header-description-->
					</div><!--/div .header-right-text-->
				</div><!--/div .header-right .cf-->
			</div><!--/div .wrapper .cf-->
		</header><!--/header #header-->

		<div class="wrapper">
			<section id="small-content">
				<div class="small-entry">
					<h3 class="small-entry-title">
						Ops, you can't edit this !
					</h3><!--/h3 .small-entry-title-->
					<p class="small-entry-content">
						<?php echo $theme->get("Description"); ?>
					 				</p><!--/p .small-entry-content-->
				</div><!--/div .small-entry-->
			</section><!--/section #small-content-->
			<div class="small-buttons">
				<a href="<?php echo $theme->get("ThemeURI"); ?>" title="Buy Now MusicBand Theme" class="small-buy-now">
					<span>Download Now</span> 	<?php echo $theme->get("Name"); ?>
					<p>And see all the features live on your live.</p>
				</a><!--/a .small-buy-now-->
			</div><!--/div .small-center-->
		</div><!--/div .wrapper-->
	</div><!--/div #wrap-->
	<?php

}



//custom dashboard page creation

function ti_render_custom_dashboard(){
	$theme = wp_get_theme(  );
	?>
	<div id="wrap">
		<header id="header">
			<div class="wrapper cf">
				<div class="logo-box"> <?php echo $theme->get("Name"); ?>
				</div><!--/div .logo-box-->
				<div class="header-right cf">
					<div class="header-right-text">
						<p class="header-title">
							<span><?php echo $theme->get("Name");  ?></span> Theme Admin Demo
						</p><!--/p .header-title-->
						<p class="header-description">
							This is a just a demo section, changes are not allowded.
						</p><!--/p .header-description-->
					</div><!--/div .header-right-text-->
				</div><!--/div .header-right .cf-->
			</div><!--/div .wrapper .cf-->
		</header><!--/header #header-->
		 <!--/div .wrapper-->
		<div class="wrapper">
			<section id="content">
				<div class="content-left">
					<p class="entry"><?php echo $theme->get("Description"); ?></p>
				</div><!--/div .content-left-->
				<img src="<?php echo get_template_directory_uri(); ?>/screenshot.png" alt="Content Image" title="Content Image" />
				<div class="buttons">
					<?php

						?>
						<a href="<?php echo  $theme->get("ThemeURI"); ?>" title="Buy Now  <?php echo $theme->get("Name"); ?>!" class="buy-now">
							<span>Download Now</span>  <?php echo $theme->get("Name"); ?>!
						</a><!--/a .buy-now-->
						<a href="http://themeisle.com/contact/" title="Support" class="support">
							Support
						</a>



					<!--/a .support-->
				</div><!--/div .buttons-->
			</section><!--/section #content-->
		</div><!--/div .wrapper-->
	</div><!--/div #wrap-->
<?php

}

function ti_custom_enqueue() {
	if( ti_get_role() != ROLE_NAME )
		return;
	wp_enqueue_style( 'ti_demo_user_custom_style', plugin_dir_url( __FILE__ ) . '/style.css' );
	wp_enqueue_style("ti_open_sans","http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic");
}
add_action( 'admin_enqueue_scripts', 'ti_custom_enqueue' );


//registration form

function ti_change_login_image() {
	echo "
<style>
body.login #login h1 a {
background: url('http://cdn.themeisle.com/wp-content/themes/themeIsle/images/logo.png') 8px 0 no-repeat transparent;
height:37px;
width:220px; }
</style>
";
}
add_action("login_head", "ti_change_login_image");

add_action("init","ti_custom_register_page");

function ti_custom_register_page(){
	if($GLOBALS["pagenow"] == "wp-login.php" && @$_GET["action"] == "register"){

		$plugin_url  = plugin_dir_url( __FILE__ );
		$theme = wp_get_theme(  );
        ?>

		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="UTF-8">
			<title>ThemeIsle Dashboard - Login</title>
			<link rel="stylesheet" type="text/css" href="<?php echo $plugin_url ; ?>style.css" />
			<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic' rel='stylesheet' type='text/css'>
			<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">


			</script>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#ask").click(function(){
						$.ajax({
							url:"<?php echo admin_url( 'admin-ajax.php' ) ; ?>",
							type:"POST",
							data:{action:"ti_register_demo_user",email:$("#email").val()},
							success:function(r){
								   $("#full-content").html(r);
							}

						});

						return false;
					})

				})

			</script>
		</head>
		<body id="register-page">
		<header id="full-header">
			<div class="full-wrap">
				<div class="logo-box">

					 <?php echo $theme->get("Name"); ?>
				</div><!--/div .logo-box-->
				<div class="header-right cf">
					<div class="header-right-text">
						<p class="header-title">
							<span>  <?php echo $theme->get("Name"); ?> Theme Admin Demo
						</p><!--/p .header-title-->
						<p class="header-description">
							This is a just a demo section, changes are not allowded.
						</p><!--/p .header-description-->
					</div><!--/div .header-right-text-->
				</div><!--/div .header-right .cf-->
			</div><!--/div .full-wrap-->
		</header><!--/header #full-header-->
		<div class="full-wrap">
			<section id="full-content">
				<h3 class="full-content-title">
					We need your email adress to send you the login details !
				</h3><!--/h3 .full-content-title-->
				<form action="#" class="form cf">
					<input type="email" id="email" placeholder="Your e-mail address" class="input-email" required>
					<a id="ask" class="input-submit"  >View Demo !</a>
				</form><!--/form .form .cf-->
			</section><!--/section #full-content-->
		</div><!--/div .full-wrap-->
		</body>
		</html>
		<?php
		die();
	}
}

add_action( 'wp_ajax_nopriv_ti_register_demo_user', 'ti_register_demo_user_callback' );
function ti_register_demo_user_callback() {
	$email = trim($_POST["email"]);
	$user = get_user_by(  "email", $email);
	$password  = wp_generate_password( $length=12, $include_standard_special_chars=false );
	if($user === false){


	    wp_insert_user(array(
				"user_pass"=>$password,
				"user_login"=>$email,
				"user_email"=>$email,
				"role"=>ROLE_NAME
			)
		);


	}else{

		wp_update_user( array(
			"ID"=>$user->ID,
			"user_pass"=>$password

		) );
	}

	?>
		<h3 class="full-content-title">
			Your WordPress Dashboard login details:
		</h3><!--/h3 .full-content-title-->
		<div class="login-details cf">
			<div class="details">
				<p><span>Username:</span> <?php echo $email; ?></p>
				<p><span>Password:</span> <?php echo $password; ?></p>
			</div><!--/div .details-->
			<a href="<?php echo wp_login_url(); ?>" title="Log In Now!" class="log-in-now">
				Log In Now!
			</a><!--/a .log-in-now-->
		</div>
	<?php
	die();
}


function ti_plugin_activate() {
    update_option("users_can_register",1);
}
register_activation_hook( __FILE__, 'ti_plugin_activate' );