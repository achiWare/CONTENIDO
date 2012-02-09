<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * Class for creating form pages
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package    CONTENIDO Backend Classes
 * @version    1.0.3
 * @author     Jan Lengowski
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since CONTENIDO release <= 4.6
 *
 * {@internal
 *   created unknown
 *   modified 2008-06-30, Dominik Ziegler, add security fix
 *   modified 2009-10-23, Murat Purc, removed deprecated function (PHP 5.3 ready)
 *   modified 2011-11-18, Murat Purc, normalize E-Mail validation [#CON-448] an ported to PHP 5
 *
 *   $Id$:
 * }}
 *
 */

if (!defined('CON_FRAMEWORK')) {
    die('Illegal call');
}

/**
 * Form generator class
 * @package    CONTENIDO Backend Classes
 */
class Form
{

    /**
     * counter
     * @var int
     */
    public $counter = 0;

    /**
     * passed
     * @var bool
     */
    public $passed = false;

    /**
     * isSend
     * @var bool
     */
    public $isSend = false;

    /**
     * debug
     * @var bool
     */
    public $debug = false;

    /**
     * fields
     * @var array
     */
    public $fields = array();

    /**
     * defaults
     * @var array
     */
    public $default = array();

    /**
     * action
     * @var string
     */
    public $action = '';

    /**
     * method
     * @var string
     */
    public $method = 'post';

    /**
     * enctype
     * @var string
     */
    public $enctype = '';

    /**
     * target
     * @var string
     */
    public $target = '_self';

    /**
     * Constructor Function
     */
    public function __construct()
    {
        // empty
    }

    /** @deprecated  [2011-11-18] Old constructor function for downwards compatibility */
    public function Form()
    {
        cDeprecated("Use __construct() instead");
        $this->__construct();
    }

    /**
     * Add a form element
     * @param  string  $name
     */
    public function Add($name)
    {
        $name = Contenido_Security::escapeDB($name, null);
        $this->counter ++;
        $this->fields[$this->counter]['name'] = $name;
    }

    /**
     * Add a default value
     * @param  string  $name
     * @param  string  $value
     */
    public function SetDefault($name, $value)
    {
        $name = Contenido_Security::escapeDB($name, null);
        $value = Contenido_Security::escapeDB($value, null);
        $this->default[$name] = $value;
    }

    /**
     * Define properties of the form or the elements
     * @param  string  $which  Which property to define
     * @param  string  $value  Values of the property
     */
    public function Define($which, $value)
    {
        $which = Contenido_Security::escapeDB($which, null);
        $value = Contenido_Security::escapeDB($value, null);
        if (0 == $this->counter) {
            $this->$which = $value;
        } else {
            $this->fields[$this->counter][$which] = $value;
        }
    }

    /**
     * Checks if the form passed
     * @return bool TRUE: Form passed without errors, FALSE: Errors
     */
    public function Passed()
    {
        return $this->passed;
    }

    /**
     * Generates the form
     * @param  string  $template Path/Filename of the template to use
     */
    public function Generate($template)
    {
        $template = Contenido_Security::escapeDB($template);

        // get form values
        $this->GetFormValues();

        // if form was submitted before validate the fields
        if ($this->isSend == true) {
            $this->checkFormValues();
        }

        // at least one entry is invalid generate the form
        if (!$this->passed) {

            // check if the template is a file or a string
            if (!@file_exists($template)) {
                // template is a string
                $tmp_template['complete'] = explode("\n", $template);
            } else {
                // template is a file
                $tmp_template['complete'] = file($template);
            }

            // line numbers for the dynamic blocks
            $tmp_template['line_nr']['start']   = 0;
            $tmp_template['line_nr']['end']     = 0;
            $tmp_template['line_nr']['max']     = count($tmp_template['complete']);

            // parts of the template
            $tmp_template['start']  = '';
            $tmp_template['block']  = '';
            $tmp_template['end']    = '';

            // search the template for dynamic blocks
            foreach ($tmp_template['complete'] as $line => $content) {
                // search for start block tag
                if (strstr($content, '<!-- BEGIN:BLOCK -->')) {
                    $tmp_template['line_nr']['start'] = $line + 1;
                }

                // search for end block tag
                if (strstr($content, '<!-- END:BLOCK -->')) {
                    $tmp_template['line_nr']['end'] = $line - 1;
                }
            }

            // extract start part
            for ($i=0; $i<$tmp_template['line_nr']['start']; $i++) {
                $tmp_template['start'] .= $tmp_template['complete'][$i];
            }

            // extract block
            for ($i=$tmp_template['line_nr']['start']; $i<=$tmp_template['line_nr']['end']; $i++) {
                $tmp_template['block'] .= $tmp_template['complete'][$i];
            }

            // extract end part
            for ($i=$tmp_template['line_nr']['end']+1; $i<=$tmp_template['line_nr']['max']; $i++) {
                $tmp_template['end'] .= $tmp_template['complete'][$i];
            }

            $tmp_needles[] = '{ACTION}';
            $tmp_needles[] = '{METHOD}';
            $tmp_needles[] = '{ENCTYPE}';
            $tmp_needles[] = '{TARGET}';

            $tmp_replacements[] = $this->action;
            $tmp_replacements[] = $this->method;
            $tmp_replacements[] = $this->enctype;
            $tmp_replacements[] = $this->target;

            unset($tmp_template['complete']);

            $tmp_template['complete'] .= str_replace($tmp_needles, $tmp_replacements, $tmp_template['start']);

            // generate blocks
            $fieldcount = count($this->fields);

            unset($tmp_needles);
            $tmp_needles[] = '{CAPTION}';
            $tmp_needles[] = '{FIELD}';
            $tmp_needles[] = '{BGCOLOR}';

            for ($i=1; $i<=$fieldcount; $i++) {

                // set default classerror style
                if (!isset($this->fields[$i]['classerror'])) {
                    $this->fields[$i]['classerror'] = $this->default['classerror'];
                }

                // set default classcaption style
                if (!isset($this->fields[$i]['classcaption'])) {
                    $this->fields[$i]['classcaption'] = $this->default['classcaption'];
                }

                // set default classinput style
                if (!isset($this->fields[$i]['classinput'])) {
                    $this->fields[$i]['classinput'] = $this->default['classinput'];
                }

                // unset replacement array
                unset($tmp_replacements);

                // set the correct caption class
                if ($this->isSend && $this->fields[$i]['passed'] == false) {
                    // error
                    $tmp_replacements[] = '<span class="'.$this->fields[$i]['classerror'].'">'.$this->fields[$i]['caption'].'</span>';
                } else {
                    // passed
                    $tmp_replacements[] = '<span class="'.$this->fields[$i]['classcaption'].'">'.$this->fields[$i]['caption'].'</span>';
                }

                // FormField instance
                $field = new FormField;

                // Get Code for one element
                $tmp_replacements[] = $field->GenerateCode($this->fields[$i]);

                // alternate between row background colors
                if ($this->default['lightcolor'] != '' && $this->default['darkcolor'] != '') {
                    $tmp_replacements[] = (is_int($i/2)) ? $this->default['lightcolor'] : $this->default['darkcolor'];
                } else {
                    $tmp_replacements[] = '';
                }

                // replace placeholders with replacements
                $tmp_template['complete'] .= str_replace($tmp_needles, $tmp_replacements, $tmp_template['block']);
            }

            // end part
            $tmp_template['complete'] .= $tmp_template['end'];

            // output
            echo $tmp_template['complete'];

            // debug info
            if ($this->debug) {
                echo '<pre>';
                print_r($this->fields);
                echo '</pre>';
            }

        } else {
            // there are no errors
            // and the form passed

            // do nothing
        }
    }

    /**
     * Extract the Form Data from the $_POST or $_GET, stores them in fields property
     */
    public function GetFormValues()
    {
        if (strtolower($this->method) == 'post') {
            // extract values from the $_POST global array
            foreach ($this->fields as $id => $element) {
                // check if value exists, extract it
                if (isset($_POST[$element['name']])) {
                    $this->fields[$id]['value'] = $_POST[$element['name']];
                    $this->isSend = true;
                } else {
                    $this->isSend = false;
                }
            }
        } elseif (strtolower($this->method) == 'get') {
            // extract values from the $_GET global array
            foreach ($this->fields as $id => $element) {
                // check if value exists, extract it
                if (isset($_POST[$element['name']])) {
                    $this->fields[$id]['value'] = $_GET[$element['name']];
                    $this->isSend = true;
                } else {
                    $this->isSend = false;
                }
            }
        }
    }

    /**
     * Runs check on send form values
     */
    public function CheckFormValues()
    {
        $tmp_passed = true;

        foreach ($this->fields as $id => $element) {

            $check = new FormCheck;

            switch (strtolower($element['checktype'])) {

                case 'none':
                    $this->fields[$id]['passed'] = true;
                    break;

                case 'simple':
                    if ('checkbox' == $element['type']) {
                        $this->fields[$id]['passed'] = (isset($element['value'])) ? true : false;
                    } else {
                        $this->fields[$id]['passed'] = ('select' == $element['type']) ? $check->isNotEmpty($element['value']) : $check->isNotNull($element['value']);
                    }
                    break;

                case 'numeric':
                    $this->fields[$id]['passed'] = $check->isNumeric($element['value']);
                    break;

                case 'alphabetic':
                    $this->fields[$id]['passed'] = $check->isAlphabetic($element['value']);
                    break;

                case 'email':
                    $this->fields[$id]['passed'] = $check->isEmail($element['value']);
                    break;

                case 'datefromto':
                    $tmp_check = array();
                    $tmp_check[] = $check->isNumeric($element['value']['from']['d']);
                    $tmp_check[] = $check->isNumeric($element['value']['from']['m']);
                    $tmp_check[] = $check->isNumeric($element['value']['from']['y']);
                    $tmp_check[] = $check->isNumeric($element['value']['to']['d']);
                    $tmp_check[] = $check->isNumeric($element['value']['to']['m']);
                    $tmp_check[] = $check->isNumeric($element['value']['to']['y']);
                    $this->fields[$id]['passed'] = (in_array(false, $tmp_check)) ? false : true;
                    unset($tmp_check);
                    break;

                case 'fromto':
                    $tmp_check = array();
                    $tmp_check[] = $check->isNumeric($element['value']['from']);
                    $tmp_check[] = $check->isNumeric($element['value']['to']);
                    $this->fields[$id]['passed'] = (in_array(false, $tmp_check)) ? false : true;
                    unset($tmp_check);
                    break;

                default:
                    if ('checkbox' == $element['type']) {
                        $this->fields[$id]['passed'] = (isset($element['value'])) ? true : false;
                    } else {
                        $this->fields[$id]['passed'] = ('select' == $element['type']) ? $check->isNotEmpty($element['value']) : $check->isNotNull($element['value']);
                    }
                    break;

            }

            if ($this->fields[$id]['passed'] == false) {
                $tmp_passed = false;
            }

        }

        $this->passed = $tmp_passed;
    }

}


/**
 * Class Formfield
 * Class for creating form elements
 * @package    CONTENIDO Backend Classes
 * @author     Jan Lengowski <Jan.Lengowski@4fb.de>
 * @version    1.0
 * @copyright  four for business 2002
 */
class FormField
{
    /**
     * Constructor Function
     */
    public function __construct()
    {
        // do nothing
    }

    /** @deprecated  [2011-11-18] Old constructor function for downwards compatibility */
    public function FormField()
    {
        cDeprecated("Use __construct() instead");
        $this->__construct();
    }

    /**
     * Created HTML Code depending on
     * the type of form field selected.
     * @argument $item array All necessary formfield data
     */
    public function GenerateCode($item)
    {
        if (!is_array($item)) {
            // no data
            exit ('Argument is not an array!');

        } else {
            // switch form type
            $tmp_ret_str = '';

            switch (strtolower($item['type'])) {

                case 'caption':
                    // Feld ist nur eine Beschriftung, HTML ist f�r das Feld erlaubt.
                    $tmp_ret_str = '<span class="'.$item['classcaption'].'">'.$item['value'].'</span>';
                    break;

                case 'hidden':
                    // Feld ist versteckt und dient nur zum �bermitteln von Daten.
                    $tmp_ret_str = '<input type="hidden" name="'.$item['name'].'" value="'.$item['value'].'">';
                    break;

                case 'text':
                    // Feld ist ein einzeiliges Text- Eingabefeld.
                    $tmp_ret_str = '<input type="'.$item['type'].'" name="'.$item['name'].'" value="'.$item['value'].'" class="'.$item['classinput'].'">';
                    break;

                case 'textarea':
                    // Feld ist ein mehrzeiliges Text-Eingabefeld.
                    $tmp_ret_str = '<textarea name="'.$item['name'].'" class="'.$item['classinput'].'">'.$item['value'].'</textarea>';
                    break;

                case 'select':
                    // Feld ist ein Auswahlfeld.
                    $tmp_ret_str = '<select name="'.$item['name'].'" class="'.$item['classinput'].'">';

                    if (!is_array($item['values'])) {
                        // no values
                        $tmp_ret_str .= '<option style="color:#FF0000">no values passed</option>';

                    } else {
                        // values array passed
                        foreach ($item['values'] as $key => $value) {
                            if ($item['value'] == $key) {
                                // selected
                                $tmp_ret_str .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';

                            } else {
                                // unselected
                                $tmp_ret_str .= '<option value="'.$key.'">'.$value.'</option>';
                            }
                        }
                    }

                    $tmp_ret_str .= '</select>';

                    break;

                case 'radiolist':
                    // Liste mit radio buttons.
                    if (!is_array($item['values'])) {
                        // no values
                        $tmp_ret_str .= '<span style="color:#FF0000">no values passed</span>';

                    } else {
                        $tmp_ret_str .= '<table cellspacing="0" cellpadding="2" border="0">';

                        $first = true;

                        foreach ($item['values'] as $caption => $value) {
                            $tmp_ret_str .=     '<tr>';

                            if ($item['value'] == $value) {
                                $tmp_ret_str .=     '<td class="'.$item['classcaption'].'">'.$caption.'</td><td><input type="radio" name="'.$item['name'].'" value="'.$value.'" checked="checked"></td>';
                            } else {
                                if ($first) {
                                    $tmp_ret_str .=     '<td class="'.$item['classcaption'].'">'.$caption.'</td><td><input type="radio" name="'.$item['name'].'" value="'.$value.'" checked="checked"></td>';
                                } else {
                                    $tmp_ret_str .=     '<td class="'.$item['classcaption'].'">'.$caption.'</td><td><input type="radio" name="'.$item['name'].'" value="'.$value.'"></td>';
                                }
                            }

                            $tmp_ret_str .=     '<tr>';
                            $first = false;
                        }

                        $tmp_ret_str .= '</table>';
                    }
                    break;

                case 'checkbox':
                    if (isset($item['value'])) {
                        $tmp_ret_str .= '<input type="checkbox" name="'.$item['name'].'" value="on" checked="checked">';

                    } else {
                        $tmp_ret_str .= '<input type="checkbox" name="'.$item['name'].'" value="on">';
                    }
                    break;

                case 'datefromto':
                    $tmp_ret_str .= '<table cellspacing="0" cellpadding="2" border="0">';

                    $tmp_ret_str .= '   <tr>';
                    $tmp_ret_str .= '       <td><span class="'.$item['classcaption'].'">'.$item['values'][0].'</span></td>';
                    $tmp_ret_str .= '       <td><input class="'.$item['classinput'].'" type="text" name="'.$item['name'].'[from][d]" value="'.$item['value']['from']['d'].'" size="2" maxlength="2"></td>';
                    $tmp_ret_str .= '       <td><input class="'.$item['classinput'].'" type="text" name="'.$item['name'].'[from][m]" value="'.$item['value']['from']['m'].'" size="2" maxlength="2"></td>';
                    $tmp_ret_str .= '       <td><input class="'.$item['classinput'].'" type="text" name="'.$item['name'].'[from][y]" value="'.$item['value']['from']['y'].'" size="4" maxlength="4"></td>';
                    $tmp_ret_str .= '   <tr>';

                    $tmp_ret_str .= '   <tr>';
                    $tmp_ret_str .= '       <td><span class="'.$item['classcaption'].'">'.$item['values'][1].'</span></td>';
                    $tmp_ret_str .= '       <td><input class="'.$item['classinput'].'" type="text" name="'.$item['name'].'[to][d]" value="'.$item['value']['to']['d'].'" size="2" maxlength="2"></td>';
                    $tmp_ret_str .= '       <td><input class="'.$item['classinput'].'" type="text" name="'.$item['name'].'[to][m]" value="'.$item['value']['to']['m'].'" size="2" maxlength="2"></td>';
                    $tmp_ret_str .= '       <td><input class="'.$item['classinput'].'" type="text" name="'.$item['name'].'[to][y]" value="'.$item['value']['to']['y'].'" size="4" maxlength="4"></td>';
                    $tmp_ret_str .= '   <tr>';

                    $tmp_ret_str .= '</table>';

                    break;

                // TimeJob hardcoded dummy
                case 'suche':
                    $tmp_ret_str .= '<input type="text" class="'.$item['classinput'].'" value="'.$item['value'].'">&nbsp;<a href="#" onclick="popUp(\''.$item['values'][0].'\')"><img src="images/button_suchen.gif" border="0"></a>';
                    $tmp_ret_str .= '&nbsp;&nbsp;<a href="#" onclick="popUp(\''.$item['values'][1].'\')"><img src="images/but_help.gif" border="0"></a>';
                    break;

                case 'fromto':
                    $tmp_ret_str .= '<input type="text" class="'.$item['classinput'].'" name="'.$item['name'].'[from]" value="'.$item['value']['from'].'">';
                    $tmp_ret_str .= '&nbsp;<span class="'.$item['classcaption'].'">bis</a>&nbsp;';
                    $tmp_ret_str .= '<input type="text" class="'.$item['classinput'].'" name="'.$item['name'].'[to]" value="'.$item['value']['to'].'">';

                    break;

            }

            return $tmp_ret_str;
        }

    }

}


/**
 * class FormCheck
 * Class for checking form values
 * @package    CONTENIDO Backend Classes
 * @author     Jan Lengowski <Jan.Lengowski@4fb.de>
 * @version    1.0
 * @copyright  four for business 2002
 */
class FormCheck
{

    /**
     * Constructor function
     */
    public function __construct()
    {
        // empty
    }

    /** @deprecated  [2011-11-18] Old constructor function for downwards compatibility */
    public function FormCheck()
    {
        cDeprecated("Use __construct() instead");
        $this->__construct();
    }

    /**
     * Checks if a value is NOT empty
     * @param  mixed  $value  Value to check
     * @return bool
     */
    public function isNotEmpty($value)
    {
        return ('' == $value || 0 == $value) ? false : true;
    }

    /**
     * Checks if a value is NOT null
     * @param  mixed  $value  Value to check
     * @return bool
     */
    public function isNotNull($value)
    {
        return ($value) ? true : false;
    }

    /**
     * Checks if a value is numeric
     * @param  mixed  $value  Value to check
     * @return bool
     */
    public function isNumeric($value)
    {
        if ('' != $value) {
            return (!preg_match('/[^0-9]/', $value)) ? true : false;
        } else {
            return false;
        }
    }

    /**
     * Checks if a value is alphabetic
     * @param  mixed  $value  Value to check
     * @return bool
     */
    public function isAlphabetic($value)
    {
        return (!preg_match('/[^a-zA-Z]/', $value)) ? true : false;
    }

    /**
     * Checks if a string is a valid email adress
     * @param  mixed  $value  Value to check
     * @return bool
     */
    public function isEmail($value)
    {
        $validator = Contenido_Validator_Factory::getInstance('email');
        return $validator->isValid($value);
    }

}

?>