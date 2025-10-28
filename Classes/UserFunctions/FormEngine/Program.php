<?php

namespace Medpzl\Clubdata\UserFunctions\FormEngine;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class Program
{
    /**
     * Add two items to existing ones
     *
     * @param array $params
     */
    public function itemsProcFunc(&$params): void
    {
        if ($params['table'] === 'tx_clubdata_domain_model_program' &&
            $params['field'] === 'flags') {

            $pid = $params['effectivePid'];
            $pageTsConfig = BackendUtility::getPagesTSconfig($pid);
            $pageTsConfig = $pageTsConfig['TCEFORM.']['tx_clubdata_domain_model_program.']['flags.'] ?? null;

            foreach ($pageTsConfig['addItems.'] ?? [] as $value => $label) {
                $params['items'][] = [
                    'label' => $label,
                    'value' => $value,
                ];
            }
        }
    }

    private function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
