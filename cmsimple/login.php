<?php // utf8-marker = äöü

/*
================================================== 
This file is a part of CMSimple 4.2.4
Released: 2013-09-03
Project website: www.cmsimple.org
--------------------------------------------------
CMSimple 4.0 and higher uses some code and modules of CMSimple_XH, 
a community driven CMSimple based Project
CMSimple_XH project website: www.cmsimple-xh.org
================================================== 
CMSimple COPYRIGHT INFORMATION

© Gert Ebersbach - mail@ge-webdesign.de

CMSimple 3.3 and higher is released under the GPL3 licence. 
You may not remove copyright information from the files. 
Any modifications will fall under the copyleft conditions of GPL3.

GPL 3 Deutsch: http://www.gnu.de/documents/gpl.de.html
GPL 3 English: http://www.gnu.org/licenses/gpl.html

END CMSimple COPYRIGHT INFORMATION
================================================== 
*/

global $pth;

if (preg_match('/login.php/i', $_SERVER['SCRIPT_NAME']))
    die('Access Denied');

require $pth['folder']['cmsimple'] . 'PasswordHash.php'; 
$xh_hasher = new PasswordHash(8, true);

function gc($s) 
{
    if (!isset($_COOKIE)) 
    {
        global $_COOKIE;
        $_COOKIE = $GLOBALS['HTTP_COOKIE_VARS'];
    }
    if (isset($_COOKIE[$s]))
        return $_COOKIE[$s];
}

function logincheck() 
{
    global $cf;
    
    return (gc('passwd') == $cf['security']['password']);
}

function writelog($m) 
{
    global $pth, $e;
    if ($fh = @fopen($pth['file']['log'], "a")) 
	{
        fwrite($fh, $m);
        fclose($fh);
    }
}

function lilink() // not in use anymore - fallback only
{
    global $cf, $adm, $sn, $u, $s, $tx;
    if (!$adm) 
	{
        if ($cf['security']['type'] == 'javascript')
            return '<a href="javascript:login()">' . $tx['menu']['login'] . '</a><form id="login" style="display: inline; display: none;" action="' . $sn . '" method="post">' . tag('input type="hidden" name="login" value="true"') . tag('input type="hidden" name="selected" value="' . $u[$s] . '"') . tag('input type="hidden" name="passwd" id="passwd" value=""') . '</form>';
        else
            return a($s > -1 ? $s : 0, '&amp;login') . $tx['menu']['login'] . '</a>';
    }
}

function loginforms() 
{
    global $adm, $cf, $print, $hjs, $tx, $onload, $f, $o, $s, $sn, $u;
    // Javascript placed in head section used for javascript login
    if (!$adm && $cf['security']['type'] == 'javascript' && !$print) 
	{
        $hjs .= '<script type="text/javascript"><!--
			function login(){var t=prompt("' . $tx['login']['warning'] . '","");if(t!=null&&t!=""){document.getElementById("passwd").value=t;document.getElementById("login").submit();}}
			//-->
			</script>';
    }
    if ($f == 'login') 
	{

        $cf['meta']['robots'] = "noindex";
        $onload .= "self.focus();document.login.passwd.focus();";
        $f = $tx['menu']['login'];
        $o .= '<h1>' . $tx['menu']['login'] . '</h1>
<div style="font-weight: 700;">
' . $tx['login']['warning'] . '
</div>
<div id="cmsimple_loginform">
<form id="login" name="login" action="' . $sn . '?' . $u[$s] . '" method="post">' . "\n"
 . tag('input type="hidden" name="login" value="true"') . "\n"
 . tag('input type="hidden" name="selected" value="' . @$u[$s] . '"') . "\n"
 . tag('input type="password" name="passwd" id="passwd" value=""') . ' ' . "\n"
 . tag('input type="submit" name="submit" id="submit" value="' . $tx['menu']['login'] . '"') . '
</form>
</div>';
        $s = -1;
    }
}

// LOGIN & BACKUP

$adm = (gc('status' . str_replace('index.php','',$sn)) == 'adm' && logincheck());

if ($cf['security']['type'] == 'page' && $login && $passwd == '' && !$adm) 
{
    $login = null;
    $f = 'login';
}

if ($login && !$adm) 
{
    if ($xh_hasher->CheckPassword($passwd, $cf['security']['password'])
	&& ($cf['security']['type'] == 'page' || $cf['security']['type'] == 'javascript'))
    {
		setcookie('status' . str_replace('index.php','',$sn), 'adm', 0);
		setcookie('status', 'adm', 0, CMSIMPLE_ROOT);
		setcookie('passwd', $cf['security']['password'], 0);
		$adm = true;
		$edit = true;
		writelog(date("Y-m-d H:i:s") . " from " . sv('REMOTE_ADDR') . " logged_in\n");
    }
    else
	{
		writelog(date("Y-m-d H:i:s")." from ".sv('REMOTE_ADDR')." login failed\n");
		shead('401');
	}
} 
else if ($logout && $adm) 
{
    $backupDate = date("Ymd_His");
    $fn = $backupDate . '_content.htm';
    if (@copy($pth['file']['content'], $pth['folder']['content'] . $fn)) 
	{
        $o .= '<p>' . ucfirst($tx['filetype']['backup']) . ' ' . $fn . ' ' . $tx['result']['created'] . '</p>';
        $fl = array();
        $fd = @opendir($pth['folder']['content']);
        while (($p = @readdir($fd)) == true) 
		{
            if (preg_match("/\d{3}\_content.htm/", $p))
                $fl[] = $p;
        }
        if ($fd == true)
            closedir($fd);
        @sort($fl, SORT_STRING);
        $v = count($fl) - $cf['backup']['numberoffiles'];
        for ($i = 0; $i < $v; $i++) 
		{
            if (@unlink($pth['folder']['content'] . '/' . $fl[$i]))
                $o .= '<p>' . ucfirst($tx['filetype']['backup']) . ' ' . $fl[$i] . ' ' . $tx['result']['deleted'] . '</p>';
            else
                e('cntdelete', 'backup', $fl[$i]);
        }
    }
    else
	{
        e('cntsave', 'backup', $fn);
	}

// SAVE function for pagedata.php added

    if (file_exists($pth['folder']['content'] . 'pagedata.php')) 
	{
        $fn = $backupDate . '_pagedata.php';
        if (@copy($pth['file']['pagedata'], $pth['folder']['content'] . $fn)) 
		{
            $o .= '<p>' . ucfirst($tx['filetype']['backup']) . ' ' . $fn . ' ' . $tx['result']['created'] . '</p>';
            $fl = array();
            $fd = @opendir($pth['folder']['content']);
            while (($p = @readdir($fd)) == true) 
			{
                if (preg_match("/\d{3}\_pagedata.php/", $p))
                    $fl[] = $p;
            }
            if ($fd == true)
                closedir($fd);
            @sort($fl, SORT_STRING);
            $v = count($fl) - $cf['backup']['numberoffiles'];
            for ($i = 0; $i < $v; $i++) 
			{
                if (@unlink($pth['folder']['content'] . $fl[$i]))
                    $o .= '<p>' . ucfirst($tx['filetype']['backup']) . ' ' . $fl[$i] . ' ' . $tx['result']['deleted'] . '</p>';
                else
                    e('cntdelete', 'backup', $fl[$i]);
            }
        }
        else
		{
            e('cntsave', 'backup', $fn);
		}
    }

// END save function for pagedata.php


    $adm = false;
    setcookie('status' . str_replace('index.php','',$sn), '', 0);
	setcookie('status', '', 0, CMSIMPLE_ROOT);
    setcookie('passwd', '', 0);
    $o .= '<p class="cmsimplecore_warning" style="text-align: center; font-weight: 900; padding: 8px;">' . $tx['login']['loggedout'] . '</p>';
}

// SETTING FUNCTIONS AS PERMITTED

if ($adm) 
{
    if ($edit)
        setcookie('mode', 'edit', 0);
    if ($normal)
        setcookie('mode', '', 0);
    if (gc('mode') == 'edit' && !$normal)
        $edit = true;
} 
else 
{
    if (gc('status' . str_replace('index.php','',$sn)) != '')
        setcookie('status' . str_replace('index.php','',$sn), '', 0);
	if (gc('status') != '')
        setcookie('status', '', 0, CMSIMPLE_ROOT);
    if (gc('passwd') != '')
        setcookie('passwd', '', 0);
    if (gc('mode') == 'edit')
        setcookie('mode', '', 0);
}
?>