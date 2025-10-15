<?php

namespace Medpzl\Clubdata\Domain\Service;

class SessionHandler implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * Returns the object stored in the user's PHP session
     * @return Object the stored object
     */
    public function restoreFromSession()
    {
        $sessionData = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->getKey('ses', 'tx_clubdata_pi1');
        //$sessionData=$_SESSION['pinea']['tx_ckapartments_pi1'];
        return unserialize($sessionData);
    }

    /**
     * Writes an object into the PHP session
     * @param	$object	any serializable object to store into the session
     * @return	Tx_MyExt_Domain_Session_SessionHandler this
     */
    public function writeToSession($object)
    {
        $sessionData = serialize($object);
        $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->setKey('ses', 'tx_clubdata_pi1', $sessionData);
        $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->sesData_change = true;
        $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->storeSessionData();
        //$_SESSION['pinea']['tx_ckapartments_pi1']=$sessionData;
        return $this;
    }

    /**
     * Cleans up the session: removes the stored object from the PHP session
     * @return	Tx_MyExt_Domain_Session_SessionHandler this
     */
    public function cleanUpSession()
    {
        $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->setKey('ses', 'tx_clubdata_pi1', null);
        $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->storeSessionData();
        return $this;
    }
}
