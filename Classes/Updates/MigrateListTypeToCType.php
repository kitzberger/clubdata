<?php

declare(strict_types=1);

namespace Medpzl\Clubdata\Updates;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('clubdata_migrateListTypeToCType')]
final class MigrateListTypeToCType implements UpgradeWizardInterface
{
    private array $actionToCTypeMapping = [
        'Club->list' => 'clubdata_list',
        'Club->detail' => 'clubdata_detail',
        'Club->services' => 'clubdata_services',
        'Club->listArchive' => 'clubdata_listarchive',
        'Club->detailArchive' => 'clubdata_detail',
        'Club->newsletterUpcoming' => 'clubdata_upcoming',
        'Club->listHighlights' => 'clubdata_listhighlights',
        'Club->preview' => 'clubdata_preview',
        'Club->carousel' => 'clubdata_carousel',
        'Club->listPress' => 'clubdata_listpress',
        'Club->listHelpers;Club->saveHelpers' => 'clubdata_listhelpers',
    ];

    public function getIdentifier(): string
    {
        return 'clubdata_migrateListTypeToCType';
    }

    public function getTitle(): string
    {
        return 'Migrate "EXT:ck_clubdata" plugins to separate content elements';
    }

    public function getDescription(): string
    {
        return 'Migrates existing "ckclubdata_pi1" plugins to separate content elements based on their switchableControllerActions configuration.';
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    public function updateNecessary(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');

        // Check for old ckclubdata_pi1 records
        $oldRecordsCount = $connection->count(
            'uid',
            'tt_content',
            ['CType' => 'list', 'list_type' => 'ckclubdata_pi1']
        );

        // Check for clubdata_pi1 records (the intermediate migration)
        $intermediateRecordsCount = $connection->count(
            'uid',
            'tt_content',
            ['CType' => 'clubdata_pi1']
        );

        return $oldRecordsCount > 0 || $intermediateRecordsCount > 0;
    }

    public function executeUpdate(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);

        // Step 1: Migrate old ckclubdata_pi1 records to clubdata_pi1 first
        $this->migrateOldListTypeRecords($connection);

        // Step 2: Split clubdata_pi1 records based on switchableControllerActions
        $this->splitPluginRecords($connection, $flexFormService);

        return true;
    }

    private function migrateOldListTypeRecords(Connection $connection): void
    {
        // First migrate any remaining ckclubdata_pi1 records to clubdata_pi1
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();
        $records = $queryBuilder
            ->select('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('list')),
                    $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('ckclubdata_pi1'))
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($records as $record) {
            $connection->update(
                'tt_content',
                [
                    'CType' => 'clubdata_pi1',
                    'list_type' => '',
                ],
                ['uid' => (int)$record['uid']]
            );
        }
    }

    private function splitPluginRecords(Connection $connection, FlexFormService $flexFormService): void
    {
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();
        $records = $queryBuilder
            ->select('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('clubdata_pi1'))
            )
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($records as $record) {
            $flexFormData = [];
            if (!empty($record['pi_flexform'])) {
                try {
                    $flexFormData = $flexFormService->convertFlexFormContentToArray($record['pi_flexform']);
                } catch (\Exception $e) {
                    // If FlexForm parsing fails, use default action
                    $flexFormData = [];
                }
            }

            $action = $flexFormData['switchableControllerActions'] ?? 'Club->list';
            $newCType = $this->actionToCTypeMapping[$action] ?? 'clubdata_list';

            // Update the record
            $connection->update(
                'tt_content',
                [
                    'CType' => $newCType,
                ],
                ['uid' => (int)$record['uid']]
            );
        }
    }
}
