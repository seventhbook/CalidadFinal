<?php
/* Copyright (C) 2004-2019 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2018      Alexandre Spangaro   <aspangaro@open-dsi.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * 	\defgroup   asset     Module Assets
 *  \brief      Asset module descriptor.
 *
 *  \file       htdocs/core/modules/modAsset.class.php
 *  \ingroup    asset
 *  \brief      Description and activation file for module Assets
 */
include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';


/**
 *  Description and activation class for module FixedAssets
 */
class modAsset extends DolibarrModules
{
	/**
	 * Constructor. Define names, constants, directories, boxes, permissions
	 *
	 * @param DoliDB $db Database handler
	 */
	public function __construct($db)
	{
		global $langs, $conf;

		$this->db = $db;

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->numero = 51000;	

		// Family can be 'crm','financial','hr','projects','products','ecm','technic','interface','other'
		// It is used to group modules by family in module setup page
		$this->family = "financial";
		// Module position in the family on 2 digits ('01', '10', '20', ...)
		$this->module_position = '70';
		// Gives the possibility to the module, to provide his own family info and position of this family (Overwrite $this->family and $this->module_position. Avoid this)

		// Module label (no space allowed), used if translation string 'ModuleAssetsName' not found (MyModue is name of module).
		$this->name = preg_replace('/^mod/i', '', get_class($this));
		// Module description, used if translation string 'ModuleAssetsDesc' not found (MyModue is name of module).
		$this->description = "Assets module";
		// Used only if file README.md and README-LL.md not found.
		$this->descriptionlong = "Assets module to manage assets module and depreciation charge on Dolibarr";

		// Possible values for version are: 'development', 'experimental', 'dolibarr', 'dolibarr_deprecated' or a version string like 'x.y.z'
		$this->version = 'development';
		// Key used in llx_const table to save module status enabled/disabled (where ASSETS is value of property name of module in uppercase)
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		// Name of image file used for this module.
		// If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
		// If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
		$this->picto = 'generic';

		// Defined all module parts (triggers, login, substitutions, menus, css, etc...)
		
		
		
		$this->module_parts = array();

		$this->dirs = array();

		// Config pages. Put here list of php page, stored into assets/admin directory, to use to setup module.
		$this->config_page_url = array("setup.php@asset");

		// Dependencies
		$this->hidden = false; // A condition to hide module
		$this->depends = array(); // List of module class names as string that must be enabled if this module is enabled
		$this->requiredby = array(); // List of module ids to disable if this one is disabled
		$this->conflictwith = array(); // List of module class names as string this module is in conflict with
		$this->langfiles = array("assets");
		$this->phpmin = array(5, 4); // Minimum version of PHP required by module
		$this->need_dolibarr_version = array(7, 0); // Minimum version of Dolibarr required by module
		$this->warnings_activation = array(); // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
		$this->warnings_activation_ext = array(); // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
		$this->const = array(
			1=>array('ASSET_MYCONSTANT', 'chaine', 'avalue', 'This is a constant to add', 1, 'allentities', 1)
		);


		if (!isset($conf->asset) || !isset($conf->asset->enabled))
		{
			$conf->asset = new stdClass();
			$conf->asset->enabled = 0;
		}


		// Array to add new pages in new tabs
		$this->tabs = array();
		// Example:
		
		
		
		
		// Where objecttype can be
		// 'categories_x'	  to add a tab in category view (replace 'x' by type of category (0=product, 1=supplier, 2=customer, 3=member)
		// 'contact'          to add a tab in contact view
		// 'contract'         to add a tab in contract view
		// 'group'            to add a tab in group view
		// 'intervention'     to add a tab in intervention view
		// 'invoice'          to add a tab in customer invoice view
		// 'invoice_supplier' to add a tab in supplier invoice view
		// 'member'           to add a tab in fundation member view
		// 'opensurveypoll'	  to add a tab in opensurvey poll view
		// 'order'            to add a tab in customer order view
		// 'order_supplier'   to add a tab in supplier order view
		// 'payment'		  to add a tab in payment view
		// 'payment_supplier' to add a tab in supplier payment view
		// 'product'          to add a tab in product view
		// 'propal'           to add a tab in propal view
		// 'project'          to add a tab in project view
		// 'stock'            to add a tab in stock view
		// 'thirdparty'       to add a tab in third party view
		// 'user'             to add a tab in user view


		// Dictionaries
		$this->dictionaries=array();


		// Boxes/Widgets
		// Add here list of php file(s) stored in assets/core/boxes that contains class to show a widget.
		$this->boxes = array(
			//0=>array('file'=>'assetswidget1.php@asset','note'=>'Widget provided by Assets','enabledbydefaulton'=>'Home'),
			//1=>array('file'=>'assetswidget2.php@asset','note'=>'Widget provided by Assets'),
			//2=>array('file'=>'assetswidget3.php@asset','note'=>'Widget provided by Assets')
		);

		// Permissions
		$this->rights = array();		// Permission array used by this module
        $this->rights_class = 'asset';
        $r=0;

        $r++;
        $this->rights[$r][0] = 51001;	            // Permission id (must not be already used)
		$this->rights[$r][1] = 'Read assets';		// Permission label
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 0; 					// Permission by default for new user (0/1)
		$this->rights[$r][4] = 'read';				// In php code, permission will be checked by test if ($user->rights->asset->level1->level2)
		$this->rights[$r][5] = '';					// In php code, permission will be checked by test if ($user->rights->asset->level1->level2)

		$r++;
		$this->rights[$r][0] = 51002;               // Permission id (must not be already used)
		$this->rights[$r][1] = 'Create/Update assets';	// Permission label
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 0; 					// Permission by default for new user (0/1)
		$this->rights[$r][4] = 'write';				// In php code, permission will be checked by test if ($user->rights->asset->level1->level2)
		$this->rights[$r][5] = '';					// In php code, permission will be checked by test if ($user->rights->asset->level1->level2)

		$r++;
		$this->rights[$r][0] = 51003;               // Permission id (must not be already used)
		$this->rights[$r][1] = 'Delete assets';		// Permission label
        $this->rights[$r][2] = 'd';
        $this->rights[$r][3] = 0; 					// Permission by default for new user (0/1)
		$this->rights[$r][4] = 'delete';			// In php code, permission will be checked by test if ($user->rights->asset->level1->level2)
		$this->rights[$r][5] = '';					// In php code, permission will be checked by test if ($user->rights->asset->level1->level2)

        $r++;
        $this->rights[$r][0] = 51005;               // Permission id (must not be already used)
        $this->rights[$r][1] = 'Setup types of asset';  // Permission label
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 0;                   // Permission by default for new user (0/1)
        $this->rights[$r][4] = 'advanced_configurer';        // In php code, permission will be checked by test if ($user->rights->asset->level1->level2)
        $this->rights[$r][5] = '';					// In php code, permission will be checked by test if ($user->rights->asset->level1->level2)

        // Menus
        
        $this->menu = 1;        // This module add menu entries. They are coded into menu manager.
	}

	/**
	 *  Function called when module is enabled.
	 *  The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *  It also creates data directories
	 *
	 *  @param      string	$options    Options when enabling module ('', 'noboxes')
	 *  @return     int             	1 if OK, 0 if KO
	 */
    public function init($options = '')
    {
        // Permissions
        $this->remove($options);

        $sql = array();

        return $this->_init($sql, $options);
    }
}
