<?php

/**
 * This file contains the cHTMLLink class.
 *
 * @package    Core
 * @subpackage GUI_HTML
 * @author     Simon Sprankel
 * @copyright  four for business AG <www.4fb.de>
 * @license    https://www.contenido.org/license/LIZENZ.txt
 * @link       https://www.4fb.de
 * @link       https://www.contenido.org
 */

defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

/**
 * cHTMLLink class represents a link.
 *
 * @package    Core
 * @subpackage GUI_HTML
 */
class cHTMLLink extends cHTMLContentElement {
    /**
     * Stores the link location
     * @var string
     */
    protected $_link;

    /**
     * Stores the anchor
     * @var string
     */
    protected $_anchor;

    /**
     * Stores the custom entries
     * @var array
     */
    protected $_custom;

    /**
     * @var string
     */
    protected $_image;

    /**
     * @var string
     */
    protected $_targetarea;

    /**
     * @var string
     */
    protected $_targetframe;

    /**
     * @var string
     */
    protected $_targetaction;

    /**
     * @var string
     */
    protected $_type;

    /**
     * @var string
     */
    protected $_targetarea2;

    /**
     * @var string
     */
    protected $_targetaction2;

    /**
     * @var string
     */
    protected $_targetframe2;

    /**
     * Constructor to create an instance of this class.
     *
     * Creates an HTML link.
     *
     * @param string $href [optional]
     *         String with the location to link to
     * @param mixed $content [optional]
     *         String or object with the contents
     * @param string $class [optional]
     *         the class of this element
     * @param string $id [optional]
     *         the ID of this element
     */
    public function __construct($href = '', $content = '', $class = '', $id = '') {
        parent::__construct($content, $class, $id);

        $this->setLink($href);
        $this->_tag = 'a';
        $this->_image = '';

        // Check for backend
        $sess = cRegistry::getSession();
        if (is_object($sess) && get_class($sess) == 'cSession') {
            $this->enableAutomaticParameterAppend();
        }
    }

    /**
     *
     * @return cHTML
     *         $this for chaining
     */
    public function enableAutomaticParameterAppend() {
        return $this->setEvent('click', 'var doit = true; try { var i = get_registered_parameters() } catch (e) { doit = false; }; if (doit == true) { this.href += i; }');
    }

    /**
     *
     * @return cHTML
     *         $this for chaining
     */
    public function disableAutomaticParameterAppend() {
        return $this->unsetEvent('click');
    }

    /**
     * Sets the link to a specific location
     *
     * @param string $href
     *         String with the location to link to
     * @return cHTMLLink
     *         $this for chaining
     */
    public function setLink($href) {
        $this->_link = $href;
        $this->_type = 'link';

        if (cString::findFirstPos($href, 'javascript:') !== false) {
            $this->disableAutomaticParameterAppend();
        }

        return $this;
    }

    /**
     * Sets the target frame
     *
     * @param string $target
     *         Target frame identifier
     * @return cHTMLLink
     *         $this for chaining
     */
    public function setTargetFrame($target) {
        return $this->updateAttribute('target', $target);
    }

    /**
     * Sets a CONTENIDO link (area, frame, action)
     *
     * @param string $targetarea
     *         Target backend area
     * @param string $targetframe
     *         Target frame (1-4)
     * @param string $targetaction [optional]
     *         Target action
     * @return cHTMLLink
     *         $this for chaining
     */
    public function setCLink($targetarea, $targetframe, $targetaction = '') {
        $this->_targetarea = $targetarea;
        $this->_targetframe = $targetframe;
        $this->_targetaction = $targetaction;
        $this->_type = 'clink';

        return $this;
    }

    /**
     * Sets a multilink
     *
     * @param string $righttoparea
     *         Area (right top)
     * @param string $righttopaction
     *         Action (right top)
     * @param string $rightbottomarea
     *         Area (right bottom)
     * @param string $rightbottomaction
     *         Action (right bottom)
     * @return cHTMLLink
     *         $this for chaining
     */
    public function setMultiLink($righttoparea, $righttopaction, $rightbottomarea, $rightbottomaction) {
        $this->_targetarea = $righttoparea;
        $this->_targetframe = 3;
        $this->_targetaction = $righttopaction;
        $this->_targetarea2 = $rightbottomarea;
        $this->_targetframe2 = 4;
        $this->_targetaction2 = $rightbottomaction;
        $this->_type = 'multilink';

        return $this;
    }

    /**
     * Sets a custom attribute to be appended to the link
     *
     * @param string $key
     *         Parameter name
     * @param string $value
     *         Parameter value
     * @return cHTMLLink
     *         $this for chaining
     */
    public function setCustom($key, $value) {
        $this->_custom[$key] = $value;

        return $this;
    }

    /**
     *
     * @param string $src
     * @return cHTMLLink
     *         $this for chaining
     */
    public function setImage($src) {
        $this->_image = $src;

        return $this;
    }

    /**
     * Unsets a previous set custom attribute
     *
     * @param string $key
     *         Parameter name
     * @return cHTMLLink
     *         $this for chaining
     */
    public function unsetCustom($key) {
        if (isset($this->_custom[$key])) {
            unset($this->_custom[$key]);
        }

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getHref() {
        global $sess;

        $custom = '';
        if (is_array($this->_custom)) {
            foreach ($this->_custom as $key => $value) {
                $custom .= "&$key=$value";
            }
        }

        if ($this->_anchor) {
            $anchor = '#' . $this->_anchor;
        } else {
            $anchor = '';
        }

        switch ($this->_type) {
            case 'link':
                $custom = '';
                if (is_array($this->_custom)) {
                    foreach ($this->_custom as $key => $value) {
                        if ($custom == '') {
                            $custom .= "?$key=$value";
                        } else {
                            $custom .= "&$key=$value";
                        }
                    }
                }

                return $this->_link . $custom . $anchor;
            case 'clink':
                $this->disableAutomaticParameterAppend();
                return 'main.php?area=' . $this->_targetarea . '&frame=' . $this->_targetframe . '&action=' . $this->_targetaction . $custom . '&contenido=' . $sess->id . $anchor;
            case 'multilink':
                $this->disableAutomaticParameterAppend();
                $tmp_mstr = 'javascript:Con.multiLink(\'%s\',\'%s\',\'%s\',\'%s\');';
                $mstr = sprintf($tmp_mstr, 'right_top', $sess->url('main.php?area=' . $this->_targetarea . '&frame=' . $this->_targetframe . '&action=' . $this->_targetaction . $custom), 'right_bottom', $sess->url('main.php?area=' . $this->_targetarea2 . '&frame=' . $this->_targetframe2 . '&action=' . $this->_targetaction2 . $custom));
                return $mstr;
            default:
                return '';
        }
    }

    /**
     * Sets an anchor
     * Only works for the link types Link and cLink.
     *
     * @param string $content
     *         Anchor name
     * @return cHTMLLink
     *         $this for chaining
     */
    public function setAnchor($anchor) {
        $this->_anchor = $anchor;

        return $this;
    }

    /**
     * Renders the link
     *
     * @return string
     *         Rendered HTML
     */
    public function toHtml() {
        $this->updateAttribute('href', $this->getHref());

        if ($this->_image != '') {
            $image = new cHTMLImage($this->_image);
            $this->setContent($image);
        }

        return parent::toHtml();
    }

}
