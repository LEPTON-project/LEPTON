<?php

/**
 *  @module         code2
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @copyright      2004-2017 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 */


if(class_exists("lib_codemirror", true))
{
    $aCodeMirrorFiles = lib_codemirror::getInstance()->getBaseFiles();
    
    $mod_headers = array(
        'backend' => array(
            'css'   => array(),
            'js'    => array()
        )
    );

    foreach($aCodeMirrorFiles['css'] as $sCssPath)
    {
        $mod_headers['backend']['css'][] = array(
            'media'  => 'all',
            'file'  => $sCssPath
        );
    }

    foreach($aCodeMirrorFiles['js'] as $sJSPath)
    {
        $mod_headers['backend']['js'][] = $sJSPath;
    }

}