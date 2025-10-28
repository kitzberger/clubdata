<?php

declare(strict_types=1);

namespace Medpzl\Clubdata\PageTitle;

use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

final class ProgramPageTitleProvider extends AbstractPageTitleProvider
{
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
