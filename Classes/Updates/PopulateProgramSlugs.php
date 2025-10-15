<?php

declare(strict_types=1);

namespace Medpzl\Clubdata\Updates;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Introduce URL parts ("slugs") to all existing program records
 */
#[UpgradeWizard('clubdata_populateProgramSlugs')]
final class PopulateProgramSlugs implements UpgradeWizardInterface
{
    protected string $table = 'tx_clubdata_domain_model_program';
    protected string $fieldName = 'slug';

    public function getTitle(): string
    {
        return 'Populate and update program record slugs';
    }

    public function getDescription(): string
    {
        return 'Program records need URL parts ("slugs") to be used in speaking URLs. '
            . 'This wizard will generate slugs from the program title and datetime for existing records '
            . 'and update existing slugs to use consistent Y-m-d date format.';
    }

    public function getIdentifier(): string
    {
        return 'populateProgramSlugs';
    }

    public function executeUpdate(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->table);

        // Get all records that need slug updates (empty slugs or old format)
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder
            ->select('uid', 'pid', 'title', 'datetime', 'slug')
            ->from($this->table)
            ->where(
                $queryBuilder->expr()->or(
                    // Records without slugs
                    $queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter('', Connection::PARAM_STR)),
                    $queryBuilder->expr()->isNull('slug'),
                    // Records with old Unix timestamp format (contains 10+ digit numbers)
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->neq('slug', $queryBuilder->createNamedParameter('', Connection::PARAM_STR)),
                        $queryBuilder->expr()->like('slug', $queryBuilder->createNamedParameter('%-15%00'))
                    )
                )
            )
            ->orderBy('uid')
            ->executeQuery();

        $slugHelper = GeneralUtility::makeInstance(
            SlugHelper::class,
            $this->table,
            $this->fieldName,
            $this->getSlugFieldConfig()
        );

        while ($record = $statement->fetchAssociative()) {
            $slug = $this->generateSlugForRecord($record, $slugHelper);
            if (!empty($slug)) {
                $updateQueryBuilder = $connection->createQueryBuilder();
                $updateQueryBuilder
                    ->update($this->table)
                    ->where(
                        $updateQueryBuilder->expr()->eq(
                            'uid',
                            $updateQueryBuilder->createNamedParameter($record['uid'], Connection::PARAM_INT)
                        )
                    )
                    ->set('slug', $slug);
                $updateQueryBuilder->executeStatement();
            }
        }

        return true;
    }

    public function updateNecessary(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->table);

        $schemaManager = $connection->createSchemaManager();
        if (!$schemaManager->tablesExist([$this->table])) {
            return false;
        }

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        $elementCount = $queryBuilder
            ->count('uid')
            ->from($this->table)
            ->where(
                $queryBuilder->expr()->or(
                    // Records without slugs
                    $queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter('', Connection::PARAM_STR)),
                    $queryBuilder->expr()->isNull('slug'),
                    // Records with old Unix timestamp format (contains 10+ digit numbers)
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->neq('slug', $queryBuilder->createNamedParameter('', Connection::PARAM_STR)),
                        $queryBuilder->expr()->like('slug', $queryBuilder->createNamedParameter('%-15%00'))
                    )
                )
            )
            ->executeQuery()
            ->fetchOne();

        return $elementCount > 0;
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    /**
     * Generate a slug for the given record in Y-m-d format
     */
    protected function generateSlugForRecord(array $record, SlugHelper $slugHelper): string
    {
        // Generate slug in Y-m-d-title format directly
        $baseSlug = '';

        // Add date information in Y-m-d format if datetime is available
        if (!empty($record['datetime'])) {
            $datetime = new \DateTime('@' . $record['datetime']);
            $dateString = $datetime->format('Y-m-d');
            $baseSlug = $dateString;
        }

        if (!empty($record['title'])) {
            if (!empty($baseSlug)) {
                $baseSlug .= '/' . $record['title'];
            } else {
                $baseSlug = $record['title'];
            }
        }

        // If we still don't have a base slug, use the UID
        if (empty($baseSlug)) {
            $baseSlug = 'program-' . $record['uid'];
        }

        $slug = $slugHelper->sanitize($baseSlug);

        // Ensure uniqueness by checking for duplicates manually
        if (!empty($slug)) {
            $slug = $this->ensureUniqueSlug($slug, (int)$record['uid'], (int)$record['pid']);
        }

        return $slug;
    }

    /**
     * Ensure the slug is unique by appending a suffix if needed
     */
    protected function ensureUniqueSlug(string $slug, int $recordUid, int $pid): string
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->table);
        $originalSlug = $slug;
        $counter = 0;

        do {
            $queryBuilder = $connection->createQueryBuilder();
            $count = $queryBuilder
                ->count('uid')
                ->from($this->table)
                ->where(
                    $queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter($slug, Connection::PARAM_STR)),
                    $queryBuilder->expr()->neq('uid', $queryBuilder->createNamedParameter($recordUid, Connection::PARAM_INT))
                )
                ->executeQuery()
                ->fetchOne();

            if ($count > 0) {
                $counter++;
                $slug = $originalSlug . '-' . $counter;
            }
        } while ($count > 0);

        return $slug;
    }

    /**
     * Load the TCA field configuration for the slug field
     */
    protected function getSlugFieldConfig(): array
    {
        return $GLOBALS['TCA'][$this->table]['columns'][$this->fieldName]['config'];
    }
}
