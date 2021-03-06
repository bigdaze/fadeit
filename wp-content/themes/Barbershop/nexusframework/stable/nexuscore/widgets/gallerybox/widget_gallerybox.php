<?php

function nxs_widgets_gallerybox_geticonid() {
	$widget_name = basename(dirname(__FILE__));
	return "nxs-icon-" . $widget_name;
}

// Setting the widget title
function nxs_widgets_gallerybox_gettitle() {
	return nxs_l18n__("Gallery box", "nxs_td");
}

// 
function nxs_widgets_gallerybox_getunifiedstylinggroup() {
	return "galleryboxwidget";
}

/* WIDGET STRUCTURE
----------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------- */

// Define the properties of this widget
function nxs_widgets_gallerybox_home_getoptions($args) 
{
	$options = array
	(
		"sheettitle" => nxs_widgets_gallerybox_gettitle(),
		"sheeticonid" => nxs_widgets_gallerybox_geticonid(),
		"sheethelp" => nxs_l18n__("http://nexusthemes.com/image-gallery-widget/"),
		"unifiedstyling" => array
		(
			"group" => nxs_widgets_gallerybox_getunifiedstylinggroup(),
		),
		"fields" => array
		(
			// TITLE	
			
			array( 
				"id" 				=> "wrapper_title_begin",
				"type" 				=> "wrapperbegin",
				"label" 			=> "Title",
				"initial_toggle_state"	=> "closed",
			),
			
			array(
				"id" 				=> "title",
				"type" 				=> "input",
				"label" 			=> "Title",
				"placeholder" 		=> "Title goes here",
				"localizablefield"	=> true
			),
			array(
				"id" 				=> "title_heading",
				"type" 				=> "select",
				"label" 			=> nxs_l18n__("Title importance", "nxs_td"),
				"dropdown" 			=> nxs_style_getdropdownitems("title_heading"),
				"unistylablefield"	=> true
			),
			
			array(
				"id" 				=> "title_alignment",
				"type" 				=> "radiobuttons",
				"subtype" 			=> "halign",
				"label" 			=> nxs_l18n__("Title alignment", "nxs_td"),
				"unistylablefield"	=> true
			),
						
			array(
				"id" 				=> "title_fontsize",
				"type" 				=> "select",
				"label" 			=> nxs_l18n__("Override title fontsize", "nxs_td"),
				"dropdown" 			=> nxs_style_getdropdownitems("fontsize"),
				"unistylablefield"	=> true
			),
			array(
				"id" 				=> "title_heightiq",
				"type" 				=> "checkbox",
				"label" 			=> nxs_l18n__("Row align titles", "nxs_td"),
				"tooltip" 			=> nxs_l18n__("When checked, the widget's title will participate in the title alignment of other partipating widgets in this row", "nxs_td"),
				"unistylablefield"	=> true
			),
			
			array( 
				"id" 				=> "wrapper_title_end",
				"type" 				=> "wrapperend"
			),			
			
			// IMAGE
			
			array( 
				"id" 				=> "wrapper_selection_begin",
				"type" 				=> "wrapperbegin",
				"label" 			=> nxs_l18n__("Image", "nxs_td"),
			),
			
			array(
				"id" 				=> "items_genericlistid",
				"type" 				=> "staticgenericlist_link",
				"label" 			=> nxs_l18n__("Photos", "nxs_td"),
			),
			array(
				"id" 				=> "orientation",
				"type" 				=> "select",
				"label" 			=> nxs_l18n__("Orientation", "nxs_td"),
				"dropdown" 			=> array(
					"landscape"		=>"landscape",
					"portrait"		=>"portrait",
				),
				"unistylablefield"	=> true
			),	
			array(
				"id" 				=> "numofcolumns",
				"type" 				=> "select",
				"label" 			=> nxs_l18n__("Number of columns label", "nxs_td"),
				"dropdown" 			=> nxs_convertindexarraytoassociativearray(array("2","3","4")),
				"unistylablefield"	=> true
			),
			array(
				"id" 				=> "item_title_heading",
				"type" 				=> "select",
				"label" 			=> nxs_l18n__("Title heading for item", "nxs_td"),
				"dropdown" 			=> nxs_style_getdropdownitems("title_heading"),
				"unistylablefield"	=> true
			),	
			array(
				"id" 				=> "image_border_width",
				"type" 				=> "select",
				"label" 			=> nxs_l18n__("Border size", "nxs_td"),
				"dropdown" 			=> nxs_style_getdropdownitems("border_width"),
				"unistylablefield"	=> true
			),
			array( 
				"id" 				=> "remove_image_shadow",
				"type" 				=> "checkbox",
				"label" 			=> nxs_l18n__("Remove image shadow", "nxs_td"),
				"unistylablefield"	=> true
			),	
			
			array( 
				"id" 				=> "wrapper_selection_end",
				"type" 				=> "wrapperend"
			),
			
		)
	);
	
	nxs_extend_widgetoptionfields($options, array("backgroundstyle"));
	
	return $options;
}


/* WIDGET HTML
----------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------- */

function nxs_widgets_gallerybox_render_webpart_render_htmlvisualization($args) 
{	
	// Importing variables
	extract($args);
	
	// Every widget needs it's own unique id for all sorts of purposes
	// The $postid and $placeholderid are used when building the HTML later on
	$temp_array = nxs_getwidgetmetadata($postid, $placeholderid);
	
	$unistyle = $temp_array["unistyle"];
	if (isset($unistyle) && $unistyle != "")
	{
		// blend unistyle properties
		$unistyleproperties = nxs_unistyle_getunistyleproperties(nxs_widgets_gallerybox_getunifiedstylinggroup(), $unistyle);
		$temp_array = array_merge($temp_array, $unistyleproperties);	
	}
	
	// The $mixedattributes is an array which will be used to set various widget specific variables (and non-specific).
	$mixedattributes = array_merge($temp_array, $args);
	
	// Localize atts
	$mixedattributes = nxs_localization_localize($mixedattributes);
	
	// Output the result array and setting the "result" position to "OK"
	$result = array();
	$result["result"] = "OK";
	
	// Widget specific variables
	extract($mixedattributes);
	
	$hovermenuargs = array();
	$hovermenuargs["postid"] = $postid;
	$hovermenuargs["placeholderid"] = $placeholderid;
	$hovermenuargs["placeholdertemplate"] = $placeholdertemplate;
	$hovermenuargs["metadata"] = $mixedattributes;
	nxs_widgets_setgenericwidgethovermenu_v2($hovermenuargs); 
		
	// Turn on output buffering
	nxs_ob_start();
	
	// Setting the widget name variable to the folder name
	$widget_name = basename(dirname(__FILE__));

	global $nxs_global_row_render_statebag;
	global $nxs_global_placeholder_render_statebag;
		
	// Appending custom widget class
	$nxs_global_placeholder_render_statebag["widgetclass"] = "nxs-gallery ";
	
	
	
	
	/* EXPRESSIONS
	---------------------------------------------------------------------------------------------------- */

	$structure = nxs_parsepoststructure($items_genericlistid);
	if (count($structure) == 0) {
		$alternativemessage = nxs_l18n__("Warning:no items found", "nxs_td");
	}
		
	if ($nxs_global_row_render_statebag["pagerowtemplate"] != "one") {
		$alternativemessage = nxs_l18n__("Warning:please move the gallerybox to a row that has exactly 1 column", "nxs_td");
	}
	
	
	
	// Default HMTL rendering
	$htmltitle = nxs_gethtmlfortitle($title, $title_heading, $title_alignment, $title_fontsize, $title_heightiq, "", "");

	/* OUTPUT
	---------------------------------------------------------------------------------------------------- */
	
	if ($alternativemessage != "" && $alternativemessage != null) {
		nxs_renderplaceholderwarning($alternativemessage);
	} else {
		$nxs_global_row_render_statebag["rrs_cssclass"].= " row-no-border-right ";
		
		?>
		<script type='text/javascript'>
			// opens a gallery thumbnail in a lightbox
			function nxs_js_opengalleryitemlightbox(element)
			{
				if (nxs_js_popup_anyobjectionsforopeningnewpopup())
				{
					// opening a new popup is not allowed; likely some other popup is already opened
					return;
				}
				
				var galleryitem = jQuery(element).closest(".nxs-galleryitem")[0];
				//nxs_js_log("galleryitem:");
				//nxs_js_log(galleryitem);
				var galleryitemid = galleryitem.id;	// bijv. nxs-galleryitem-{galleryid}-{index}-{imageid}
				var galleryid = galleryitemid.split("-")[2];
				var index = galleryitemid.split("-")[3];
				var imageid = galleryitemid.split("-")[4];
				
				// initiate a new popupsession data as this is a new session
				nxs_js_popupsession_startnewcontext();
				
				// move gallerybox sheet implementation to seperate file, not in site.php
				nxs_js_popup_setsessioncontext("popup_current_dimensions", "gallerybox");
				nxs_js_popup_setsessioncontext("contextprocessor", "gallerybox");
				nxs_js_popup_setsessioncontext("galleryid", galleryid);
				nxs_js_popup_setsessioncontext("imageid", imageid);
				nxs_js_popup_setsessioncontext("index", '' + index + '');
				nxs_js_popup_setsessioncontext("containerpostid", nxs_js_getcontainerpostid());
				
				// show the popup
				nxs_js_popup_navigateto_v2("detail", false);
			}
		</script>
		
		<?php
		
		if ($title != "") {
			echo $htmltitle;
		}
		
		$index = -1;
		foreach ($structure as $pagerow)
		{
			$index = $index + 1;
			$rowcontent = $pagerow["content"];
			$placeholderid = nxs_parsepagerow($rowcontent);
			$placeholdermetadata = nxs_getwidgetmetadata($items_genericlistid, $placeholderid);

			$placeholdertype = $placeholdermetadata["type"];
			
			if ($placeholdertype != "" && $placeholdertype != "undefined")
			{
				// Localize atts
				$placeholdermetadata = nxs_localization_localize($placeholdermetadata);

				// load widget
				nxs_requirewidget($placeholdertype);
				
				// dynamic function invocation
				$functionnametoinvoke = "nxs_widgets_{$placeholdertype}_renderingallery";
				$args = array();
				$args["placeholdermetadata"] = $placeholdermetadata;
				$args["orientation"] = $orientation;
				$args["numofcolumns"] = $numofcolumns;
				$args["index"] = $index;
				$args["items_genericlistid"] = $items_genericlistid;
				$args["item_title_heading"] = $item_title_heading;
				$args["remove_image_shadow"] = $remove_image_shadow;
				$args["image_border_width"] = $image_border_width;
				nxs_function_invokefunction($functionnametoinvoke, $args);
			}
			else
			{
				// ignore
			}
		}
		
		echo "<div class='nxs-clear'></div>";
	} 
	
	/* ------------------------------------------------------------------------------------------------- */
	 
	// Setting the contents of the output buffer into a variable and cleaning up te buffer
	$html = nxs_ob_get_contents();
	nxs_ob_end_clean();
	
	// Setting the contents of the variable to the appropriate array position
	// The framework uses this array with its accompanying values to render the page
	$result["html"] = $html;	
	$result["replacedomid"] = 'nxs-widget-'.$placeholderid;
	
	return $result;
}

/* INITIATING WIDGET DATA
----------------------------------------------------------------------------------------------------*/
function nxs_widgets_gallerybox_initplaceholderdata($args)
{
	// delegate to generic implementation
	$widgetname = basename(dirname(__FILE__));
	
	// create a new generic list with subtype gallery
	// assign the newly create list to the list property
	
	$subargs = array();
	$subargs["nxsposttype"] = "genericlist";
	$subargs["nxssubposttype"] = "gallery";	// NOTE!
	$subargs["poststatus"] = "publish";
	$subargs["titel"] = nxs_l18n__("Gallery items", "nxs_td");
	$subargs["slug"] = $subargs["titel"];
	$subargs["postwizard"] = "defaultgenericlist";
	
	$response = nxs_addnewarticle($subargs);
	if ($response["result"] == "OK")
	{
		$args["items_genericlistid"] = $response["postid"];
		$args["items_genericlistid_globalid"] = nxs_get_globalid($response["postid"], true);
	}
	else
	{
		var_dump($response);
		nxs_webmethod_return_nack("unsupported result");
	}
	
	$args["image_border_width"] = "1-0";
	$args["title_heading"] = "2";	
	$args["numofcolumns"] = "3";	// initialize to 3 columns
	$args['title_heightiq'] = "true";

	// current values as defined by unistyle prefail over the above "default" props
	$unistylegroup = nxs_widgets_gallerybox_getunifiedstylinggroup();
	$args = nxs_unistyle_blendinitialunistyleproperties($args, $unistylegroup);
	
	$result = nxs_widgets_initplaceholderdatageneric($args, $widgetname);
	return $result;
}

/* UPDATING WIDGET DATA
----------------------------------------------------------------------------------------------------*/
function nxs_widgets_gallerybox_updateplaceholderdata($args) 
{
	// delegate to generic implementation
	$widgetname = basename(dirname(__FILE__));
	$result = nxs_widgets_updateplaceholderdatageneric($args, $widgetname);
	return $result;
}

?>
