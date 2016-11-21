<?php

function nxs_widgets_pagebackground_geticonid() {
	return "nxs-icon-image";
}

function nxs_widgets_pagebackground_gettitle() {
	return nxs_l18n__("Page background", "nxs_td");
}

function nxs_widgets_pagebackground_registerhooksforpagewidget($args)
{	
	$pagedecoratorid = $args["pagedecoratorid"];
	$pagedecoratorwidgetplaceholderid = $args["pagedecoratorwidgetplaceholderid"];
		
	global $nxs_pagebackground_pagedecoratorid;
	$nxs_pagebackground_pagedecoratorid = $pagedecoratorid;
	global $nxs_pageslider_pagedecoratorwidgetplaceholderid;
	$nxs_pageslider_pagedecoratorwidgetplaceholderid = $pagedecoratorwidgetplaceholderid;
		
	add_action('nxs_beforeend_head', 'nxs_widgets_pagebackground_beforeend_head');
}

// kudos to http://css-tricks.com/perfect-full-page-background-image/
function nxs_widgets_pagebackground_beforeend_head()
{
	global $nxs_pagebackground_pagedecoratorid;
	global $nxs_pageslider_pagedecoratorwidgetplaceholderid;
	
	$metadata = nxs_getwidgetmetadata($nxs_pagebackground_pagedecoratorid, $nxs_pageslider_pagedecoratorwidgetplaceholderid);
	extract($metadata);
	
	$backgroundcolorhtml = "";
	if ($flatcolor_linkcolorvar != "")
	{
		// Background flat color
		
		// het is niet zo dat alle kleur variaties server side zijn vastgelegd;
		// alleen de midden kleuren zijn vastgelegd. Als de gebruiker als kleur
		// een "l" (lighter) variant heeft gekozen, moeten we deze dus 
		// serverside or clientside afleiden
		
		$key = "color_" . $flatcolor_linkcolorvar;
		$key = str_replace("-", "_", $key);
				
		$backgroundcolorhtml = '
		var csl = nxs_js_getruntimecolorschemelookup();
		var hexcolor = csl["' . $key . '"];
		nxs_js_log("setting color to " + hexcolor);
		jQuery("html").css("background-color", hexcolor);
		';
		
		/*
		if (nxs_isdebug())
		{
			var_dump($key);
			var_dump($metadata);
			die();
		}
		*/
	}

	$backgroundimagehtml = "";
	if ($image_imageid != "") 
	{
		// Background image
		$imagemetadata = wp_get_attachment_image_src($image_imageid, 'full', true);
		// Returns an array with $imagemetadata: [0] => url, [1] => width, [2] => height
		$imageurl = $imagemetadata[0];
		$imageurl = nxs_img_getimageurlthemeversion($imageurl);

		// background position
		if (!$image_position) {
			$image_position = "left top";
		}

		// image size
		if ($image_size == "cover" && $image_isfixed == ""){
			// do nothing
		} else {
			$backgroundimagehtml .= '
			jQuery("body").css("background-image", "url(' . $imageurl . ')");
			';
		}
		
		// background size
		if ($image_isfixed != "")
		{
			$backgroundimagehtml .= '
			jQuery("body").css("background-attachment", "fixed");
			';
		}
		
		if ($image_size == "cover")
		{
			if ($image_isfixed != "")
			{
				?>
				<?php
				if (nxs_ishandheld())
				{
					echo '<div id="nxs-background-image-fallback"></div>';
					$backgroundimagehtml .= '
					jQuery("#nxs-background-image-fallback").css("background-image", "url(' . $imageurl . ')");
					jQuery("#nxs-background-image-fallback").css("background-position", "' . $image_position . '");

					jQuery(window).on("nxs_event_resizeend.background", function(){
						nxs_change_background_size();
					});

					jQuery(window).trigger("nxs_event_resizeend.background");

					function nxs_change_background_size() {
						var windowHeight = jQ_nxs(window).height();
						var windowWidth = jQ_nxs(window).width();
						jQuery("#nxs-background-image-fallback").css({ width: windowWidth, height: windowHeight });
					}
					';
				}
				else
				{
					$backgroundimagehtml .= '
					jQuery("body").css("-webkit-background-size", "cover");
					jQuery("body").css("-moz-background-size", "cover");
					jQuery("body").css("-o-background-size", "cover");
					jQuery("body").css("background-size", "cover");
					';
				}
			}
			else
			{
				echo '<div id="nxs-background-image"></div>';
				$backgroundimagehtml .= '
				jQuery("#nxs-background-image").css("background-image", "url(' . $imageurl . ')");
				jQuery("#nxs-background-image").css("background-position", "' . $image_position . '");
				';
			}
		}
		else if ($image_size == "contain")
		{
			$backgroundimagehtml .= '
			jQuery("body").css("-webkit-background-size", "contain");
			jQuery("body").css("-moz-background-size", "contain");
			jQuery("body").css("-o-background-size", "contain");
			jQuery("body").css("background-size", "contain");
			';
		}
		
		// background repeat
		if ($image_repeat == "") { $repeatattribute = "no-repeat"; }
		if ($image_repeat == "-") { $repeatattribute = "no-repeat"; }
		if ($image_repeat == "repeatx") { $repeatattribute = "repeat-x"; }
		if ($image_repeat == "repeaty") { $repeatattribute = "repeat-y"; }
		if ($image_repeat == "repeatxy") { $repeatattribute = "repeat"; }
		
		$backgroundimagehtml .= '
			jQuery("body").css("background-repeat", "' . $repeatattribute . '");
			';

		$backgroundimagehtml .= '
			jQuery("body").css("background-position", "' . $image_position . '");
			';
	}
	?>
	<script type='text/javascript'>

		jQuery(window).load
		(
			function()
			{
				// background page decorator
				<?php
				echo $backgroundcolorhtml;
				echo $backgroundimagehtml;
				?>
			}
		);
	</script>
	<?php
}

/* WIDGET STRUCTURE
----------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------- */

// Define the properties of this widget
function nxs_widgets_pagebackground_home_getoptions($args) 
{
	$options = array
	(
		"sheettitle" => nxs_widgets_pagebackground_gettitle(),
		"sheeticonid" => nxs_widgets_pagebackground_geticonid(),
		"footerfiller" => true,
		"fields" => array
		(
			// SLIDES			
			
			array
			( 
				"id" 				=> "wrapper_input_begin",
				"type" 				=> "wrapperbegin",
				"label" 			=> nxs_l18n__("Display", "nxs_td"),
			),
			
			array
			( 
				"id" 				=> "flatcolor_linkcolorvar",
				"type" 				=> "colorvariation",
				"scope" 			=> "background",
				"label" 			=> nxs_l18n__("Backgroundcolor", "nxs_td"),
			),
			
			array
			( 
				"id" 				=> "image_imageid",
				"type" 				=> "image",
				"label" 			=> nxs_l18n__("Image", "nxs_td"),
				"unicontentablefield" => true,				
			),
			array
			(
				"id" 				=> "image_repeat",
				"type" 				=> "select",
				"label" 			=> nxs_l18n__("Image repeat", "nxs_td"),
				"dropdown" 			=> nxs_style_getdropdownitems("backgroundimage_repeat")
			),
			array
			(
				"id" 				=> "image_isfixed",
				"type" 				=> "checkbox",
				"label" 			=> nxs_l18n__("Image fixed", "nxs_td"),
				"tooltip" 			=> nxs_l18n__("When checked, the background image is fixed; it wont scroll", "nxs_td")
			),
			array
			(
				"id" 				=> "image_size",
				"type" 				=> "select",
				"label" 			=> nxs_l18n__("Image size", "nxs_td"),
				"dropdown" 			=> nxs_style_getdropdownitems("backgroundimage_size")
			),
			array
			(
				"id" 				=> "image_position",
				"type" 				=> "radiobuttons",
				"layout" 			=> "3x3",
				"default" 			=> "left top",
				"label" 			=> nxs_l18n__("Image position", "nxs_td"),
				"subtype"			=> "backgroundimage_position"
			),
			array
			( 
				"id" 				=> "wrapper_input_end",
				"type" 				=> "wrapperend"
			),
		)
	);
	
	return $options;
}


/* ADMIN PAGE HTML
----------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------- */

function nxs_widgets_pagebackground_render_webpart_render_htmlvisualization($args) 
{
	// Importing variables
	extract($args);
	
	// Every widget needs it's own unique id for all sorts of purposes
	// The $postid and $placeholderid are used when building the HTML later on
	$temp_array = nxs_getwidgetmetadata($postid, $placeholderid);
	
	// The $mixedattributes is an array which will be used to set various widget specific variables (and non-specific).
	$mixedattributes = array_merge($temp_array, $args);
	
	// Output the result array and setting the "result" position to "OK"
	$result = array();
	$result["result"] = "OK";
	
	// Widget specific variables
	extract($mixedattributes);
	
	// popup menu
	
	$hovermenuargs = array();
	$hovermenuargs["postid"] = $postid;
	$hovermenuargs["placeholderid"] = $placeholderid;
	$hovermenuargs["placeholdertemplate"] = $placeholdertemplate;
	$hovermenuargs["enable_decoratewidget"] = false;
	$hovermenuargs["enable_deletewidget"] = false;
	$hovermenuargs["enable_deleterow"] = true;
	$hovermenuargs["metadata"] = $mixedattributes;	
	nxs_widgets_setgenericwidgethovermenu_v2($hovermenuargs);
	
	// Turn on output buffering
	nxs_ob_start();
	
	// Setting the widget name variable to the folder name
	$widget_name = basename(dirname(__FILE__));
		
	global $nxs_global_placeholder_render_statebag;
	if ($shouldrenderalternative == true) {
		$nxs_global_placeholder_render_statebag["widgetclass"] = "nxs-" . $widget_name . "-warning ";
	} else {
		// Appending custom widget class
		$nxs_global_placeholder_render_statebag["widgetclass"] = "nxs-" . $widget_name . " ";
	}
	
	/* EXPRESSIONS
	---------------------------------------------------------------------------------------------------- */
	// Check if specific variables are empty
	// If so > $shouldrenderalternative = true, which triggers the error message
	$shouldrenderalternative = false;
	/*
	if (
		somealternativeflow
	) {
		$shouldrenderalternative = true;
		$alternativehint = nxs_l18n__("Minimal: image, title, text or button", "nxs_td");
	}
	*/
	
		
	/* OUTPUT
	---------------------------------------------------------------------------------------------------- */

	if ($shouldrenderalternative) 
	{
		if ($alternativehint == "")
		{
			$alternativehint = nxs_l18n__("Missing input", "nxs_td");
		}
		nxs_renderplaceholderwarning($alternativehint); 
	} 
	else 
	{
		/* ADMIN OUTPUT
		---------------------------------------------------------------------------------------------------- */
		
		echo '
		<div class="nxs-dragrow-handler nxs-padding-menu-item">
		<div class="content2">
		 <div class="box">
		        <div class="box-title">
		   <h4>Background image and color</h4>
		  </div>
		  <div class="box-content"></div>
		 </div>
		 <div class="nxs-clear"></div>
		</div>
		</div>';
		
		/* ------------------------------------------------------------------------------------------------- */
	}
	
	/* ------------------------------------------------------------------------------------------------- */
	 
	// Setting the contents of the output buffer into a variable and cleaning up te buffer
	$html = nxs_ob_get_contents();
	nxs_ob_end_clean();
	
	// Setting the contents of the variable to the appropriate array position
	// The framework uses this array with its accompanying values to render the page
	$result["html"] = $html;	
	$result["replacedomid"] = 'nxs-widget-' . $placeholderid;
	return $result;
}


function nxs_widgets_pagebackground_initplaceholderdata($args)
{
	extract($args);

	$args['image_position'] = "left top";
		
	nxs_mergewidgetmetadata_internal($postid, $placeholderid, $args);
	
	$result = array();
	$result["result"] = "OK";
	
	return $result;
}

?>
