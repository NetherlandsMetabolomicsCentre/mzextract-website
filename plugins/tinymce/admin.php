<?php
if (!$adm){return;}

initvar('tinymce');

if ($tinymce) {
    $plugin = basename(dirname(__FILE__), "/");
    $o .= '<div class="plugintext">';
    $o .= '<br /><hr /><p>' . $tx['message']['plugin_standard1'] . '</p><p>' . $tx['message']['plugin_standard2'] . ' <a href="./?file=config&action=array"><b>' . $tx['filetype']['config'] . '</b></a></p><hr />';
    $o .= '<div class="plugineditcaption">TinyMCE for CMSimple_XH</div>';
    $o .= '<p>Version 1.1</p>';
    $o .= '<p>TinyMCE version 3.4.5  &ndash; <a href="http://www.tinymce.com/" target="_blank">tinymce.com</a></p>';
    $o .= '<p>Filebrowser integration &ndash; <a href="http://www.zeichenkombinat.de/" target="_blank">zeichenkombinat.de</a></p>';
    $o .= '<p>Adapted for CMSimple 4 and higher &ndash; <a href="http://www.ge-webdesign.de/" target="_blank">ge-webdesign.de</a></p>';



    $admin= isset($_POST['admin']) ? $_POST['admin'] : $admin = isset($_GET['admin']) ? $_GET['admin'] : '';
    $action= isset($_POST['action']) ? $_POST['action'] : $action = isset($_GET['action']) ? $_GET['action'] : '';
    $o .= plugin_admin_common($action,$admin,$plugin);

    if ($action === 'plugin_save')  // refresh
    {
        include $pth['folder']['plugins'] . $plugin . '/config/config.php';
    }


    $inits = glob($pth['folder']['plugins'] . 'tinymce/inits/*.js');
    $options = array();

    foreach ($inits as $init) 
    {
        $temp = explode('_', basename($init, '.js'));

        if (isset($temp[1])) 
        {
            $options[] = $temp[1];
        }
    }
    $o .= '</div>';
}
?>