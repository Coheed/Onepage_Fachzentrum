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

array_insert($GLOBALS['TL_DCA']['tl_user']['list']['operations'], 5, array(
    'favoriten' => array
    (
        'label'               => &$GLOBALS['TL_LANG']['tl_user']['favoriten'],
        'href'                => 'table=tl_favoriten',
        'icon'                => 'redirect.gif'
    )
));



