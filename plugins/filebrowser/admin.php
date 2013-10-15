<?php
/* utf-8 marker: äöü */

if (!$adm || $cf['filebrowser']['external'] /*|| $backend_hooks['filebrowser']*/) {
    return true;
}

initvar('filebrowser');

if ($filebrowser) {
    $plugin = basename(dirname(__FILE__));
    $plugin = basename(dirname(__FILE__), "/");
	$o .= '<div class="plugintext">';
	$o .= '<br /><hr /><p>' . $tx['message']['plugin_standard1'] . '</p><p>' . $tx['message']['plugin_standard2'] . ' <a href="./?file=config&action=array"><b>' . $tx['filetype']['config'] . '</b></a></p><hr />';
	$o .= '<div class="plugineditcaption">Filebrowser for CMSimple</div>';
    $o .= '<p>Version 1.2</p>';
    $o .= '</div>';
    return;
}

if (!($images || $downloads || $userfiles || $media)) {
    return true;
}

if ($images) {
    $f = 'images';
}

if ($downloads) {
    $f = 'downloads';
}

if ($userfiles) {
    $f = 'userfiles';
}

if ($media) {
    $f = 'media';
}

$browser = $_SESSION['xh_browser'];
define('XHFB_PATH', $pth['folder']['plugins'] . 'filebrowser/');
$hjs .= '<script type="text/javascript" src="' . XHFB_PATH . 'js/filebrowser.js"></script>';

$subdir = isset($_GET['subdir']) ? str_replace(array('..', '.'), '', $_GET['subdir']) : '';

if (strpos($subdir, $browser->baseDirectories[$f]) !== 0) {
    $subdir = $browser->baseDirectories[$f];
}

$browser->baseDirectory = $browser->baseDirectories[$f];
$browser->currentDirectory =  rtrim($subdir, '/') . '/';
$browser->linkType = $f;
$browser->setLinkParams($f);

if (isset($_POST['deleteFile']) && isset($_POST['file'])) {
    $browser->deleteFile($_POST['file']);
}
if (isset($_POST['deleteFolder']) && isset($_POST['folder'])) {
    $browser->deleteFolder($_POST['folder']);
}
if (isset($_POST['upload'])) {
    $browser->uploadFile();
}
if (isset($_POST['createFolder'])) {
    $browser->createFolder();
}
if (isset($_POST['renameFile'])) {
    $browser->renameFile();
}

$browser->readDirectory();

$o .= $browser->render('cmsbrowser');

$f = 'filebrowser';
$images = $downloads = $userfiles = $media = false;
/*
 * EOF filebrowser/admin.php
 */
 
?>