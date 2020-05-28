<?php
/* Copyright (C) 2004-2018  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2019       Frédéric France         <frederic.france@netlogic.fr>
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
/**
 *  \defgroup   zapier     Module Zapier
 *  \brief      Zapier module descriptor.
 *
 *  \file       htdocs/core/modules/modZapier.class.php
 *  \ingroup    zapier
 *  \brief      Description and activation file for module Zapier
 */
include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';

/**
 *  Description and activation class for module Zapier
 */
class modZapier extends DolibarrModules
{
    /**
     * Constructor. Define names, constants, directories, boxes, permissions
     *
     * @param DoliDB $db Database handler
     */
    public function __construct($db)
    {
        global $langs,$conf;

        $this->db = $db;
        // Id for module (must be unique).
        // Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
        $this->numero = 50330;
        // Key text used to identify module (for permissions, menus, etc...)
        $this->rights_class = 'zapier';
        // Family can be 'base' (core modules),'crm','financial','hr','projects','products','ecm','technic' (transverse modules),'interface' (link with external tools),'other','...'
        // It is used to group modules by family in module setup page
        $this->family = "interface";
        // Module position in the family on 2 digits ('01', '10', '20', ...)
        $this->module_position = '13';
        // Gives the possibility for the module, to provide his own family info and position of this family (Overwrite $this->family and $this->module_position. Avoid this)
        
        // Module label (no space allowed), used if translation string 'ModuleZapierName' not found (Zapier is name of module).
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        // Module description, used if translation string 'ModuleZapierDesc' not found (Zapier is name of module).
        $this->description = "ZapierDescription";
        // Used only if file README.md and README-LL.md not found.
        $this->descriptionlong = "Zapier description (Long)";
        // Possible values for version are: 'development', 'experimental', 'dolibarr', 'dolibarr_deprecated' or a version string like 'x.y.z'
        $this->version = 'development';
        //Url to the file with your last numberversion of this module
        
        // Key used in llx_const table to save module status enabled/disabled (where ZAPIERFORDOLIBARR is value of property name of module in uppercase)
        $this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
        // Name of image file used for this module.
        // If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
        // If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
        $this->picto = 'zapier';
        // Define some features supported by module (triggers, login, substitutions, menus, css, etc...)
        $this->module_parts = array(
            // Set this to 1 if module has its own trigger directory (core/triggers)
            'triggers' => 1,
            // Set this to 1 if module has its own login method file (core/login)
            'login' => 0,
            // Set this to 1 if module has its own substitution function file (core/substitutions)
            'substitutions' => 0,
            // Set this to 1 if module has its own menus handler directory (core/menus)
            'menus' => 0,
            // Set this to 1 if module overwrite template dir (core/tpl)
            'tpl' => 0,
            // Set this to 1 if module has its own barcode directory (core/modules/barcode)
            'barcode' => 0,
            // Set this to 1 if module has its own models directory (core/modules/xxx)
            'models' => 0,
            // Set this to 1 if module has its own theme directory (theme)
            'theme' => 0,
            // Set this to relative path of css file if module has its own css file
            'css' => array(
                //    '/zapier/css/zapier.css.php',
            ),
            // Set this to relative path of js file if module must load a js on all pages
            'js' => array(
                //   '/zapier/js/zapier.js.php',
            ),
            // Set here all hooks context managed by module. To find available hook context, make a "grep -r '>initHooks(' *" on source code. You can also set hook context 'all'
            'hooks' => array(
                //   'data' => array(
                //       'hookcontext1',
                //       'hookcontext2',
                
                //   'entity' => '0',
            ),
            // Set this to 1 if feature of module are opened to external users
            'moduleforexternal' => 0,
        );
        // Data directories to create when module is enabled.
        // Example: this->dirs = array("/zapier/temp","/zapier/subdir");
        $this->dirs = array("/zapier/temp");
        // Config pages. Put here list of php page, stored into zapier/admin directory, to use to setup module.
        $this->config_page_url = array(
        //    "setup.php@zapier"
        );
        // Dependencies
        // A condition to hide module
        $this->hidden = false;
        // List of module class names as string that must be enabled if this module is enabled. Example: array('always1'=>'modModuleToEnable1','always2'=>'modModuleToEnable2', 'FR1'=>'modModuleToEnableFR'...)
        $this->depends = array();
        // List of module class names as string to disable if this one is disabled. Example: array('modModuleToDisable1', ...)
        $this->requiredby = array();
        // List of module class names as string this module is in conflict with. Example: array('modModuleToDisable1', ...)
        $this->conflictwith = array();
        $this->langfiles = array("zapier");
        // Minimum version of PHP required by module
        
        // Minimum version of Dolibarr required by module
        $this->need_dolibarr_version = array(10, 0);
        // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
        $this->warnings_activation = array();
        // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
        $this->warnings_activation_ext = array();
        
        //     'FR'=>'ZapierWasAutomaticallyActivatedBecauseOfYourCountryChoice',
        
        // If true, can't be disabled
        
        // Constants
        // List of particular constants to add when module is enabled (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
        // Example: $this->const=array(
        //    1 => array('ZAPIERFORDOLIBARR_MYNEWCONST1', 'chaine', 'myvalue', 'This is a constant to add', 1),
        //    2 => array('ZAPIERFORDOLIBARR_MYNEWCONST2', 'chaine', 'myvalue', 'This is another constant to add', 0, 'current', 1)
        
        $this->const = array(
            // 1 => array('ZAPIERFORDOLIBARR_MYCONSTANT', 'chaine', 'avalue', 'This is a constant to add', 1, 'allentities', 1)
        );
        // Some keys to add into the overwriting translation tables
        /*$this->overwrite_translation = array(
            'en_US:ParentCompany'=>'Parent company or reseller',
            'fr_FR:ParentCompany'=>'Maison mère ou revendeur'
        )*/
        if (! isset($conf->zapier) || ! isset($conf->zapier->enabled)) {
            $conf->zapier = new stdClass();
            $conf->zapier->enabled=0;
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
        /* Example:
        $this->dictionaries=array(
            'langs'=>'mylangfile@zapier',
            // List of tables we want to see into dictonnary editor
            'tabname'=>array(MAIN_DB_PREFIX."table1",MAIN_DB_PREFIX."table2",MAIN_DB_PREFIX."table3"),
            // Label of tables
            'tablib'=>array("Table1","Table2","Table3"),
            // Request to select fields
            'tabsql'=>array('SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table1 as f','SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table2 as f','SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table3 as f'),
            // Sort order
            'tabsqlsort'=>array("label ASC","label ASC","label ASC"),
            // List of fields (result of select to show dictionary)
            'tabfield'=>array("code,label","code,label","code,label"),
            // List of fields (list of fields to edit a record)
            'tabfieldvalue'=>array("code,label","code,label","code,label"),
            // List of fields (list of fields for insert)
            'tabfieldinsert'=>array("code,label","code,label","code,label"),
            // Name of columns with primary key (try to always name it 'rowid')
            'tabrowid'=>array("rowid","rowid","rowid"),
            // Condition to show each dictionary
            'tabcond'=>array($conf->zapier->enabled,$conf->zapier->enabled,$conf->zapier->enabled)
        );
        */
        // Boxes/Widgets
        // Add here list of php file(s) stored in zapier/core/boxes that contains class to show a widget.
        $this->boxes = array(
            //  0 => array(
            //      'file' => 'zapierwidget1.php@zapier',
            //      'note' => 'Widget provided by Zapier',
            //      'enabledbydefaulton' => 'Home',
            
            //1=>array('file'=>'zapierwidget2.php@zapier','note'=>'Widget provided by Zapier'),
            //2=>array('file'=>'zapierwidget3.php@zapier','note'=>'Widget provided by Zapier')
        );
        // Cronjobs (List of cron jobs entries to add when module is enabled)
        // unit_frequency must be 60 for minute, 3600 for hour, 86400 for day, 604800 for week
        $this->cronjobs = array(
            //  0 => array(
            //      'label' => 'MyJob label',
            //      'jobtype' => 'method',
            //      'class' => '/zapier/class/myobject.class.php',
            //      'objectname' => 'MyObject',
            //      'method' => 'doScheduledJob',
            //      'parameters' => '',
            //      'comment' => 'Comment',
            //      'frequency' => 2,
            //      'unitfrequency' => 3600,
            //      'status' => 0,
            //      'test' => '$conf->zapier->enabled',
            //      'priority' => 50,
            
        );
        // Example: $this->cronjobs=array(
        //    0=>array('label'=>'My label', 'jobtype'=>'method', 'class'=>'/dir/class/file.class.php', 'objectname'=>'MyClass', 'method'=>'myMethod', 'parameters'=>'param1, param2', 'comment'=>'Comment', 'frequency'=>2, 'unitfrequency'=>3600, 'status'=>0, 'test'=>'$conf->zapier->enabled', 'priority'=>50),
        //    1=>array('label'=>'My label', 'jobtype'=>'command', 'command'=>'', 'parameters'=>'param1, param2', 'comment'=>'Comment', 'frequency'=>1, 'unitfrequency'=>3600*24, 'status'=>0, 'test'=>'$conf->zapier->enabled', 'priority'=>50)
        
        // Permissions
        // Permission array used by this module
        $this->rights = array();

        $r=0;
        // Permission id (must not be already used)
        $this->rights[$r][0] = $this->numero + $r;
        // Permission label
        $this->rights[$r][1] = 'Read myobject of Zapier';
        // Permission by default for new user (0/1)
        $this->rights[$r][3] = 1;
        // In php code, permission will be checked by test if ($user->rights->zapier->level1->level2)
        $this->rights[$r][4] = 'read';
        // In php code, permission will be checked by test if ($user->rights->zapier->level1->level2)
        $this->rights[$r][5] = '';
        $r++;
        $this->rights[$r][0] = $this->numero + $r;
        $this->rights[$r][1] = 'Create/Update myobject of Zapier';
        $this->rights[$r][3] = 1;
        $this->rights[$r][4] = 'write';
        $this->rights[$r][5] = '';
        $r++;
        $this->rights[$r][0] = $this->numero + $r;
        $this->rights[$r][1] = 'Delete myobject of Zapier';
        $this->rights[$r][3] = 1;
        $this->rights[$r][4] = 'delete';
        $this->rights[$r][5] = '';

        // Main menu entries
        $this->menu = array();  // List of menus to add
        $r=0;

        // Add here entries to declare new menus
        
        //     'fk_menu' => '',                          // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
        //     'type' => 'top',                          // This is a Top menu entry
        //     'titre' => 'Zapier',
        //     'mainmenu' => 'zapier',
        //     'leftmenu' => '',
        //     'url' => '/zapier/zapierindex.php',
        //     'langs' => 'zapier@zapier',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
        //     'position' => 1000+$r,
        //     'enabled' => '$conf->zapier->enabled',  // Define condition to show or hide menu entry. Use '$conf->zapier->enabled' if entry must be visible if module is enabled.
        //     'perms' => '1',			                // Use 'perms'=>'$user->rights->zapier->level1->level2' if you want your menu with a permission rules
        //     'target' => '',
        //     'user' => 2,				                // 0=Menu for internal users, 1=external users, 2=both
        

        /*
        $this->menu[$r++]=array(
            'fk_menu'=>'fk_mainmenu=zapier',	    // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
            'type'=>'left',			                // This is a Left menu entry
            'titre'=>'List MyObject',
            'mainmenu'=>'zapier',
            'leftmenu'=>'zapier_myobject_list',
            'url'=>'/zapier/myobject_list.php',
            'langs'=>'zapier@zapier',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
            'position'=>1000+$r,
            'enabled'=>'$conf->zapier->enabled',  // Define condition to show or hide menu entry. Use '$conf->zapier->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
            'perms'=>'1',			                // Use 'perms'=>'$user->rights->zapier->level1->level2' if you want your menu with a permission rules
            'target'=>'',
            'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
        );
        $this->menu[$r++]=array(
            'fk_menu'=>'fk_mainmenu=zapier,fk_leftmenu=zapier',	    // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
            'type'=>'left',			                // This is a Left menu entry
            'titre'=>'New MyObject',
            'mainmenu'=>'zapier',
            'leftmenu'=>'zapier_myobject_new',
            'url'=>'/zapier/myobject_page.php?action=create',
            'langs'=>'zapier@zapier',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
            'position'=>1000+$r,
            'enabled'=>'$conf->zapier->enabled',  // Define condition to show or hide menu entry. Use '$conf->zapier->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
            'perms'=>'1',			                // Use 'perms'=>'$user->rights->zapier->level1->level2' if you want your menu with a permission rules
            'target'=>'',
            'user'=>2,                              // 0=Menu for internal users, 1=external users, 2=both
        );
        */
    }

    /**
     *  Function called when module is enabled.
     *  The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     *  It also creates data directories
     *
     *  @param      string  $options    Options when enabling module ('', 'noboxes')
     *  @return     int                 1 if OK, 0 if KO
     */
    public function init($options = '')
    {
        $result = $this->_load_tables('/zapier/sql/');
        if ($result < 0) return -1; // Do not activate module if not allowed errors found on module SQL queries (the _load_table run sql with run_sql with error allowed parameter to 'default')

        // Create extrafields
        
        
        
        
        
        
        
        $sql = array();
        return $this->_init($sql, $options);
    }

    /**
     *  Function called when module is disabled.
     *  Remove from database constants, boxes and permissions from Dolibarr database.
     *  Data directories are not deleted
     *
     *  @param      string	$options    Options when enabling module ('', 'noboxes')
     *  @return     int                 1 if OK, 0 if KO
     */
    public function remove($options = '')
    {
        $sql = array();
        return $this->_remove($sql, $options);
    }
}
