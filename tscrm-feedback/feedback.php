<?php
/**
Temporary page for client feedback
 */
//Decrypt Function
function doDecrypt($decrypt)
{
	global $crypt_key;
	
	$decoded = base64_decode($decrypt);
	return $decoded;
	//return str_replace("\\0", '', $decrypted);
} 

//Connect with TSCRM DB
$servername = "localhost";
$username = "tenderso_tscrusr";
$password = "3+94s-2o32fD";
$success_msg = '';
$error = '';
//echo "client = ".$_GET['client']." type=".$_GET['type']." contactid =".$_GET['contactid'];
 
 $conn = mysql_connect($servername,$username,$password);
 if(! $conn ) {
	die('Could not connect: ' . mysql_error());
 }
 //echo 'Connected successfully';
 mysql_select_db( 'tenderso_tscrm' );
 
 if ( ! strlen($_SERVER['QUERY_STRING']) )
{ 
  header("Location: http://tendersoftware.com");	
  exit ();
}

	$qry_str_arr = null;
	//echo $_SERVER['QUERY_STRING'];
	
	$qry_str =  ( doDecrypt($_SERVER['QUERY_STRING']) ); //die;
	parse_str ( $qry_str, $qry_str_arr); //print_r($qry_str_arr);
	if(!empty($qry_str_arr)){
		$client_id = isset($qry_str_arr['client'])?$qry_str_arr['client']:0;
		$type = isset($qry_str_arr['type'])?$qry_str_arr['type']:0;
		$contactid = isset($qry_str_arr['contactid'])?$qry_str_arr['contactid']:0;
	}	
	
	//echo $client_id."-".$type."-".$contactid; die;
	if( ( !is_numeric($client_id) || $client_id <= 0 ) || (!is_numeric($type) || $type <= 0 ) ){
		header("Location: http://tendersoftware.com");	
		exit ();
	}
	
 if( (isset($_POST) && !empty($_POST) )  ){ 
	if( empty ( $_POST['feedback_text'] ) && ($type != 1) ){
		//$success_msg = '';
		$error = "Please select any one option"; // echo $error; die;
	}else{
		unset($error);
		$feedback_option = '';
		$feedback_text = $_POST['feedback_text'];
		foreach($feedback_text as $key => $value){
			$feedback_option .= $value."|";
		}	
		$feedback_option = rtrim($feedback_option, "|");
		$now = date('Y-m-d h:i:s');
		//$_qry == 1;
		/*if($type == 1 ){
			$_POST['client_feedback'];
		}*/	
		
		if($_POST['client_feedback'] != ''){
			$client_feedback = strip_tags($_POST['client_feedback']);	
		}	
		
		$sql = "UPDATE tblfeedback_mail_log
				SET feedback=2, feedback_type=".$type.",feedback_text='".$feedback_option ."',client_feedback='".$client_feedback."', feedback_date = '".$now."'
				WHERE client_id=".$client_id." AND contact_id = ".$contactid; 
				
		//echo $sql; die;	
		$retval = mysql_query( $sql, $conn );
		if(! $retval ) {
		  //die('Could not update data: ' . mysql_error());
		  $error =  mysql_error();
		}
		$success_msg = "Your feedback sent successfully\n";
		mysql_close($conn);
		$_POST = array();	
		header("Location: http://tendersoftware.com/feedback.php?success=".$success_msg);
		exit;
	}	
 }	 
 

?> 
<!DOCTYPE html>
<!--[if lt IE 7]>  <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en-US" prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 7]>     <html class="no-js lt-ie9 lt-ie8" lang="en-US" prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 8]>     <html class="no-js lt-ie9" lang="en-US" prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en-US" prefix="og: http://ogp.me/ns#"> <!--<![endif]--><head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Tendersoftware Customer Feedback</title>
		<meta name="viewport" content="width=device-width">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="http://tendersoftware.com/xmlrpc.php">
		<!--wordpress head-->
		
<!-- This site is optimized with the Yoast SEO plugin v4.3 - https://yoast.com/wordpress/plugins/seo/ -->
<meta name="description" content="Case studies, Tender developers, Project scope, Product features, Website designs, Web application services, Xero integration, Mailchimp functions, Mobile applications"/>
<meta name="robots" content="noodp"/>
<meta name="keywords" content="Case studies, Tender developers, Project scope, Mobile applications , website designs, Web application services, Xero integration, Mailchimp functions, Wordpress plugin"/>
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="article" />
<meta property="og:title" content="Case studies| Tender developers | Project scope | Product Features" />
<meta property="og:description" content="Case studies, Tender developers, Project scope, Product features, Website designs, Web application services, Xero integration, Mailchimp functions, Mobile applications" />
<meta property="og:site_name" content="Tender software - Applications Development &amp; Maintenance" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:description" content="Case studies, Tender developers, Project scope, Product features, Website designs, Web application services, Xero integration, Mailchimp functions, Mobile applications" />
<meta name="twitter:title" content="Case studies| Tender developers | Project scope | Product Features" />
<!-- / Yoast SEO plugin. -->

<link rel='dns-prefetch' href='//tendersoftware.com' />
<link rel='dns-prefetch' href='//s.w.org' />
<link rel="alternate" type="application/rss+xml" title="Tender software - Applications Development &amp; Maintenance &raquo; Feed" href="http://tendersoftware.com/feed/" />
<link rel="alternate" type="application/rss+xml" title="Tender software - Applications Development &amp; Maintenance &raquo; Comments Feed" href="http://tendersoftware.com/comments/feed/" />
		<script type="text/javascript">
			window._wpemojiSettings = {"baseUrl":"https:\/\/s.w.org\/images\/core\/emoji\/2.2.1\/72x72\/","ext":".png","svgUrl":"https:\/\/s.w.org\/images\/core\/emoji\/2.2.1\/svg\/","svgExt":".svg","source":{"concatemoji":"http:\/\/tendersoftware.com\/wp-includes\/js\/wp-emoji-release.min.js?ver=4.7.5"}};
			!function(a,b,c){function d(a){var b,c,d,e,f=String.fromCharCode;if(!k||!k.fillText)return!1;switch(k.clearRect(0,0,j.width,j.height),k.textBaseline="top",k.font="600 32px Arial",a){case"flag":return k.fillText(f(55356,56826,55356,56819),0,0),!(j.toDataURL().length<3e3)&&(k.clearRect(0,0,j.width,j.height),k.fillText(f(55356,57331,65039,8205,55356,57096),0,0),b=j.toDataURL(),k.clearRect(0,0,j.width,j.height),k.fillText(f(55356,57331,55356,57096),0,0),c=j.toDataURL(),b!==c);case"emoji4":return k.fillText(f(55357,56425,55356,57341,8205,55357,56507),0,0),d=j.toDataURL(),k.clearRect(0,0,j.width,j.height),k.fillText(f(55357,56425,55356,57341,55357,56507),0,0),e=j.toDataURL(),d!==e}return!1}function e(a){var c=b.createElement("script");c.src=a,c.defer=c.type="text/javascript",b.getElementsByTagName("head")[0].appendChild(c)}var f,g,h,i,j=b.createElement("canvas"),k=j.getContext&&j.getContext("2d");for(i=Array("flag","emoji4"),c.supports={everything:!0,everythingExceptFlag:!0},h=0;h<i.length;h++)c.supports[i[h]]=d(i[h]),c.supports.everything=c.supports.everything&&c.supports[i[h]],"flag"!==i[h]&&(c.supports.everythingExceptFlag=c.supports.everythingExceptFlag&&c.supports[i[h]]);c.supports.everythingExceptFlag=c.supports.everythingExceptFlag&&!c.supports.flag,c.DOMReady=!1,c.readyCallback=function(){c.DOMReady=!0},c.supports.everything||(g=function(){c.readyCallback()},b.addEventListener?(b.addEventListener("DOMContentLoaded",g,!1),a.addEventListener("load",g,!1)):(a.attachEvent("onload",g),b.attachEvent("onreadystatechange",function(){"complete"===b.readyState&&c.readyCallback()})),f=c.source||{},f.concatemoji?e(f.concatemoji):f.wpemoji&&f.twemoji&&(e(f.twemoji),e(f.wpemoji)))}(window,document,window._wpemojiSettings);
		</script>
		<style type="text/css">
img.wp-smiley,
img.emoji {
	display: inline !important;
	border: none !important;
	box-shadow: none !important;
	height: 1em !important;
	width: 1em !important;
	margin: 0 .07em !important;
	vertical-align: -0.1em !important;
	background: none !important;
	padding: 0 !important;
}
.feedback_error{
	color: red;
	padding: 8px;
    border: 1px solid red;
	display: block;
}	
.feedback_success{
	color: green;
    padding: 8px;
    border: 1px solid green;
	display: block;
}	
.hide_class{
	display: none;
}	
.div_feedback_btn{
	margin-top: 10px;
}	
</style>
<link rel='stylesheet' id='contact-form-7-css'  href='http://tendersoftware.com/wp-content/plugins/contact-form-7/includes/css/styles.css?ver=4.6.1' type='text/css' media='all' />
<link rel='stylesheet' id='rs-plugin-settings-css'  href='http://tendersoftware.com/wp-content/plugins/revslider/public/assets/css/settings.css?ver=5.2.5' type='text/css' media='all' />
<style id='rs-plugin-settings-inline-css' type='text/css'>
#rs-demo-id {}
</style>
<link rel='stylesheet' id='wonderplugin-slider-css-css'  href='http://tendersoftware.com/wp-content/plugins/wonderplugin-slider/engine/wonderpluginsliderengine.css?ver=4.7.5' type='text/css' media='all' />
<link rel='stylesheet' id='math-captcha-frontend-css'  href='http://tendersoftware.com/wp-content/plugins/wp-math-captcha/css/frontend.css?ver=4.7.5' type='text/css' media='all' />
<link rel='stylesheet' id='wpsisac_slick_style-css'  href='http://tendersoftware.com/wp-content/plugins/wp-slick-slider-and-image-carousel/assets/css/slick.css?ver=1.2.4' type='text/css' media='all' />
<link rel='stylesheet' id='wpsisac_recent_post_style-css'  href='http://tendersoftware.com/wp-content/plugins/wp-slick-slider-and-image-carousel/assets/css/slick-slider-style.css?ver=1.2.4' type='text/css' media='all' />
<link rel='stylesheet' id='fonts-css'  href='http://tendersoftware.com/wp-content/themes/tendersoftware/css/fonts.css?ver=4.7.5' type='text/css' media='all' />
<link rel='stylesheet' id='theme-style-css'  href='http://tendersoftware.com/wp-content/themes/tendersoftware/css/tstheme_style.css?ver=4.7.5' type='text/css' media='all' />
<link rel='stylesheet' id='animate-css'  href='http://tendersoftware.com/wp-content/themes/tendersoftware/css/animate.css?ver=4.7.5' type='text/css' media='all' />
<link rel='stylesheet' id='bootstrap-basic-style-css'  href='http://tendersoftware.com/wp-content/themes/tendersoftware/style.css?ver=4.7.5' type='text/css' media='all' />
<link rel='stylesheet' id='slick-css'  href='http://tendersoftware.com/wp-content/themes/tendersoftware/css/slick.css?ver=4.7.5' type='text/css' media='all' />
<link rel='stylesheet' id='new-skin-css'  href='http://tendersoftware.com/wp-content/themes/tendersoftware/css/newskin.css?ver=4.7.5' type='text/css' media='all' />
<link rel='stylesheet' id='new-skin1-css'  href='http://tendersoftware.com/wp-content/themes/tendersoftware/css/newskin1.css?ver=4.7.5' type='text/css' media='all' />
<script type='text/javascript' src='http://tendersoftware.com/wp-includes/js/jquery/jquery.js?ver=1.12.4'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-includes/js/jquery/jquery-migrate.min.js?ver=1.4.1'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/plugins/wonderplugin-slider/engine/wonderpluginsliderskins.js?ver=9.0'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/plugins/wonderplugin-slider/engine/wonderpluginslider.js?ver=9.0'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/themes/tendersoftware/js/vendor/modernizr.min.js?ver=4.7.5'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/themes/tendersoftware/js/vendor/respond.min.js?ver=4.7.5'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/themes/tendersoftware/js/vendor/html5shiv.js?ver=4.7.5'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/themes/tendersoftware/js/slick.min.js?ver=4.7.5'></script>
<link rel='https://api.w.org/' href='http://tendersoftware.com/wp-json/' />
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://tendersoftware.com/xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="http://tendersoftware.com/wp-includes/wlwmanifest.xml" /> 
<link rel='shortlink' href='http://tendersoftware.com/?p=344' />
<link rel="alternate" type="application/json+oembed" href="http://tendersoftware.com/wp-json/oembed/1.0/embed?url=http%3A%2F%2Ftendersoftware.com%2Fcompany%2Fcase-studies%2F" />
<link rel="alternate" type="text/xml+oembed" href="http://tendersoftware.com/wp-json/oembed/1.0/embed?url=http%3A%2F%2Ftendersoftware.com%2Fcompany%2Fcase-studies%2F&#038;format=xml" />
<link rel="icon" href="http://tendersoftware.com/wp-content/uploads/2016/01/favicon.png"/>		


</head>
	<body class="page-template page-template-template-contact page-template-template-contact-php page page-id-344 page-child parent-pageid-14 responsive-menu-slide-left">
				<div class="header-section ts_headersection">
		<header>
			<div class="container">
				<div class="row">
					<div class="col-md-3 logo">
					   <h2 class="site-title-heading">
							<a href="http://tendersoftware.com/" title="Tender software &#8211; Applications Development &amp; Maintenance" rel="home">
							  <img src="http://tendersoftware.com/wp-content/uploads/2016/01/admin_logo.png" title="Tender software &#8211; Applications Development &amp; Maintenance" alt="Tender software &#8211; Applications Development &amp; Maintenance" />
							</a>
					   </h2>
				   </div>
				   <div class="col-md-9 headermenu">
						<div class="main-navigation">
								<nav class="navbar navbar-default" role="navigation">
									<div class="navbar-header">
										<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-primary-collapse">
											<span class="sr-only">Toggle navigation</span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
										</button>
									</div>
									<div class="collapse navbar-collapse navbar-primary-collapse">
										<ul id="menu-menu-1" class="nav navbar-nav"><li id="menu-item-18" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home menu-item-18"><a href="http://tendersoftware.com/">Home</a></li>
<li id="menu-item-609" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-609"><a href="http://tendersoftware.com/skill-set/">Skill Set</a></li>
<li id="menu-item-338" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-338"><a href="http://tendersoftware.com/benefits-outsourcing/">Outsourcing Benefits</a></li>
<li id="menu-item-204" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor menu-item-has-children menu-item-204 dropdown" data-dropdown="dropdown"><a href="http://tendersoftware.com/company-overview/" class="dropdown-toggle" data-toggle="dropdown">Company <span class="caret"></span> </a>
<ul class="sub-menu dropdown-menu">
	<li id="menu-item-178" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-178"><a href="http://tendersoftware.com/company-overview/">Company Overview</a></li>
	<li id="menu-item-469" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-469"><a href="http://tendersoftware.com/testimonial/">Testimonials</a></li>
	<li id="menu-item-179" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-179"><a href="http://tendersoftware.com/company/development-offices/">Development Offices</a></li>
	<li id="menu-item-352" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-344 current_page_item menu-item-352 active active"><a href="http://tendersoftware.com/company/case-studies/">Case Studies</a></li>
</ul>
</li>
<li id="menu-item-449" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-449"><a href="http://tendersoftware.com/contact/">Contact</a></li>
</ul> 
                                        
                                      <!--     <div class="our_sites">
                                         <ul class="regions">
                                            <li class=""><img src="http://tendersoftware.com/wp-content/themes/tendersoftware/img/us_flag.png" alt="tendersoftware.com" /></li>
                                         <li class=""><img src="http://tendersoftware.com/wp-content/themes/tendersoftware/img/aus_flag.png" alt="tendersoftware.net" /></li>
                                         </ul>
                  						</div>-->
                   
													<div class="textwidget"><div class="headercontact"> <ul class="regions">
                                            <li class=""><a href="http://tendersoftware.com"> <img src="http://tendersoftware.com/wp-content/themes/tendersoftware/img/us_flag.png" alt="tendersoftware.com" /></a></li>
                                             <li style="position:relative;top:-3px;"><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;&nbsp;1-888-975-7080</li>
											  <li class="ineternational" class="dropdown" data-dropdown="dropdown"><a href="javascript:void(0)"  class="dropdown-toggle" data-toggle="dropdown">INTERNATIONAL SITES <span class="caret"></span></a>
											 	<ul class="sub-menu dropdown-menu">
													 <li class=""><a href="http://tendersoftware.com.au"> 
													 	<img src="http://tendersoftware.com/wp-content/themes/tendersoftware/img/aus_flag.png" alt="tendersoftware.com.au" />
														<span>Australia</span></a></li>
													</ul>	
													 </li>
                                         </ul></div></div>
		 
									</div><!--.navbar-collapse-->
								</nav>
						</div><!--.main-navigation-->
				   </div>
                   
				   
				</div>
			</div>
			</header>
		</div>
		<div class="banner-section">
							<img class="inner-banner" src="http://tendersoftware.com/wp-content/uploads/2016/07/inner-banner.jpg" />
					</div>	
		<div class="container page-container">
			 
			<div id="content" class="row row-with-vspace site-content"> 

	  

				<div class="col-md-9 content-area" id="main-column">

					<main id="main" class="site-main" role="main">

						<article id="post-344" class="post-344 page type-page status-publish hentry">
	<header class="entry-header">
		
	</header><!-- .entry-header -->

	<div class="entry-content">
		<h3>Dear Customer, thankyou for your interest...</h3>
			<div id="feedback_error" class=" feedback_error alert alert-success alert alert-danger " style="display:none;"> 
				<?php echo (isset($error) && $error !='')? $error : "" ?>
			</div>
			<div class="  <?php if( isset($_GET['success']) ){ echo 'feedback_success alert alert-success'; }else{ echo 'hide_class'; } ?>" > 
				<?php echo (isset($_GET['success']))? $_GET['success'] : "" ?>
			</div>
			<?php 
			if(isset($type) && $type > 0 ) { 
				if($type == 4 ) { 
			?>
			<div><strong>Please help to understand more:</strong></div>
			<form class="form" method="post" action="" onsubmit="return feedback_validation();" >	
			   <div class="form-group"> 
				<div class="col-sm-10">
				  <div class="checkbox">
					<label> <input type="checkbox" class="feedback_text" name="feedback_text[]" value = "4_A"> Under skilled developers</label>
				  </div>
				</div>
			  </div>
			  <div class="form-group"> 
				<div class="col-sm-10">
				  <div class="checkbox">
					<label> <input type="checkbox" class="feedback_text" name="feedback_text[]" value = "4_B"> Poor communication, does not understand the requirements</label>
				  </div>
				</div>
			  </div>
			  <div class="form-group"> 
				<div class="col-sm-10">
				  <div class="checkbox">
					<label> <input type="checkbox"  class="feedback_text" name="feedback_text[]" value = "4_C"> Careless approach not following standards</label>
				  </div>
				</div>
			  </div>
			  <div class="form-group"> 
				<div class="col-sm-10">
				  <textarea class="form-control" rows="5" name="client_feedback" /></textarea>
				</div>
			  </div>
			  <div class="form-group"> 
				<div class="col-sm-10 div_feedback_btn">
				 <button type="submit" class="btn btn-success btn_feedback" name="btn_feedback">Submit</button>
				</div>
			  </div>
				
			</form>
        
			<?php } 
				if($type == 3 ) { 
			?>
				<div><strong>Please help to understand more:</strong></div>
				<form class="form" method="post" action="" onsubmit="return feedback_validation();" >
				   <div class="form-group"> 
					<div class="col-sm-10">
					  <div class="checkbox">
						<label><input type="checkbox" class="feedback_text" name="feedback_text[]" value = "3_A" >Developer needs too much explanation</label>
					  </div>
					</div>
				  </div>
				  <div class="form-group"> 
					<div class="col-sm-10">
					  <div class="checkbox">
						<label><input type="checkbox" class="feedback_text" name="feedback_text[]" value = "3_B" >Slow progress</label>
					  </div>
					</div>
				  </div>
				  <div class="form-group"> 
					<div class="col-sm-10">
					  <div class="checkbox">
						<label><input type="checkbox" class="feedback_text" name="feedback_text[]" value = "3_C">Too many bugs produced</label>
					  </div>
					</div>
				  </div>
				 <div class="form-group"> 
					<div class="col-sm-10">
					  <textarea class="form-control" rows="5" name="client_feedback" /></textarea>
					</div>
				  </div>
				  <div class="form-group"> 
					<div class="col-sm-10 div_feedback_btn">
					 <button type="submit" class="btn btn-success btn_feedback" name="btn_feedback">Submit</button>
					</div>
				  </div>
					
				</form>
			<?php 
				}
				if($type == 2 ) { 
			?>
				<div><strong>Please help to understand more:</strong></div>
				<form class="form" method="post" action="" onsubmit="return feedback_validation();" >
				   <div class="form-group"> 
					<div class="col-sm-10">
					  <div class="checkbox">
						<label><input type="checkbox" class="feedback_text" name="feedback_text[]" value = "2_A" >Ok but improvement is possible </label>
					  </div>
					</div>
				  </div>
				  <div class="form-group"> 
					<div class="col-sm-10">
					  <textarea class="form-control" rows="5" name="client_feedback" /></textarea>
					</div>
				  </div>
				  <div class="form-group"> 
					<div class="col-sm-10 div_feedback_btn">
					 <button type="submit" class="btn btn-success btn_feedback" name="btn_feedback" >Submit</button>
					</div>
				  </div>
				</form>
			<?php 
				}
				if($type == 1 ) { 
			?>
				
				<form class="form" method="post" action="">
				   <div class="form-group" action="" > 
					<div class="col-sm-10">
					  Great! Thanks for letting us know!
					</div>
				  </div>
				  <div class="form-group"> 
					<div class="col-sm-10">
					  <textarea class="form-control" rows="5" id="client_feedback_text" name="client_feedback"/></textarea>
					</div>
				  </div>
				  <div class="form-group"> 
					<div class="col-sm-10 div_feedback_btn">
					 <button type="submit" class="btn btn-success btn_feedback" name="btn_feedback">Submit</button>
					</div>
				  </div>
				</form>
			<?php } 
			}	
			?>
		<div class="clearfix"></div>
			</div><!-- .entry-content -->
	
	<footer class="entry-meta">
		<a class="post-edit-link btn btn-default btn-xs" href="" title="Edit"><i class="edit-post-icon glyphicon glyphicon-pencil" title="Edit"></i></a> 
	</footer>
</article><!-- #post-## -->



 

					</main>

				</div>
</div>
</div>
 

<div class="clearfix"></div>

<!--<div id="map"></div>-->

<div class="clearfix"></div>


			</div><!--.site-content-->

		</div><!--.container page-container-->

			<div class="ts_contact section">

				<div class="container">

					<div class="contact_title">Contact Info</div>

					<div class="sep-icon"><span></span></div>

					<div class="row col-sm-1"></div>

					<div class="row contact-detail col-sm-5 col-xs-12">

						<div class="col-sm-12 col-xs-4 foot1 wow zoomIn" data-wow-delay="0.4s">

							<div class="left">

								<div class="icon"><i class="fa fa-map-marker"></i></div>

							</div>

							<div class="right">

								<div class="foottitle">Address</div>

                             </div>  

                             <div class="left">&nbsp;</div> 

                             <div class="right">

								<p>340 S, Lemon Ave, #6652N, Walnut, CA, 91789, USA</p><p>1010 Spring-Cypress Road, #102, Spring TX, 77373, USA</p>
							</div>

						</div>

						<div class="col-sm-12 col-xs-4 foot2 wow zoomIn" data-wow-delay="0.4s">

							<div class="left">

								<div class="icon"><i class="fa fa-mobile"></i></div>

							</div>

							<div class="right">

								<div class="foottitle">Phone</div>

								<p>USA: 1-888-975-7080</p>
							</div>

						</div>
                        
                        
                        <div class="col-sm-12 col-xs-4 foot2 wow zoomIn" data-wow-delay="0.4s">

							<div class="left">

								<a href="http://tendersoftware.com/contact/"><div class="icon"><i class="fa fa-envelope"></i></div></a>

							</div>

							<div class="right">

								<div class="foottitle"><a href="http://tendersoftware.com/contact/">Send us a message</a></div>

								

							</div>

						</div>
                        

						<!--<div class="col-sm-12 col-xs-4 foot3 wow zoomIn" data-wow-delay="0.4s">

							<div class="left">

								<div class="icon"><i class="fa fa-envelope"></i></div>

							</div>

							<div class="right">

								<div class="foottitle"><a href="contact/"></a></div>

								
							</div>

						</div>-->

					</div>

					

					<!--<div class="row cont-gmap col-sm-5 col-xs-12">

						<div id="contact-gmap"></div>

					</div>

					<div class="row col-sm-1"></div>->

				</div>

			</div>		

		
		<div class="container-fluid ts-footer">

        	<footer class="container" id="site-footer" role="contentinfo">

				<div id="footer-row" class="row site-footer">

					
                    <div class="col-md-4 col-sm-4 footer-left">

                    
					

					© 2017 Tender Software Inc
					</div>

					
                    <div class="col-md-4 col-sm-4 footer-right text-right">

						<div class="social_icon">

							<ul>

								 <li><a target="_blank" href="https://twitter.com/TenderSoftware"><i class="fa fa-twitter"></i></a></li>
								<li><a target="_blank" href="https://www.facebook.com/tendersoftware/"><i class="fa fa-facebook"></i></a></li>
								
								
                                
                                <li><a target="_blank" href="https://www.linkedin.com/company/tender-software"><i class="fa fa-linkedin"></i></a></li>
                                

							</ul>

						</div>

					</div>

                    
                    
                    <div class="col-md-4 col-sm-4 footer-right text-center">

                    
						<!-- <div class="footerlogo"><a href="http://tendersoftware.net/" title="Tender Software"><img src="http://tendersoftware.com/wp-content/uploads/2016/02/footerlogo.png" title="Tender Software" alt="Tender Software" /></a></div> 

						American ADM LLC  - <a href="#">Privacy Policy</a>-->

					</div>

					

				</div>

			</footer>

		</div>

		<!--wordpress footer-->

		
            
<script type='text/javascript' src='http://tendersoftware.com/wp-content/plugins/contact-form-7/includes/js/jquery.form.min.js?ver=3.51.0-2014.06.20'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var _wpcf7 = {"recaptcha":{"messages":{"empty":"Please verify that you are not a robot."}}};
/* ]]> */
</script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/plugins/contact-form-7/includes/js/scripts.js?ver=4.6.1'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/plugins/revslider/public/assets/js/jquery.themepunch.tools.min.js?ver=5.2.5'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/plugins/revslider/public/assets/js/jquery.themepunch.revolution.min.js?ver=5.2.5'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/plugins/wp-slick-slider-and-image-carousel/assets/js/slick.min.js?ver=1.2.4'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/themes/tendersoftware/js/vendor/bootstrap.min.js?ver=4.7.5'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/themes/tendersoftware/js/wow.js?ver=4.7.5'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-content/themes/tendersoftware/js/main.js?ver=4.7.5'></script>
<script type='text/javascript' src='http://tendersoftware.com/wp-includes/js/wp-embed.min.js?ver=4.7.5'></script>


	




  <script>
  jQuery(window).bind("load", function(){jQuery('#top-a > div > .module').removeAttr('style');jQuery(window).resize();jQuery('#top-a > div > .module').attr('style','min-height:1px;');});
  

 
	function feedback_validation(){  
		//alert('call feedback_validation');
		//var checkboxes = document.getElementsByName('feedback_text');
		
		 var chk = document.getElementsByName('feedback_text[]')
		 var len = chk.length; //alert(len);
		 var c = 0;
		  for(i=0;i<len;i++)
			{
				if(chk[i].checked){
					c ++;
				}
			}
			
			//alert(c);
			
			if( c > 0 ){
				document.getElementById("feedback_error").style.display = "none";
				return true;
			}else{  //alert("Please select any one option");
				document.getElementById("feedback_error").innerHTML = "Please choose your input below";
				document.getElementById("feedback_error").style.display = "block"; 
				return false;
			}	
		 return false;
	}	
	/*
	function feedback_validation1(){
		var client_feedback_text = document.getElementById("client_feedback_text").value();
		if(client_feedback_text == ''){
			return false;
		}	
		return false;
	}*/	
  
  </script>
	</body>

</html>