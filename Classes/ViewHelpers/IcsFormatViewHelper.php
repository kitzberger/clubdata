<?php

declare(strict_types=1);

namespace Medpzl\Clubdata\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Format text for ICS files according to RFC 5545
 */
class IcsFormatViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'The value to format for ICS', true);
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $value = $arguments['value'] ?? '';
        
        // Strip HTML tags and decode entities
        $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Normalize whitespace
        $value = trim(preg_replace('/\s+/', ' ', $value));
        
        // Escape special characters according to RFC 5545 (order matters!)
        $value = str_replace(
            ['\\', "\n", "\r\n", "\r", ';', ','],
            ['\\\\', '\\n', '\\n', '\\n', '\\;', '\\,'],
            $value
        );
        
        // Fold lines at 75 octets (RFC 5545 requirement)
        // Continuation lines start with a space
        $result = '';
        $currentLength = 0;
        $maxLength = 75;
        
        // Split by characters to handle multibyte correctly
        $chars = mb_str_split($value, 1, 'UTF-8');
        
        foreach ($chars as $char) {
            $charLength = strlen($char); // Byte length, not character length
            
            if ($currentLength + $charLength > $maxLength && $currentLength > 0) {
                // Need to fold
                $result .= "\r\n "; // CRLF + space for continuation
                $currentLength = 1; // Space counts as 1
            }
            
            $result .= $char;
            $currentLength += $charLength;
        }
        
        return $result;
    }
}
