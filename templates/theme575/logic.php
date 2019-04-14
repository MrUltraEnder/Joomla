<?php defined('_JEXEC') or die;

// variables
$app = JFactory::getApplication();
$doc = JFactory::getDocument(); 
$params = $app->getParams();
$headdata = $doc->getHeadData();
$menu = $app->getMenu();
$active = $app->getMenu()->getActive();
$pageclass = $params->get('pageclass_sfx');
$tpath = $this->baseurl.'/templates/'.$this->template;
$templateparams	= $app->getTemplate(true)->params;
$option = JRequest::getString('option', null);
$view = JRequest::getString('view', null);

// parameter
$logo = $this->params->get('logo');
$logo2 = $this->params->get('logo2');

$animation = $templateparams->get('home_animate');


// generator tag
$this->setGenerator(null);


// force latest IE & chrome frame
$doc->setMetadata('x-ua-compatible', 'IE=edge,chrome=1');


// add javascripts

// if ($option != "com_virtuemart") {
// 	$doc->addScript($tpath.'/js/jquery-1.10.2.min.js');
// 	$doc->addScript($tpath.'/js/jquery-migrate-1.2.1.min.js');
// 	$doc->addScript($tpath.'/js/jquery-noconflict.js');
// }

//$doc->addScript($tpath.'/js/jquery-1.11.1.min.js');
//$doc->addScript($tpath.'/js/jquery-migrate-1.2.1.min.js'); ?> 
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<?php 
$doc->addStyleSheet($tpath.'/css/normalize.css');
$doc->addStyleSheet($tpath.'/css/font-awesome.css');
$doc->addStyleSheet($tpath.'/css/bootstrap.css');
$doc->addStyleSheet($tpath.'/css/vm/allvmscripts.css');
$doc->addStyleSheet($tpath.'/css/vm/virtuemart.css');
//$doc->addStyleSheet($tpath.'/css/swipebox.css');
//$doc->addStyleSheet($tpath.'/css/jquery.jqzoom.css');
//$doc->addStyleSheet($tpath.'/css/owl.carousel.css');
//$doc->addStyleSheet($tpath.'/css/owl.transitions.css');
//$doc->addStyleSheet($tpath.'/css/jquery.selectBoxIt.css');
if($animation == '1'){
$doc->addStyleSheet($tpath.'/css/animate.css');
}
$doc->addStyleSheet($tpath.'/css/template.css');

// favicon
?>

<link rel="shortcut icon" href="<?php echo $tpath; ?>/favicon.ico" />

<?php
// disable position conditions

switch ($view) {
	case 'user':
	case 'orders':
	case 'cart':
	case 'contact':
	case 'wrapper':
	case 'productdetails':
		$hide_position 	= true;
		$hide_asides 	= true;
		break;

	case 'category':
	case 'manufacturer':
	case 'vendor':
	case 'article':
	case 'archive':
	case 'categories':
	case 'featured':
	case 'login':
	case 'profile':
	case 'reset':
	case 'registration':
	case 'remind':
	case 'search':
		$hide_position 	= true;
		$hide_asides 	= false;
		break;

	case 'virtuemart':
		$hide_position 	= false;
		$hide_asides 	= false;
		break;

	default:
		$hide_position 	= false;
		$hide_asides 	= false;
		break;
}


// main row grid logic
$aside_width = 3;

$modules_in_left = $this->countModules( 'aside-left' );
$modules_in_right = $this->countModules( 'aside-right' );

if ($modules_in_left > 0 && $modules_in_right > 0 && $hide_asides == false) {
  $content_width = 12 - $aside_width * 2;
} else if ((($modules_in_left > 0 && $modules_in_right == 0) || ($modules_in_left == 0 && $modules_in_right > 0)) && $hide_asides == false){
  $content_width = 12 - $aside_width;
}  else {
  $content_width = 12;
}


// bulk module position conditions
function position_enabled($positions = array()){
	$doc = JFactory::getDocument(); 
	if (count($positions) > 0) {
		foreach ($positions as $position) {
			if ($doc->countModules( $position )) {
				return true;
			} else {
				return false;
			}

		}
	} else {
		return false;
	}
}

?>