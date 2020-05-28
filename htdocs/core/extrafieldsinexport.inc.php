<?php

if (empty($keyforselect) || empty($keyforelement) || empty($keyforaliasextra))
{
    
    dol_print_error('', 'include of file extrafieldsinexport.inc.php was done but var $keyforselect or $keyforelement or $keyforaliasextra was not set');
    exit;
}

// Add extra fields
$sql="SELECT name, label, type, param, fieldcomputed, fielddefault FROM ".MAIN_DB_PREFIX."extrafields WHERE elementtype = '".$keyforselect."' AND type != 'separate' AND entity IN (0, ".$conf->entity.') ORDER BY pos ASC';

$resql=$this->db->query($sql);
if ($resql)    // This can fail when class is used on old database (during migration for example)
{
	while ($obj=$this->db->fetch_object($resql))
	{
		$fieldname=$keyforaliasextra.'.'.$obj->name;
		$fieldlabel=ucfirst($obj->label);
		$typeFilter="Text";
		$typefield=preg_replace('/\(.*$/', '', $obj->type);	// double(24,8) -> double
		switch ($typefield) {
			case 'int':
			case 'integer':
			case 'double':
			case 'price':
				$typeFilter="Numeric";
				break;
			case 'date':
			case 'datetime':
			case 'timestamp':
				$typeFilter="Date";
				break;
			case 'boolean':
				$typeFilter="Boolean";
				break;
			case 'select':
			    if (! empty($conf->global->EXPORT_LABEL_FOR_SELECT))
			    {
    			    $tmpparam=unserialize($obj->param);	
    			    if ($tmpparam['options'] && is_array($tmpparam['options'])) {
    			        $typeFilter="Select:".$obj->param;
    			    }
			    }
			    break;
			case 'sellist':
				$tmp='';
				$tmpparam=unserialize($obj->param);	
				if ($tmpparam['options'] && is_array($tmpparam['options'])) {
					$tmpkeys=array_keys($tmpparam['options']);
					$tmp=array_shift($tmpkeys);
				}
				if (preg_match('/[a-z0-9_]+:[a-z0-9_]+:[a-z0-9_]+/', $tmp)) $typeFilter="List:".$tmp;
				break;
		}
		if ($obj->type!='separate')
		{
		    // If not a computed field
		    if (empty($obj->fieldcomputed))
		    {
    			$this->export_fields_array[$r][$fieldname]=$fieldlabel;
    			$this->export_TypeFields_array[$r][$fieldname]=$typeFilter;
    			$this->export_entities_array[$r][$fieldname]=$keyforelement;
		    }
			// If this is a computed field
			else
			{
			    $this->export_fields_array[$r][$fieldname]=$fieldlabel;
			    $this->export_TypeFields_array[$r][$fieldname]=$typeFilter.'Compute';
			    $this->export_special_array[$r][$fieldname]=$obj->fieldcomputed;
			    $this->export_entities_array[$r][$fieldname]=$keyforelement;
			}
		}
	}
}
// End add axtra fields
