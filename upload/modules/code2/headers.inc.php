<?php

/**
 *  @module         code2
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @copyright      2004-2018 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 */

$mod_headers = array(
    'backend' => array(
        'css'   => array(),
        'js'    => array()
    )
);

if( true === code2::getInstance()->codemirrorSupported )
{
    $oCodeMirror = lib_codemirror::getInstance();
    
    $mod_headers['backend']['css'][] = array(
        "media" => "all",
        "file"  => "/modules/lib_codemirror/css/backend.css"
    );
     
    $oCodeMirror->useFavorites = true;
    $aCodeMirrorFiles = $oCodeMirror->getBaseFiles();

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