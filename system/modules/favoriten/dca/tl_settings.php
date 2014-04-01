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


$GLOBALS['TL_DCA']['tl_settings']['fields']['favoriten_iconFolders'] = array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['favoriten_iconFolders'],
        'exclude'                 => true,
        'inputType'               => 'fileTree',
        'eval'                    => array('multiple' => true, 'fieldType' => 'checkbox', 'files' => false, 'filesOnly' => false)
);

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{favoriten_legend}, favoriten_iconFolders';