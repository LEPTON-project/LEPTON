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

class code2 extends LEPTON_abstract
{
    /**
     *  Is CodeMirror installed?
     *  @type   boolean
     */
    public $codemirrorSupported = false;
    
    /**
     *  The own singelton instance.
     *  @type   instance
     */
    static $instance;
    
    /**
     *  Called by instance. All we have to do during the initialisation of this class.
     * 
     */
    public function initialize()
    {
        self::$instance->codemirrorSupported = class_exists("lib_codemirror", true);
    }

}