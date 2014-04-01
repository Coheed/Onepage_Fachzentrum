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

/**
 * Class Favorites
 *
 * @copyright  Martin Kozianka 2013
 * @author     Martin Kozianka <http://kozianka.de>
 * @package    favoriten
 */
class Favoriten extends Backend {
    private static $fileTypes  = " AND (tl_files.extension = 'png' OR tl_files.extension = 'gif')";
    private $favModules        = array();

	public function __construct() {
		parent::__construct();
	}

	public function addFavorites($arrModules, $blnShowAll) {
        $this->import('BackendUser', 'User');

        if (!$this->Database->tableExists('tl_favoriten')) {
            unset($arrModules['favoriten']);
            return $arrModules;
        }

        // Parse user groups
        $groups   = array();
        foreach($this->User->groups as $g) {
            if (strlen($g)>0) $groups[] = $g;
        }
        $groupSQL = (count($groups) > 0) ? " OR (pid IN (".implode(',', $groups).") AND ptable = ?)" : "";

        // Favoriten holen
        $result = $this->Database->prepare("SELECT * FROM tl_favoriten
            WHERE (pid = ? AND ptable = ?)".$groupSQL." ORDER BY ptable ASC, sorting ASC")
            ->execute($this->User->id, 'tl_user', 'tl_user_group');

        while($result->next()) {
            $row = $result->row();
            $id  = standardize($row['favLabel']).'_fav'.$row['id'];
            $this->favModules[$id] = array(
                'title'     => $row['title'],
                'label'     => $row['favLabel'],
                'icon'      => ' style="background-image:url(\''.self::favIcon($row['icon']).'\')"',
                'class'     => 'navigation favorites '.standardize($row['favLabel']),
                'href'      => \Environment::get('script') . '?' . $row['gotoUrl'] . '&amp;ref=' . TL_REFERER_ID,
            );
        }

        // no favorites defined
        if (sizeOf($this->favModules) === 0) {
            unset($arrModules['favoriten']);
            return $arrModules;
        }

        $arr['favoriten'] = array(
            'icon'     => 'featured_.gif', // 'modMinus.gif',
            'title'    => specialchars($GLOBALS['TL_LANG']['MSC']['collapseNode']),
            'label'    => $GLOBALS['TL_LANG']['MOD']['favoriten'][0],
            'href'     => \Controller::addToUrl('mtg=favoriten'),
            'modules'  => $this->favModules
        );

		return $arr + $arrModules;
	}


    public static function getIcons() {

        $icons = array(
            'Default Theme' => array(
            'system/themes/default/images/admin.gif'       => 'admin',
            'system/themes/default/images/articles.gif'    => 'articles',
            'system/themes/default/images/css.gif'         => 'css',
            'system/themes/default/images/db.gif'          => 'db',
            'system/themes/default/images/error.gif'       => 'error',
            'system/themes/default/images/featured.gif'    => 'featured',
            'system/themes/default/images/filemanager.gif' => 'filemanager',
            'system/themes/default/images/form.gif'        => 'form',
            'system/themes/default/images/forwad.gif'      => 'forwad',
            'system/themes/default/images/group.gif'       => 'group',
            'system/themes/default/images/header.gif'      => 'header',
            'system/themes/default/images/layout.gif'      => 'layout',
            'system/themes/default/images/login.gif'       => 'login',
            'system/themes/default/images/logo.gif'        => 'logo',
            'system/themes/default/images/logout.gif'      => 'logout',
            'system/themes/default/images/member.gif'      => 'member',
            'system/themes/default/images/mgroup.gif'      => 'mgroup',
            'system/themes/default/images/modules.gif'     => 'modules',
            'system/themes/default/images/new.gif'         => 'new',
            'system/themes/default/images/news.gif'        => 'news',
            'system/themes/default/images/page.gif'        => 'page',
            'system/themes/default/images/pickcolor.gif'   => 'pickcolor',
            'system/themes/default/images/preview.gif'     => 'preview',
            'system/themes/default/images/redirect.gif'    => 'redirect',
            'system/themes/default/images/regular.gif'     => 'regular',
            'system/themes/default/images/root.gif'        => 'root',
            'system/themes/default/images/settings.gif'    => 'settings',
            'system/themes/default/images/show.gif'        => 'show',
            'system/themes/default/images/update.gif'      => 'update',
            'system/themes/default/images/user.gif'        => 'user',
        ));

        if (array_key_exists('favoriten_iconFolders', $GLOBALS['TL_CONFIG'])
            && strlen($GLOBALS['TL_CONFIG']['favoriten_iconFolders']) > 0) {

            $folderIds = deserialize($GLOBALS['TL_CONFIG']['favoriten_iconFolders']);
            foreach($folderIds as $fId) {
                $newIcons = self::iconsFromFolder($fId);
                if ($newIcons !== false) {
                    $icons =  $newIcons + $icons;
                }
            }
        }
        return $icons;
    }

    private static function iconsFromFolder($folderId) {
        $icons = array();
        $arrOptions = array(
            'order'  => 'name ASC',
            'column' => array("tl_files.pid = ?".self::$fileTypes)
        );

        $objFolder = \FilesModel::findByPk($folderId);
        $objChild  = \FilesModel::findBy('pid', $folderId, $arrOptions);

        // TODO Umstellung auf ids aus dem Dateisystem
        while ($objChild->next()) {
            if ($objChild->type === 'file') {
                $name                   = str_replace(array('.png','.gif'), array('',''), $objChild->name);
                $icons[$objChild->path] = $name;
            }
        }

        if (count($icons) === 0) {
            return false;
        }
        return array($objFolder->name => $icons);
    }


    public static function favIcon($imgPath) {
        if ($imgPath != '') {
            $dim     = getimagesize(TL_ROOT.'/'.$imgPath);
            $imgPath = ($dim[0]>18 || $dim[1]>18) ? Image::get($imgPath, 18, 18, 'box'): $imgPath;
        }
        else {
            $imgPath = 'system/themes/default/images/featured.gif';
        }
        return $imgPath;
    }
}


