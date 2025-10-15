<?php

namespace Medpzl\Clubdata\ViewHelpers;

use Medpzl\Clubdata\Domain\Repository\ProgramServiceRepository;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class HelperInfoViewHelper extends AbstractViewHelper
{
    public function __construct(
        protected ProgramServiceRepository $programServiceRepository
    ) {
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('program', 'int', 'Program ID', true);
        $this->registerArgument('service', 'int', 'Service ID', true);
        $this->registerArgument('mode', 'int', 'Mode', true, 0);
    }

    public function render()
    {
        $program = $this->arguments['program'];
        $service = $this->arguments['service'];
        $mode = $this->arguments['mode'];

        $entry = $this->programServiceRepository->findEntry(0, $program, $service);

        if ($mode == 1) {
            DebuggerUtility::var_dump($entry);
        }

        if (count($entry)) {
            if ($entry[0]->getUser()) {
                return $entry[0]->getUser()->getFirstName() . ' ' . $entry[0]->getUser()->getLastName();
            }
        } else {
            return '';
        }
    }
}
