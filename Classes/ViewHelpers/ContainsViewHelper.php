<?php

namespace Medpzl\Clubdata\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class ContainsViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('needle', 'string', 'String to search for', true);
        $this->registerArgument('haystack', 'string', 'String to search in', true);
    }

    public function render()
    {
        $needle = $this->arguments['needle'];
        $haystack = $this->arguments['haystack'];

        if (strpos($haystack, '' . $needle) !== false) {
            return true;
        } else {
            return false;
        }
    }
}
