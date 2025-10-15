<?php

namespace Medpzl\Clubdata\ViewHelpers;

use Medpzl\Clubdata\Domain\Repository\ProgramServiceRepository;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class HasHelperViewHelper extends AbstractViewHelper
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
        $this->registerArgument('check', 'int', 'Check flag', true);
    }

    public function render()
    {
        $user = $this->arguments['user'];
        $program = $this->arguments['program'];
        $service = $this->arguments['service'];
        $check = $this->arguments['check'];

        $entry = $this->programServiceRepository->findEntry(0, $program, $service);

        if (count($entry)) {
            if ($user || $check) {
                return "1";
            } else {
                return $entry[0]->getUser()->getUid();
            }
        } else {
            return '0';
        }
    }
}
