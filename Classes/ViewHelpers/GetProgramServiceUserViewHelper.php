<?php

namespace Medpzl\Clubdata\ViewHelpers;

use Medpzl\Clubdata\Domain\Model\Program;
use Medpzl\Clubdata\Domain\Model\ProgramServiceUser;
use Medpzl\Clubdata\Domain\Model\Service;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class GetProgramServiceUserViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('program', Program::class, 'Program', true);
        $this->registerArgument('service', Service::class, 'Service', true);
    }

    public function render(): ?ProgramServiceUser
    {
        $program = $this->arguments['program'];
        $service = $this->arguments['service'];

        foreach ($program->getServices() as $programService) {
            if ($programService->getService() === $service) {
                return $programService;
            }
        }

        return null;
    }
}
