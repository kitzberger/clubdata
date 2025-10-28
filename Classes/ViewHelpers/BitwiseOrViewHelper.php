<?php

namespace Medpzl\Clubdata\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class BitwiseOrViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('number', 'integer', 'Integer', true);
        $this->registerArgument('bit', 'integer', 'Bit', true);
    }

    public function render()
    {
        $number = $this->arguments['number'];
        $bit = $this->arguments['bit'];

        return $number & $bit;
    }
}
