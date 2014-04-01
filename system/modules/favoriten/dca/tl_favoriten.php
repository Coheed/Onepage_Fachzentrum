<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2013 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    favoriten
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_favoriten'] = array(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'closed'                      => false,
        'ptable'                      => '',
        'dynamicPtable'               => true,

        'sql' => array(
            'keys' => array('id' => 'primary')
        ),
        'onsubmit_callback' => array(

        ),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('sorting'),
            'flag'                    => 1,
            'panelLayout'             => 'search, limit',
            'headerFields'            => array('name', 'username'),
            'child_record_callback'   => array('tl_favoriten', 'favEntry')
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_favoriten']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_favoriten']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
        )

    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},favLabel,gotoUrl,icon;',
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'label'                   => array('ID'),
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'label'                   => array('TSTAMP'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
        ),
        'pid' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'ptable' => array
        (
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'sorting' => array
        (
            'label'                   => array('SORTING'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
        ),
        'icon' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_favoriten']['icon'],
            'default'                 => 'system/themes/default/images/featured.gif',
            'inputType'               => 'select',
            'options_callback'        => array('tl_favoriten', 'getIcons'),
            'sql'                     => "varchar(255) NOT NULL default ''",
            'eval'                    => array('chosen'=>true, 'includeBlankOption'=>true),
        ),

        'favLabel' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_favoriten']['favLabel'],
            'inputType'               => 'text',
            'filter'                  => true,
            'search'                  => true,
            'sql'                     => "varchar(255) NOT NULL default ''",
            'eval'                    => array('mandatory'=>true, 'tl_class' => 'w50'),
        ),
        'gotoUrl' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_favoriten']['gotoUrl'],
            'inputType'               => 'text',
            'search'                  => true,
            'sql'                     => "varchar(255) NOT NULL default ''",
            'eval'                    => array('mandatory'=>true, 'tl_class' => 'w50'),
        ),
        'class' => array(
            'label'                   => $GLOBALS['TL_LANG']['tl_favoriten']['class'],
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),

    ) //fields

);


if (Input::get('do') == 'user')  $GLOBALS['TL_DCA']['tl_favoriten']['config']['ptable'] = 'tl_user';
if (Input::get('do') == 'group') $GLOBALS['TL_DCA']['tl_favoriten']['config']['ptable'] = 'tl_user_group';



class tl_favoriten extends Backend {
    private $icons      = false;
    private $usernames  = array();
    private $groupnames = array();

    public function __construct() {
        parent::__construct();
    }

    public function favEntry($arrRow) {

        return sprintf(
            '<div class="tl_favoriten">
                <span class="icon"><img src="%s" /></span>
                <span class="label">%s</span>
                <span class="url"><a href="%s">%s</a></span>
            </div>',
            Favoriten::favIcon($arrRow['icon']),
            $arrRow['favLabel'],
            \Environment::get('script') . '?' . $arrRow['gotoUrl'] . '&amp;ref=' . TL_REFERER_ID, $arrRow['gotoUrl']
        );
    }

    public function getIcons() {
        if ($this->icons === false) {
            $this->icons = Favoriten::getIcons();
        }
        return $this->icons;
    }

}