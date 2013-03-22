<?php

/**
 *	This file is part of LEPTON Core, released under the GNU GPL
 *	Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 *	NOTICE:LEPTON CMS Package has several different licenses.
 *	Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 *	@author			LEPTON Project
 *	@copyright		2012-2013 LEPTON Project
 *	@link			http://www.LEPTON-cms.org
 *	@license		http://www.gnu.org/licenses/gpl.html
 *	@license_terms	please see LICENSE and COPYING files in your package
 *
 *	@notice			To make this file work you will have to uncomment the line at the bottom inside the
 *					"initialize.php" in the same directory of this file.
 *
 */

/**
 *	Chapter 1.0
 *	Setting the error_level to -1, to display all errors and warnings.
 *
 *	@See:	http://php.net/manual/en/function.error-reporting.php
 *
 */
error_reporting( -1 );
ini_set('display_errors', 1);

/**
 *	Chapter 2.0
 *	Place your additional modifications and settings here.
 *	E.g. if you are using xDebug:
 *
 *	ini_set('xdebug.collect_params', 1);
 *	ini_set('xdebug.profiler_enable_trigger', 1);
 *	ini_set('xdebug.profiler_output_dir', '/tmp/aldus/');
 *
 *	@see: http://xdebug.org/docs/all_settings
 *
 */

/**
 *	Chapter 3.0
 *	Overwrite Lepton-CMS settings here.
 *	E.g.
 *
 *	$database->prompt_on_error( true );
 *
 */
 ?>