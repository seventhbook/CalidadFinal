<?php
/* Copyright (C) 2017-2019  Frédéric France     <frederic.france@netlogic.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    core/triggers/interface_99_modZapier_ZapierTriggers.class.php
 * \ingroup zapier
 * \brief   Example trigger.
 *
 *
 * \remarks You can create other triggers by copying this one.
 * - File name should be either:
 *      - interface_99_modZapier_MyTrigger.class.php
 *      - interface_99_all_MyTrigger.class.php
 * - The file must stay in core/triggers
 * - The class name must be InterfaceMytrigger
 * - The constructor method must be named InterfaceMytrigger
 * - The name property name must be MyTrigger
 */

require_once DOL_DOCUMENT_ROOT.'/core/triggers/dolibarrtriggers.class.php';


/**
 *  Class of triggers for Zapier module
 */
class InterfaceZapierTriggers extends DolibarrTriggers
{
    /**
     * @var DoliDB Database handler
     */
    protected $db;

    /**
     * Constructor
     *
     * @param DoliDB $db Database handler
     */
    public function __construct($db)
    {
        $this->db = $db;

        $this->name = preg_replace('/^Interface/i', '', get_class($this));
        $this->family = "technic";
        $this->description = "Zapier triggers.";
        // 'development', 'experimental', 'dolibarr' or version
        $this->version = 'development';
        $this->picto = 'zapier';
    }

    /**
     * Trigger name
     *
     * @return string Name of trigger file
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Trigger description
     *
     * @return string Description of trigger file
     */
    public function getDesc()
    {
        return $this->description;
    }


    /**
     * Function called when a Dolibarrr business event is done.
     * All functions "runTrigger" are triggered if file
     * is inside directory core/triggers
     *
     * @param string        $action     Event action code
     * @param CommonObject  $object     Object
     * @param User          $user       Object user
     * @param Translate     $langs      Object langs
     * @param Conf          $conf       Object conf
     * @return int                      <0 if KO, 0 if no triggered ran, >0 if OK
     */
    public function runTrigger($action, $object, User $user, Translate $langs, Conf $conf)
    {
        global $db;
        if (empty($conf->zapier->enabled)) {
            // Module not active, we do nothing
            return 0;
        }
        $logtriggeraction = false;
        $sql = '';
        if ($action!='') {
            $actions = explode('_', $action);
            $sql = 'SELECT rowid, url FROM '.MAIN_DB_PREFIX.'zapier_hook';
            $sql .= ' WHERE module="'.$db->escape(strtolower($actions[0])).'" AND action="'.$db->escape(strtolower($actions[1])).'"';
            //setEventMessages($sql, null);
        }

        switch ($action) {
            // Users
            
            
            
            
            
            
            
            
            
            
            // Warning: To increase performances, this action is triggered only if constant MAIN_ACTIVATE_UPDATESESSIONTRIGGER is set to 1.
            

            // Actions
            case 'ACTION_MODIFY':
                
                break;
            case 'ACTION_CREATE':
                $resql = $db->query($sql);
                // TODO voir comment regrouper les webhooks en un post
                while ($resql && $obj = $db->fetch_array($resql)) {
                    $cleaned = cleanObjectDatas(dol_clone($object));
                    $cleaned = cleanAgendaEventsDatas($cleaned);
                    $json = json_encode($cleaned);
                    // call the zapierPostWebhook() function
                    zapierPostWebhook($obj['url'], $json);
                    //setEventMessages($obj['url'], null);
                }
                $logtriggeraction = true;
                break;
            case 'ACTION_DELETE':
                
                break;

            // Groups
            
            
            

            // Companies
            case 'COMPANY_CREATE':
                $resql = $db->query($sql);
                while ($resql && $obj = $db->fetch_array($resql)) {
                    $cleaned = cleanObjectDatas(dol_clone($object));
                    $json = json_encode($cleaned);
                    // call the zapierPostWebhook() function
                    zapierPostWebhook($obj['url'], $json);
                }
                $logtriggeraction = true;
                break;
            case 'COMPANY_MODIFY':
                $resql = $db->query($sql);
                while ($resql && $obj = $db->fetch_array($resql)) {
                    $cleaned = cleanObjectDatas(dol_clone($object));
                    $json = json_encode($cleaned);
                    // call the zapierPostWebhook() function
                    zapierPostWebhook($obj['url'], $json);
                }
                $logtriggeraction = true;
                break;
            case 'COMPANY_DELETE':
                
                break;

            // Contacts
            case 'CONTACT_CREATE':
            case 'CONTACT_MODIFY':
            case 'CONTACT_DELETE':
            case 'CONTACT_ENABLEDISABLE':
                break;
            // Products
            
            
            
            
            
            

            //Stock mouvement
            

            //MYECMDIR
            
            
            

            // Customer orders
            case 'ORDER_CREATE':
                $resql = $db->query($sql);
                while ($resql && $obj = $db->fetch_array($resql)) {
                    $cleaned = cleanObjectDatas(dol_clone($object));
                    $json = json_encode($cleaned);
                    // call the zapierPostWebhook() function
                    zapierPostWebhook($obj['url'], $json);
                }
                $logtriggeraction = true;
                break;
            case 'ORDER_CLONE':
                break;
            case 'ORDER_VALIDATE':
                break;
            case 'ORDER_DELETE':
            case 'ORDER_CANCEL':
            case 'ORDER_SENTBYMAIL':
            case 'ORDER_CLASSIFY_BILLED':
            case 'ORDER_SETDRAFT':
            case 'LINEORDER_INSERT':
            case 'LINEORDER_UPDATE':
            case 'LINEORDER_DELETE':
                break;
            // Supplier orders
            
            
            
            
            
            
            
            
            
            
            
            

            // Proposals
            
            
            
            
            
            
            
            
            
            
            

            // SupplierProposal
            
            
            
            
            
            
            
            
            
            
            

            // Contracts
            
            
            
            
            
            
            
            

            // Bills
            
            
            
            
            
            
            
            
            
            
            
            

            //Supplier Bill
            
            
            
            
            
            
            
            
            
            

            // Payments
            
            
            
            

            // Online
            
            
            

            // Donation
            
            
            

            // Interventions
            
            
            
            
            
            
            

            // Members
            
            
            
            
            
            
            

            // Categories
            
            
            
            

            // Projects
            
            
            

            // Project tasks
            
            
            

            // Task time spent
            
            
            

            // Shipping
            
            
            
            
            
            
            
            
        }
        if ($logtriggeraction) {
            dol_syslog("Trigger '" . $this->name . "' for action '.$action.' launched by " . __FILE__ . " id=" . $object->id);
        }
        return 0;
    }
}
/**
 * Post webhook in zapier with object data
 *
 * @param string $url url provided by zapier
 * @param string $json data to send
 * @return void
 */
function zapierPostWebhook($url, $json)
{
    $headers = array('Accept: application/json', 'Content-Type: application/json');
    // TODO supprimer le webhook en cas de mauvaise réponse
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($ch);
    curl_close($ch);
}

/**
 * Clean sensible object datas
 *
 * @param   object  $toclean    Object to clean
 * @return  array   Array of cleaned object properties
 */
function cleanObjectDatas($toclean)
{
    // Remove $db object property for object
    unset($toclean->db);

    // Remove linkedObjects. We should already have linkedObjectIds that avoid huge responses
    unset($toclean->linkedObjects);

    unset($toclean->lines); // should be ->lines

    unset($toclean->fields);

    unset($toclean->oldline);

    unset($toclean->error);
    unset($toclean->errors);

    unset($toclean->ref_previous);
    unset($toclean->ref_next);
    unset($toclean->ref_int);

    unset($toclean->projet);     // Should be fk_project
    unset($toclean->project);    // Should be fk_project
    unset($toclean->author);     // Should be fk_user_author
    unset($toclean->timespent_old_duration);
    unset($toclean->timespent_id);
    unset($toclean->timespent_duration);
    unset($toclean->timespent_date);
    unset($toclean->timespent_datehour);
    unset($toclean->timespent_withhour);
    unset($toclean->timespent_fk_user);
    unset($toclean->timespent_note);

    unset($toclean->statuts);
    unset($toclean->statuts_short);
    unset($toclean->statuts_logo);
    unset($toclean->statuts_long);

    unset($toclean->element);
    unset($toclean->fk_element);
    unset($toclean->table_element);
    unset($toclean->table_element_line);
    unset($toclean->picto);

    unset($toclean->skip_update_total);
    unset($toclean->context);

    // Remove the $oldcopy property because it is not supported by the JSON
    // encoder. The following error is generated when trying to serialize
    // it: "Error encoding/decoding JSON: Type is not supported"
    // Note: Event if this property was correctly handled by the JSON
    // encoder, it should be ignored because keeping it would let the API
    // have a very strange behavior: calling PUT and then GET on the same
    // resource would give different results:
    // PUT /objects/{id} -> returns object with oldcopy = previous version of the object
    // GET /objects/{id} -> returns object with oldcopy empty
    unset($toclean->oldcopy);

    // If object has lines, remove $db property
    if (isset($toclean->lines) && count($toclean->lines) > 0)  {
        $nboflines = count($toclean->lines);
        for ($i=0; $i < $nboflines; $i++) {
            cleanObjectDatas($toclean->lines[$i]);
        }
    }

    // If object has linked objects, remove $db property
    /*
    if(isset($toclean->linkedObjects) && count($toclean->linkedObjects) > 0)  {
        foreach($toclean->linkedObjects as $type_object => $linked_object) {
            foreach($linked_object as $toclean2clean) {
                $this->cleanObjectDatas($toclean2clean);
            }
        }
    }*/

    return $toclean;
}

/**
 * Clean sensible object datas
 *
 * @param   object  $toclean    Object to clean
 * @return  array   Array of cleaned object properties
 */
function cleanAgendaEventsDatas($toclean)
{
    unset($toclean->usermod);
    unset($toclean->libelle);
    
    unset($toclean->context);
    unset($toclean->canvas);
    unset($toclean->contact);
    unset($toclean->contact_id);
    unset($toclean->thirdparty);
    unset($toclean->user);
    unset($toclean->origin);
    unset($toclean->origin_id);
    unset($toclean->ref_ext);
    unset($toclean->statut);
    unset($toclean->country);
    unset($toclean->country_id);
    unset($toclean->country_code);
    unset($toclean->barcode_type);
    unset($toclean->barcode_type_code);
    unset($toclean->barcode_type_label);
    unset($toclean->barcode_type_coder);
    unset($toclean->mode_reglement_id);
    unset($toclean->cond_reglement_id);
    unset($toclean->cond_reglement);
    unset($toclean->fk_delivery_address);
    unset($toclean->shipping_method_id);
    unset($toclean->fk_account);
    unset($toclean->total_ht);
    unset($toclean->total_tva);
    unset($toclean->total_localtax1);
    unset($toclean->total_localtax2);
    unset($toclean->total_ttc);
    unset($toclean->fk_incoterms);
    unset($toclean->libelle_incoterms);
    unset($toclean->location_incoterms);
    unset($toclean->name);
    unset($toclean->lastname);
    unset($toclean->firstname);
    unset($toclean->civility_id);
    unset($toclean->contact);
    unset($toclean->societe);

    return $toclean;
}
