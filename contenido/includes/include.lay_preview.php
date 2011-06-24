<?php
/**
 * Project: 
 * Contenido Content Management System
 * 
 * Description: 
 * Contenido Layout Preview
 * 
 * Requirements: 
 * @con_php_req 5.0
 * 
 *
 * @package    Contenido Backend includes
 * @version    1.0.1
 * @author     unknown
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since contenido release <= 4.6
 * 
 * {@internal 
 *   created unknown
 *   modified 2008-06-27, Frederic Schneider, add security fix
 *   modified 2011-06-22, Rusmir Jusufovic, load layout from file 
 *
 *   $Id$:
 * }}
 * 
 */
 
if(!defined('CON_FRAMEWORK')) {
	die('Illegal call');
}


$layoutInFile = new LayoutInFile(Contenido_Security::toInteger($_GET['idlay']), "", $cfg, $lang);
if( ($code = $layoutInFile->getLayoutCode()) == false)
	echo i18n("No such layout");
	
	/* Insert base href */
	$base = '<base href="'.$cfgClient[$client]["path"]["htmlpath"].'">';
	$tags = $base;

	$code = str_replace("<head>", "<head>\n".$tags, $code);

	eval("?>\n".Contenido_Security::unescapeDB($code)."\n<?php\n");

?>