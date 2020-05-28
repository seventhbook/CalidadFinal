<?php
/* Copyright (C) 2015 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * or see https://www.gnu.org/
 */

/**
 *	\file			htdocs/core/actions_builddoc.inc.php
 *  \brief			Code for actions on building or deleting documents
 */









if (!empty($permissioncreate) && empty($permissiontoadd)) $permissiontoadd = $permissioncreate; // For backward compatibility

// Build doc
if ($action == 'builddoc' && $permissiontoadd)
{
    if (is_numeric(GETPOST('model', 'alpha')))
    {
        $error = $langs->trans("ErrorFieldRequired", $langs->transnoentities("Model"));
    }
    else
    {
   		// Reload to get all modified line records and be ready for hooks
        $ret = $object->fetch($id);
        $ret = $object->fetch_thirdparty();
        /*if (empty($object->id) || ! $object->id > 0)
        {
            dol_print_error('Object must have been loaded by a fetch');
            exit;
        }*/

        // Save last template used to generate document
    	if (GETPOST('model', 'alpha'))
    	{
    	    $object->setDocModel($user, GETPOST('model', 'alpha'));
    	}

        // Special case to force bank account
        
        //{
        if (GETPOST('fk_bank', 'int')) {
            
            $object->fk_bank = GETPOST('fk_bank', 'int');
        } elseif (!empty($object->fk_account)) {
            $object->fk_bank = $object->fk_account;
        }
        //}

        $outputlangs = $langs;
        $newlang = '';

        if ($conf->global->MAIN_MULTILANGS && empty($newlang) && GETPOST('lang_id', 'aZ09')) $newlang = GETPOST('lang_id', 'aZ09');
        if ($conf->global->MAIN_MULTILANGS && empty($newlang) && isset($object->thirdparty->default_lang)) $newlang = $object->thirdparty->default_lang; 
        if ($conf->global->MAIN_MULTILANGS && empty($newlang) && isset($object->default_lang)) $newlang = $object->default_lang; 
        if (!empty($newlang))
        {
            $outputlangs = new Translate("", $conf);
            $outputlangs->setDefaultLang($newlang);
        }

        // To be sure vars is defined
        if (empty($hidedetails)) $hidedetails = 0;
        if (empty($hidedesc)) $hidedesc = 0;
        if (empty($hideref)) $hideref = 0;
        if (empty($moreparams)) $moreparams = null;

        $result = $object->generateDocument($object->modelpdf, $outputlangs, $hidedetails, $hidedesc, $hideref, $moreparams);
        if ($result <= 0)
        {
            setEventMessages($object->error, $object->errors, 'errors');
            $action = '';
        }
        else
        {
        	if (empty($donotredirect))	// This is set when include is done by bulk action "Bill Orders"
        	{
	            setEventMessages($langs->trans("FileGenerated"), null);

	            $urltoredirect = $_SERVER['REQUEST_URI'];
	            $urltoredirect = preg_replace('/#builddoc$/', '', $urltoredirect);
	            $urltoredirect = preg_replace('/action=builddoc&?/', '', $urltoredirect); // To avoid infinite loop

	            header('Location: '.$urltoredirect.'#builddoc');
	            exit;
        	}
        }
    }
}

// Delete file in doc form
if ($action == 'remove_file' && $permissiontoadd)
{
	if (!empty($upload_dir)) {
	    require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';

	    if (empty($object->id) || !$object->id > 0)
	    {
	        // Reload to get all modified line records and be ready for hooks
	        $ret = $object->fetch($id);
	        $ret = $object->fetch_thirdparty();
	    }

	    $langs->load("other");
	    $filetodelete = GETPOST('file', 'alpha');
	    $file = $upload_dir.'/'.$filetodelete;
	    $ret = dol_delete_file($file, 0, 0, 0, $object);
	    if ($ret) setEventMessages($langs->trans("FileWasRemoved", $filetodelete), null, 'mesgs');
	    else setEventMessages($langs->trans("ErrorFailToDeleteFile", $filetodelete), null, 'errors');

	    // Make a redirect to avoid to keep the remove_file into the url that create side effects
	    $urltoredirect = $_SERVER['REQUEST_URI'];
	    $urltoredirect = preg_replace('/#builddoc$/', '', $urltoredirect);
	    $urltoredirect = preg_replace('/action=remove_file&?/', '', $urltoredirect);

	    header('Location: '.$urltoredirect);
	    exit;
	}
	else {
		setEventMessages('BugFoundVarUploaddirnotDefined', null, 'errors');
	}
}
