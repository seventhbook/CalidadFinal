<?php
/* Copyright (C) 2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
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
 *  \file       htdocs/modulebuilder/template/myobject_agenda.php
 *  \ingroup    bom
 *  \brief      Page of MyObject events
 */

// Load Dolibarr environment
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/bom/class/bom.class.php';
require_once DOL_DOCUMENT_ROOT.'/bom/lib/bom.lib.php';


// Load translation files required by the page
$langs->loadLangs(array("mrp", "other"));

// Get parameters
$id = GETPOST('id', 'int');
$ref        = GETPOST('ref', 'alpha');
$action = GETPOST('action', 'alpha');
$cancel     = GETPOST('cancel', 'aZ09');
$backtopage = GETPOST('backtopage', 'alpha');

if (GETPOST('actioncode', 'array'))
{
    $actioncode = GETPOST('actioncode', 'array', 3);
    if (!count($actioncode)) $actioncode = '0';
}
else
{
    $actioncode = GETPOST("actioncode", "alpha", 3) ?GETPOST("actioncode", "alpha", 3) : (GETPOST("actioncode") == '0' ? '0' : (empty($conf->global->AGENDA_DEFAULT_FILTER_TYPE_FOR_OBJECT) ? '' : $conf->global->AGENDA_DEFAULT_FILTER_TYPE_FOR_OBJECT));
}
$search_agenda_label = GETPOST('search_agenda_label');
$limit = GETPOST('limit', 'int') ?GETPOST('limit', 'int') : $conf->liste_limit;
$sortfield = GETPOST("sortfield", 'alpha');
$sortorder = GETPOST("sortorder", 'alpha');
$page = GETPOST("page", 'int');
if (empty($page) || $page == -1) { $page = 0; }     // If $page is not defined, or '' or -1
$offset = $limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (!$sortfield) $sortfield = 'a.datep,a.id';
if (!$sortorder) $sortorder = 'DESC';

// Initialize technical objects
$object = new BOM($db);
$extrafields = new ExtraFields($db);
$diroutputmassaction = $conf->bom->dir_output.'/temp/massgeneration/'.$user->id;
$hookmanager->initHooks(array('bomagenda', 'globalcard')); // Note that conf->hooks_modules contains array
// Fetch optionals attributes and labels
$extrafields->fetch_name_optionals_label($object->table_element);

// Load object
include DOL_DOCUMENT_ROOT.'/core/actions_fetchobject.inc.php'; // Must be include, not include_once  // Must be include, not include_once. Include fetch and fetch_thirdparty but not fetch_optionals
if ($id > 0 || !empty($ref)) $upload_dir = $conf->bom->multidir_output[$object->entity]."/".$object->id;



/*
 *	Actions
 */

$parameters = array('id'=>$socid);
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action); // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

if (empty($reshook))
{
    // Cancel
    if (GETPOST('cancel', 'alpha') && !empty($backtopage))
    {
        header("Location: ".$backtopage);
        exit;
    }

    // Purge search criteria
    if (GETPOST('button_removefilter_x', 'alpha') || GETPOST('button_removefilter.x', 'alpha') || GETPOST('button_removefilter', 'alpha')) // All tests are required to be compatible with all browsers
    {
        $actioncode = '';
        $search_agenda_label = '';
    }
}



/*
 *	View
 */

$contactstatic = new Contact($db);

$form = new Form($db);

if ($object->id > 0)
{
	$title = $langs->trans("Agenda");
	
	$help_url = '';
	llxHeader('', $title, $help_url);

	if (!empty($conf->notification->enabled)) $langs->load("mails");
	$head = bomPrepareHead($object);


	dol_fiche_head($head, 'agenda', $langs->trans("BillOfMaterials"), -1, 'bom');

	// Object card
	
	$linkback = '<a href="'.dol_buildpath('/bom/myobject_list.php', 1).'?restore_lastsearch_values=1'.(!empty($socid) ? '&socid='.$socid : '').'">'.$langs->trans("BackToList").'</a>';

	$morehtmlref = '<div class="refidno">';
	$morehtmlref .= '</div>';


	dol_banner_tab($object, 'ref', $linkback, 1, 'ref', 'ref', $morehtmlref);

    print '<div class="fichecenter">';
    print '<div class="underbanner clearboth"></div>';

    $object->info($object->id);
	dol_print_object_info($object, 1);

	print '</div>';

	dol_fiche_end();



	// Actions buttons

    $objthirdparty = $object;
    $objcon = new stdClass();

    $out = '&origin='.$object->element.'&originid='.$object->id;
    $permok = $user->rights->agenda->myactions->create;
    if ((!empty($objthirdparty->id) || !empty($objcon->id)) && $permok)
    {
        if (get_class($objthirdparty) == 'Societe') $out .= '&amp;socid='.$objthirdparty->id;
        $out .= (!empty($objcon->id) ? '&amp;contactid='.$objcon->id : '').'&amp;backtopage=1&amp;percentage=-1';
	}


	print '<div class="tabsAction">';

    if (!empty($conf->agenda->enabled))
    {
    	if (!empty($user->rights->agenda->myactions->create) || !empty($user->rights->agenda->allactions->create))
    	{
        	print '<a class="butAction" href="'.DOL_URL_ROOT.'/comm/action/card.php?action=create'.$out.'">'.$langs->trans("AddAction").'</a>';
    	}
    	else
    	{
        	print '<a class="butActionRefused classfortooltip" href="#">'.$langs->trans("AddAction").'</a>';
    	}
    }

    print '</div>';

    if (!empty($conf->agenda->enabled) && (!empty($user->rights->agenda->myactions->read) || !empty($user->rights->agenda->allactions->read)))
    {
    	$param = '&id='.$object->id.'&socid='.$socid;
        if (!empty($contextpage) && $contextpage != $_SERVER["PHP_SELF"]) $param .= '&contextpage='.urlencode($contextpage);
        if ($limit > 0 && $limit != $conf->liste_limit) $param .= '&limit='.urlencode($limit);

        // List of all actions
		$filters = array();
        $filters['search_agenda_label'] = $search_agenda_label;

        show_actions_done($conf, $langs, $db, $object, null, 0, $actioncode, '', $filters, $sortfield, $sortorder);
    }
}

// End of page
llxFooter();
$db->close();
