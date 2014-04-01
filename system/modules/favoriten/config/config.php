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
$GLOBALS['BE_MOD']['accounts']['user']['tables'][]    = 'tl_favoriten';
$GLOBALS['BE_MOD']['accounts']['group']['tables'][]   = 'tl_favoriten';

$GLOBALS['TL_HOOKS']['getUserNavigation'][]           = array('Favoriten', 'addFavorites');


if (TL_MODE == 'BE') {
    $GLOBALS['TL_CSS'][] = "/system/modules/favoriten/assets/style.css|screen";
}