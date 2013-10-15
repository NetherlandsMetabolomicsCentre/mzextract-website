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
 * Admin-interface for configuring the plugin
 * via the standard-functions of pluginloader.
 *
 * @author Holger Irmler
 * @link http://cmsimple.holgerirmler.de
 * @version 1.3.1 - 2011-09-30
 * @build 2011093001
 * @package jQuery
 **/

initvar('jquery');
if($jquery)
{
	$admin= isset($_POST['admin']) ? $_POST['admin'] : $admin = isset($_GET['admin']) ? $_GET['admin'] : '';
	$action= isset($_POST['action']) ? $_POST['action'] : $action = isset($_GET['action']) ? $_GET['action'] : '';
	$plugin=basename(dirname(__FILE__),"/");
	$o .= print_plugin_admin('off');
	
	if($admin<>'plugin_main')
	{
		$o .= plugin_admin_common($action,$admin,$plugin);
	}
	
	if ($admin == 'plugin_main') 
	{
		$o .= plugin_admin_common($action, $admin, $plugin);
	}
	
	if($admin == '') 
	{
		$o .= "\n".'<div class="plugintext">';
		$o .= '<br /><hr /><p>' . $tx['message']['plugin_standard1'] . '</p><p>' . $tx['message']['plugin_standard2'] . ' <a href="./?file=config&action=array"><b>' . $tx['filetype']['config'] . '</b></a></p><hr />';
		$o .= "\n".'<b>jQuery for CMSimple v. 1.3.1</b>' . 
'<p><b>Author:</b> &copy; 2011 <a href="http://cmsimple.holgerirmler.de/" target="_blank">http://CMSimple.HolgerIrmler.de</a></p>
<p>Adapted for CMSimple 4.0 and higher by <a href="http://www.ge-webdesign.de/" target="_blank">ge-webdesign.de</a></p>';
		$o .= "\n".'</div>';
	}
}
?>