<?php

namespace Medpzl\Clubdata\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Medpzl\Clubdata\Domain\Repository\ProgramServiceRepository;

class SelectedHelperViewHelper extends AbstractViewHelper
{
    public function __construct(
        protected ProgramServiceRepository $programServiceRepository
    ) {}

    public function initializeArguments(): void
    {
        $this->registerArgument('user', 'int', 'User ID', true);
        $this->registerArgument('program', 'int', 'Program ID', true);
        $this->registerArgument('service', 'int', 'Service ID', true);
    }

    public function render()
    {
        $user = $this->arguments['user'];
        $program = $this->arguments['program'];
        $service = $this->arguments['service'];

        $entry = $this->programServiceRepository->findEntry($user, $program, $service);

        if (count($entry)) {
            return 'selected="selected"';
        } else {
            return '';
        }
    }
}
