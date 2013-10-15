<?php
/* utf8-marker = äöüß */

/* 
=========================================================
Adapted for CMSimple 4.0 and higher: Gert Ebersbach 2013
http://www.ge-webdesign.de
=========================================================
*/

/**
 * jQuery for CMSimple
 *
 * Include-file for use in CMSimple-Plugins
 * to enable jQuery, jQueryUI 
 * and other jQuery-based plugins
 *
 * @author Holger Irmler
 * @link http://cmsimple.holgerirmler.de
 * @version 1.3 - 2011-07-27
 * @build 2011072701
 * @package jQuery
 **/

//error_reporting(E_ALL);

//load plugin-configuration
//require($pth['folder']['plugins'].'jquery/config/config.php');

function include_jQuery($path='') 
{
	global $pth, $cf, $hjs;
	
	if(!defined('JQUERY')) 
	{
		if($path == '') 
		{
			$path = $pth['folder']['plugins'].'jquery/lib/jquery/'.$cf['jquery']['file_core'];
			if(!file_exists($path)) 
			{
				e('missing', 'file', $path);
				return;
			}
		}
		$hjs = '<script type="text/javascript" src="'.$path.'"></script>' . "\n" . $hjs; 
	}
}

function include_jQueryUI($path='') 
{
	global $pth, $cf, $hjs;
	
	if($path == '') 
	{
		$path = $pth['folder']['plugins'].'jquery/lib/jquery_ui/'.$cf['jquery']['file_ui'];
		if(!file_exists($path)) 
		{
			e('missing', 'file', $path);
			return;
		}
	}
	$hjs.= "\n" . '<script type="text/javascript" src="'.$path.'"></script>';
	
	if(file_exists($pth['folder']['template'].'jquery_ui/jquery_ui.css')) 
	{
		//load a complete custom ui-theme
		$hjs .= "\n".tag('link rel="stylesheet" type="text/css" media="screen" href="'
			 .$pth['folder']['template'].'jquery_ui/jquery_ui.css"');
	} 
	else 
	{
		//load the default theme
		$hjs = tag('link rel="stylesheet" type="text/css" media="screen" href="'.$pth['folder']['plugins']
				.'jquery/lib/jquery_ui/css/'.$cf['jquery']['file_css'].'"') . "\n" . $hjs;
		//include a custom css-file to overwrite single selectors
		if(file_exists($pth['folder']['template'].'jquery_ui/stylesheet.css')) 
		{
			$hjs.= "\n" . tag('link rel="stylesheet" type="text/css" media="screen" href="'
			.$pth['folder']['template'].'jquery_ui/stylesheet.css"');
		}
	}
}

function include_jQueryPlugin($name='', $path='') 
{
	global $hjs, $jQueryPlugins;
	
	if(!isset($jQueryPlugins)) 
	{
		$jQueryPlugins = array();
	}
	
	if($name != '') 
	{
		if(!file_exists($path)) 
		{
			e('missing', 'file', $path);
			return;
		}
		$name = strtolower($name);
		if (!in_array($name, $jQueryPlugins)) 
		{
			$hjs.= "\n" . '<script type="text/javascript" src="'.$path.'"></script>';
			$jQueryPlugins[] .= $name;
		}
	}
}
?>