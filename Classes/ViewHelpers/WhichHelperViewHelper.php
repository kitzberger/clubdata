<?php

namespace Medpzl\Clubdata\ViewHelpers;

use Medpzl\Clubdata\Domain\Repository\ProgramServiceRepository;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class WhichHelperViewHelper extends AbstractViewHelper
{
    public function __construct(
        protected ProgramServiceRepository $programServiceRepository
    ) {
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('user', 'int', 'User ID', true);
        $this->registerArgument('program', 'int', 'Program ID', true);
        $this->registerArgument('service', 'int', 'Service ID', true);
        $this->registerArgument('disable', 'int', 'Disable?', true);
    }

    public function render()
    {
        $user = $this->arguments['user'];
        $program = $this->arguments['program'];
        $service = $this->arguments['service'];
        $disable = $this->arguments['disable'];

        $entry = $this->programServiceRepository->findEntry(0, $program, $service);

        if (count($entry) && $entry[0]->getUser()->getUid() != $user) {
            return 'disabled';
        } elseif ($disable) {
            return '';
        } else {
            if (isset($entry[0]) && $entry[0]->getUser()->getUid() == $user) {
                return 'me';
            }
        }
    }
}
