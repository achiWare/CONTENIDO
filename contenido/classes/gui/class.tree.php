<?php

/**
 * This file contains the tree GUI class.
 *
 * @package    Core
 * @subpackage GUI
 * @author     Mischa Holz
 * @copyright  four for business AG <www.4fb.de>
 * @license    https://www.contenido.org/license/LIZENZ.txt
 * @link       https://www.4fb.de
 * @link       https://www.contenido.org
 */

defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

/**
 * cGuiTree is a visual representation of a cTree. It supports folding,
 * optional gridline marks and item icons.
 *
 * @package    Core
 * @subpackage GUI
 */
class cGuiTree extends cTree {

    /**
     *
     * @var string
     */
    const TREEVIEW_GRIDLINE_SOLID = 'solid';

    /**
     *
     * @var string
     */
    const TREEVIEW_GRIDLINE_DASHED = 'dashed';

    /**
     *
     * @var string
     */
    const TREEVIEW_GRIDLINE_DOTTED = 'dotted';

    /**
     *
     * @var string
     */
    const TREEVIEW_GRIDLINE_NONE = 'none';

    /**
     *
     * @var string
     * @deprecated [2015-05-21]
     *    This constant is no longer supported (no replacement)
     */
    const TREEVIEW_BACKGROUND_NONE = 'none';

    /**
     *
     * @var string
     * @deprecated [2015-05-21]
     *         This constant is no longer supported (no replacement)
     */
    const TREEVIEW_BACKGROUND_SHADED = 'shaded';

    /**
     *
     * @var string
     * @deprecated [2015-05-21]
     *         This constant is no longer supported (no replacement)
     */
    const TREEVIEW_MOUSEOVER_NONE = 'none';

    /**
     *
     * @var string
     * @deprecated [2015-05-21]
     *         This constant is no longer supported (no replacement)
     */
    const TREEVIEW_MOUSEOVER_MARK = 'mark';

    /**
     * @var cApiUser
     */
    protected $_user;

    /**
     * @var string
     */
    protected $_uuid;

    /**
     * @var string
     */
    protected $_gridlineMode;

    /**
     * @var string
     */
    private $_baseLink;

    /**
     * Constructor to create an instance of this class.
     *
     * @param string $uuid
     * @param false|string $treename [optional]
     */
    public function __construct($uuid, $treename = false) {
        global $cfg, $auth;

        parent::__construct();

        $this->_uuid = $uuid;
        //$this->setGridlineMode(self::TREEVIEW_GRIDLINE_DOTTED);

        if ($treename != false) {
            $this->setTreeName($treename);
        }

        $this->_user = new cApiUser($auth->auth["uid"]);
    }

    /**
     * @throws cDbException
     * @throws cException
     * @throws cInvalidArgumentException
     */
    public function processParameters() {
        if (($items = $this->_user->getUserProperty("expandstate", $this->_uuid)) !== false) {
            $list = unserialize($items);

            foreach ($list as $litem) {
                $this->setCollapsed($litem);
            }
        }

        if (!empty($this->_name)) {
            $treename = $this->_name . "_";
        }

        if (array_key_exists($treename . "collapse", $_GET)) {
            $this->setCollapsed($_GET[$treename . "collapse"]);
        }

        if (array_key_exists($treename . "expand", $_GET)) {
            $this->setExpanded($_GET[$treename . "expand"]);
        }

        $xlist = []; // Define variable before using it by reference...
        $this->getCollapsedList($xlist);
        $slist = serialize($xlist);

        $this->_user->setUserProperty("expandstate", $this->_uuid, $slist);
    }

    /**
     * @param int $mode
     *         Sets the gridline mode to one of the following values:
     *         - cGuiTree::TREEVIEW_GRIDLINE_SOLID
     *         - cGuiTree::TREEVIEW_GRIDLINE_DASHED
     *         - cGuiTree::TREEVIEW_GRIDLINE_DOTTED
     *         - cGuiTree::TREEVIEW_GRIDLINE_NONE
     */
    public function setGridlineMode($mode) {
        $this->_gridlineMode = $mode;
    }

    /**
     *
     * @param string|mixed $mode
     * @deprecated [2015-05-21]
     *         This method is no longer supported (no replacement)
     */
    public function setBackgroundMode($mode) {
        cDeprecated('This method is deprecated and is not needed any longer');
        $this->_backgroundMode = $mode;
    }

    /**
     *
     * @param string|mixed $mode
     * @deprecated [2015-05-21]
     *         This method is no longer supported (no replacement)
     */
    public function setMouseoverMode($mode) {
        cDeprecated('This method is deprecated and is not needed any longer');
        $this->_mouseoverMode = $mode;
    }

    /**
     *
     * @param string|mixed $colors
     * @deprecated [2015-05-21]
     *         This method is no longer supported (no replacement)
     */
    public function setBackgroundColors($colors) {
        cDeprecated('This method is deprecated and is not needed any longer');
        $this->_backgroundColors = $colors;
    }

    /**
     *
     * @param bool $with_root [optional]
     * @return string
     */
    public function render($with_root = true) {

        /* @var $objects cTreeItem[] */
        $objects = $this->flatTraverse(0);

        if ($with_root == false) {
            unset($objects[0]);
        }

        $img = new cHTMLImage;
        $r_table = new cHTMLTable;
        $r_row = new cHTMLTableRow;
        $r_leftcell = new cHTMLTableData;
        $r_rightcell = new cHTMLTableData;
        $r_actioncell = new cHTMLTableData;

        $img_spacer = new cHTMLImage;
        $img_spacer->updateAttributes(['width' => '16', 'height' => '20']);
        $img_spacer->setAlt("");
        $img_spacer->setSrc("images/spacer.gif");
        $img_spacer->advanceID();

        $r_table->setCellPadding(0);
        $r_table->setCellSpacing(0);
        $r_table->setWidth("100%");
        $r_rightcell->appendStyleDefinition("padding-left", "3px");
        $r_rightcell->setVerticalAlignment("middle");
        $r_leftcell->setVerticalAlignment("middle");
        $r_leftcell->updateAttributes(["nowrap" => "nowrap"]);
        $r_rightcell->updateAttributes(["nowrap" => "nowrap"]);
        $r_actioncell->updateAttributes(["nowrap" => "nowrap"]);
        $r_leftcell->setWidth("1%");
        $r_rightcell->setWidth("100%");
        $r_actioncell->setAlignment("right");
        $r_actioncell->setWidth("1%");

        if (!is_object($this->_baseLink)) {
            $this->_baseLink = new cHTMLLink();
        }

        $out = $result = '';

        $lastitem = [];
        foreach ($objects as $key => $object) {
            $img->setAlt("");
            $r_table->advanceID();
            $r_rightcell->advanceID();
            $r_leftcell->advanceID();
            $r_row->advanceID();
            $r_actioncell->advanceID();

            for ($level = 1; $level < $object->_level + 1; $level++) {
                if ($object->_level == $level) {
                    if ($object->_next === false) {
                        if (count($object->_subitems) > 0) {
                            $link = $this->_setExpandCollapseLink($this->_baseLink, $object);
                            $link->advanceID();
                            $img->setSrc($this->_getExpandCollapseIcon($object));
                            $img->advanceID();
                            $link->setContent($img);
                            $out .= $link->render();
                        } else {
                            if ($level == 1 && $with_root == false) {
                                $out .= $img_spacer->render();
                            } else {
                                $img->setSrc($this->_buildImagePath("grid_linedownrightend.gif"));
                                $img->advanceID();
                                $out .= $img->render();
                            }
                        }
                        $lastitem[$level] = true;
                    } else {
                        if (count($object->_subitems) > 0) {
                            $link = $this->_setExpandCollapseLink($this->_baseLink, $object);
                            $link->advanceID();
                            $img->setSrc($this->_getExpandCollapseIcon($object));
                            $img->advanceID();
                            $link->setContent($img);
                            $out .= $link->render();
                        } else {
                            if ($level == 1 && $with_root == false) {
                                $out .= $img_spacer->render();
                            } else {
                                $img->setSrc($this->_buildImagePath("grid_linedownright.gif"));
                                $out .= $img->render();
                            }
                        }

                        $lastitem[$level] = false;
                    }
                } else {
                    if ($lastitem[$level] == true) {
                        $out .= $img_spacer->render();
                    } else {
                        if ($level == 1 && $with_root == false) {
                            $out .= $img_spacer->render();
                        } else {
                            $img->setSrc($this->_buildImagePath("/grid_linedown.gif"));
                            $img->advanceID();
                            $out .= $img->render();
                        }
                    }
                }
            }

            // Fetch Render icon from the meta object
            if (is_object($object->payload)) {
                // Fetch payload object
                $meta = $object->payload->getMetaObject();

                if (is_object($meta)) {
                    $icon = $meta->getIcon();
                    $actions = $meta->getActions();

                    $r_actioncell->setContent($actions);

                    $img->setAlt($meta->getDescription());
                    $img->advanceID();

                    // Check if we've got an edit link
                    if (count($meta->_editAction) > 0) {
                        $meta->defineActions();

                        $edit = $meta->getAction($meta->_editAction);

                        $edit->setIcon($icon);

                        $renderedIcon = $edit->render();

                        $edit->_link->setContent($object->getName());
                        $edit->_link->advanceID();
                        $renderedName = $edit->_link->render();
                    } else {
                        $img->setSrc($icon);
                        $renderedIcon = $img->render();
                        $renderedName = $object->getName();
                    }
                }
            } else {
                if (isset($object->_attributes["icon"])) {
                    $img->setSrc($object->_attributes["icon"]);
                    $renderedIcon = $img->render();
                    $renderedName = $object->getName();
                } else {
                    // Fetch tree icon
                    if ($object->getId() == 0) {
                        $icon = $object->getTreeIcon();
                        $img->setSrc($icon);
                        $renderedIcon = $img->render();
                        $renderedName = $object->getName();
                    } else {
                        $icon = $object->getTreeIcon();
                        $img->setSrc($icon);
                        $renderedIcon = $img->render();
                        $renderedName = $object->getName();
                    }
                }
            }

            $img->setSrc($icon);
            $r_leftcell->setContent($out . $renderedIcon);
            $r_rightcell->setContent($renderedName);

            $r_row->setContent([$r_leftcell, $r_rightcell, $r_actioncell]);

            $r_table->setContent($r_row);

            $result .= $r_table->render();

            unset($out);
        }

        return '<table cellspacing="0" cellpadding="0" width="100%" border="0"><tr><td>' . $result . '</td></tr></table>';
    }

    /**
     *
     * @param cTreeItem $object
     * @return string
     */
    public function _getExpandCollapseIcon($object) {

        $img = $object->getCollapsed() ? "grid_expand.gif" : "grid_collapse.gif";

        return $this->_buildImagePath($img);
    }

    /**
     * Sets collapsed state.
     *
     * @param cHTMLLink  $link
     * @param cTreeItem  $object
     * @return cHTMLLink
     */
    public function _setExpandCollapseLink($link, $object) {
        if (!empty($this->_name)) {
            $treename = $this->_name . "_";
        }

        $link->unsetCustom($treename . "expand");
        $link->unsetCustom($treename . "collapse");

        if ($object->getCollapsed() == true) {
            $link->setCustom($treename . "expand", $object->getId());
        } else {
            $link->setCustom($treename . "collapse", $object->getId());
        }

        return $link;
    }

    /**
     *
     * @param string $image
     * @return string
     */
    public function _buildImagePath($image) {
        return "./images/" . $this->_gridlineMode . "/" . $image;
    }

    /**
     *
     * @param string $link
     */
    public function setBaseLink($link) {
        $this->_baseLink = $link;
    }

}
