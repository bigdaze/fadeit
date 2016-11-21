<?php

define('NXS_THEMENAME', "X");
define('NXS_THEMEVERSION', "1");

if (!defined('NXS_FRAMEWORKLOADED'))
{
	//
	// ----------------------------------------------------------------------
	//

	define('NXS_FRAMEWORKNAME', "nexusframework");
	define('NXS_FRAMEWORKVERSION', "stable");

	//echo "thats all :)";
	//die();


	$frameworkpath = TEMPLATEPATH . "/" . NXS_FRAMEWORKNAME . "/" . NXS_FRAMEWORKVERSION;
	if (!file_exists($frameworkpath))
	{
		// if the specific theme doesn't contain the framework,
		// the fallback scenario is to locate the framework
		$frameworkpath = dirname(dirname(TEMPLATEPATH)) . "/" . NXS_FRAMEWORKNAME . "/" . NXS_FRAMEWORKVERSION;
		if (!file_exists($frameworkpath))
		{
			echo "[Sorry, unable to locate the framework path]";
			die();
		}
		else
		{
			define('NXS_FRAMEWORKSHARED', "true");
		}
	}
	else
	{
		define('NXS_FRAMEWORKSHARED', "false");
	}

	define('NXS_FRAMEWORKPATH', $frameworkpath);

	//
	// import functions of framework
	//
	require_once(NXS_FRAMEWORKPATH . '/framework-functions.php');
}
else
{
	// framework was already loaded (by plugin)
}

//
// ----------------------------------------------------------------------
//


//
//
//

add_action("nxs_getcolorsinpalette", "nxs_ext_getcolorsinpalette");
function nxs_ext_getcolorsinpalette($result)
{
	$result[] = "c2";
	$result[] = "c3";

	return $result;
}

function nxs_theme_getmeta()
{
	$result = array();
	$result["url"] = "https://nexusthemes.com/wordpress-themes/beauty/barber-shop-wordpress-theme/";
	$result["title"] = "Barbershop WordPress theme";
	$result["version"] = "3.0.160131.1836";
	$result["id"] = "barbershop";
	return $result;
}

// Custom Filters for changing wordpress mail settings
