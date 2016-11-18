<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		search_highlight
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
} //defined( 'LEPTON_PATH' )
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	} //( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) )
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	} //file_exists( $root . '/framework/class.secure.php' )
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

function search_highlight($foo='', $arr_string=array()) {
        require_once(LEPTON_PATH .'/framework/summary.utf8.php');
        static $string_ul_umlaut = FALSE;
        static $string_ul_regex = FALSE;
        if($string_ul_umlaut === FALSE || $string_ul_regex === FALSE) {
            require(LEPTON_PATH.'/modules/lib_search/search.convert.php');
        }
        $foo = entities_to_umlauts($foo, 'UTF-8');
        array_walk($arr_string, create_function('&$v,$k','$v = preg_quote($v, \'~\');'));
        $search_string = implode("|", $arr_string);
        $string = str_replace($string_ul_umlaut, $string_ul_regex, $search_string);
        // the highlighting
        // match $string, but not inside <style>...</style>, <script>...</script>, <!--...--> or HTML-Tags
        // Also droplet tags are now excluded from highlighting.
        // split $string into pieces - "cut away" styles, scripts, comments, HTML-tags and eMail-addresses
        // we have to cut <pre> and <code> as well.
        // for HTML-Tags use <(?:[^<]|<.*>)*> which will match strings like <input ... value="<b>value</b>" >
        $matches = preg_split("~(\[\[.*\]\]|<style.*</style>|<script.*</script>|<pre.*</pre>|<code.*</code>|<!--.*-->|<(?:[^<]|<.*>)*>|\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,8}\b)~iUs",$foo,-1,(PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY));
        if(is_array($matches) && $matches != array()) {
            $foo = "";
         foreach($matches as $match) {
                if($match{0}!="<" && !preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,8}$/i', $match) && !preg_match('~\[\[.*\]\]~', $match)) {
                    $match = str_replace(array('&lt;', '&gt;', '&amp;', '&quot;', '&#039;', '&nbsp;'), array('<', '>', '&', '"', '\'', "\xC2\xA0"), $match);
                    $match = preg_replace('~('.$string.')~ui', '_span class=_highlight__$1_/span_',$match);
                    $match = str_replace(array('&', '<', '>', '"', '\'', "\xC2\xA0"), array('&amp;', '&lt;', '&gt;', '&quot;', '&#039;', '&nbsp;'), $match);
                   
                  $match = str_replace(array('_span class=_highlight__', '_/span_'), array('<span class="highlight">', '</span>'), $match);
                 
               
                }
                $foo .= $match;
            }
        }

        if(DEFAULT_CHARSET != 'utf-8') {
            $foo = umlauts_to_entities($foo, 'UTF-8');
        }
        return $foo;
    }

?>