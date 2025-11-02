<?php

declare(strict_types=1);

namespace Medpzl\Clubdata\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Creates an ALTREP (Alternate Representation) data URI with HTML for ICS DESCRIPTION
 */
class IcsHtmlAltrepViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'The HTML value to encode', true);
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $html = $arguments['value'] ?? '';
        
        // Keep basic HTML formatting
        // Convert common tags to simpler HTML
        $html = str_replace(['<p>', '</p>'], ['', '<br>'], $html);
        $html = strip_tags($html, '<br><b><i><strong><em><a>');
        
        // URL encode for data URI
        $encoded = rawurlencode($html);
        
        return 'data:text/html,' . $encoded;
    }
}
