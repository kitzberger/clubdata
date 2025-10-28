<?php

namespace Medpzl\Clubdata\Hooks;

class Entrance
{
    /**
     * Sets entrances to 1 hour before start of the show
     */
    public function processDatamap_preProcessFieldArray(&$fieldArray, $table, $id, &$pObj)
    {
        date_default_timezone_set('Europe/Berlin');
        if ($table == 'tx_clubdata_domain_model_program') {
            if (empty($fieldArray['entrance']) && !empty($fieldArray['datetime'])) {
                $showtime = substr($fieldArray['datetime'], 0, -6);

                $entranceTime = date('H:i', strtotime($showtime . '  -1 hours'));
                $fieldArray['entrance'] = $entranceTime;
            }
        }
        return $fieldArray;
    }
}
