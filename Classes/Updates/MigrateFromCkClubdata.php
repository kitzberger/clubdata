<?php

declare(strict_types=1);

namespace Medpzl\Clubdata\Updates;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Migrate data from old ck_clubdata extension tables to new clubdata tables
 */
#[UpgradeWizard('clubdata_migrateFromCkClubdata')]
final class MigrateFromCkClubdata implements UpgradeWizardInterface
{
    /**
     * Mapping from old table names to new table names
     */
    protected array $tableMapping = [
        'tx_ckclubdata_domain_model_program' => 'tx_clubdata_domain_model_program',
        'tx_ckclubdata_domain_model_state' => 'tx_clubdata_domain_model_state',
        'tx_ckclubdata_domain_model_link' => 'tx_clubdata_domain_model_link',
        'tx_ckclubdata_domain_model_service' => 'tx_clubdata_domain_model_service',
        'tx_ckclubdata_domain_model_programlinkrel' => 'tx_clubdata_domain_model_programlinkrel',
        'tx_ckclubdata_domain_model_programservicerel' => 'tx_clubdata_domain_model_programservicerel',
        'tx_ckclubdata_domain_model_seating' => 'tx_clubdata_domain_model_seating',
        'tx_ckclubdata_domain_model_programservice' => 'tx_clubdata_domain_model_programservice',
    ];

    public function getTitle(): string
    {
        return 'Migrate ck_clubdata extension data to clubdata';
    }

    public function getDescription(): string
    {
        return 'Migrates all data from the old ck_clubdata extension tables to the new clubdata extension tables. ' .
            'This includes programs, services, states, links, seating configurations, and all relationship data. ' .
            'Also updates sys_file_reference table names to maintain file attachments. ' .
            'The old tables will remain intact after migration for safety.';
    }

    public function getIdentifier(): string
    {
        return 'migrateFromCkClubdata';
    }

    public function executeUpdate(): bool
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        try {
            // Process each table mapping
            foreach ($this->tableMapping as $oldTable => $newTable) {
                $this->migrateTableData($connectionPool, $oldTable, $newTable);
            }

            // Update sys_file_reference table names for file attachments
            $this->updateFileReferenceTableNames($connectionPool);

            return true;
        } catch (\Exception $e) {
            // Log the error for debugging
            error_log('MigrateFromCkClubdata migration failed: ' . $e->getMessage());
            return false;
        }
    }

    public function updateNecessary(): bool
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        foreach ($this->tableMapping as $oldTable => $newTable) {
            // Check if old table exists and has data
            if (!$this->tableExists($connectionPool, $oldTable)) {
                continue;
            }

            // Check if new table exists
            if (!$this->tableExists($connectionPool, $newTable)) {
                continue;
            }

            // Check if old table has data that needs to be migrated
            if ($this->hasDataToMigrate($connectionPool, $oldTable, $newTable)) {
                return true;
            }
        }

        // Check if file references need to be updated
        if ($this->hasFileReferencesToUpdate($connectionPool)) {
            return true;
        }

        return false;
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    /**
     * Check if a table exists in the database
     */
    protected function tableExists(ConnectionPool $connectionPool, string $tableName): bool
    {
        try {
            $connection = $connectionPool->getConnectionForTable($tableName);
            $schemaManager = $connection->createSchemaManager();
            return $schemaManager->tablesExist([$tableName]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if the old table has data that needs to be migrated
     */
    protected function hasDataToMigrate(ConnectionPool $connectionPool, string $oldTable, string $newTable): bool
    {
        try {
            $connection = $connectionPool->getConnectionForTable($oldTable);

            // Count records in old table
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->getRestrictions()->removeAll();
            $oldCount = $queryBuilder
                ->count('*')
                ->from($oldTable)
                ->executeQuery()
                ->fetchOne();

            if ($oldCount == 0) {
                return false;
            }

            // Count records in new table
            $newConnection = $connectionPool->getConnectionForTable($newTable);
            $newQueryBuilder = $newConnection->createQueryBuilder();
            $newQueryBuilder->getRestrictions()->removeAll();
            $newCount = $newQueryBuilder
                ->count('*')
                ->from($newTable)
                ->executeQuery()
                ->fetchOne();

            // Migration needed if old table has more records than new table
            return $oldCount > $newCount;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Migrate data from old table to new table
     */
    protected function migrateTableData(ConnectionPool $connectionPool, string $oldTable, string $newTable): void
    {
        if (!$this->tableExists($connectionPool, $oldTable) || !$this->tableExists($connectionPool, $newTable)) {
            return;
        }

        $connection = $connectionPool->getConnectionForTable($oldTable);
        $newConnection = $connectionPool->getConnectionForTable($newTable);

        // Get all existing UIDs in new table to avoid duplicates
        $existingUids = $this->getExistingUids($newConnection, $newTable);

        // Get all columns from old table
        $oldColumns = $this->getTableColumns($connection, $oldTable);
        $newColumns = $this->getTableColumns($newConnection, $newTable);

        // Find common columns
        $commonColumns = array_intersect($oldColumns, $newColumns);

        if (empty($commonColumns)) {
            return;
        }

        // Fetch all records from old table
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder
            ->select('*')
            ->from($oldTable)
            ->where(
                $queryBuilder->expr()->notIn(
                    'uid',
                    $queryBuilder->createNamedParameter($existingUids ?: [0], Connection::PARAM_INT_ARRAY)
                )
            )
            ->executeQuery();

        // Insert records into new table
        while ($record = $statement->fetchAssociative()) {
            $insertData = [];
            foreach ($commonColumns as $column) {
                $insertData[$column] = $record[$column];
            }

            if (!empty($insertData)) {
                $newConnection->insert($newTable, $insertData);
            }
        }
    }

    /**
     * Get existing UIDs from the target table
     */
    protected function getExistingUids(Connection $connection, string $tableName): array
    {
        try {
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->getRestrictions()->removeAll();
            $statement = $queryBuilder
                ->select('uid')
                ->from($tableName)
                ->executeQuery();

            $uids = [];
            while ($row = $statement->fetchAssociative()) {
                $uids[] = (int)$row['uid'];
            }

            return $uids;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get all column names for a table
     */
    protected function getTableColumns(Connection $connection, string $tableName): array
    {
        try {
            $schemaManager = $connection->createSchemaManager();
            $columns = $schemaManager->listTableColumns($tableName);
            return array_keys($columns);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Check if there are file references that need to be updated
     */
    protected function hasFileReferencesToUpdate(ConnectionPool $connectionPool): bool
    {
        try {
            $connection = $connectionPool->getConnectionForTable('sys_file_reference');

            foreach ($this->tableMapping as $oldTable => $newTable) {
                $queryBuilder = $connection->createQueryBuilder();
                $count = $queryBuilder
                    ->count('*')
                    ->from('sys_file_reference')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'tablenames',
                            $queryBuilder->createNamedParameter($oldTable, Connection::PARAM_STR)
                        )
                    )
                    ->executeQuery()
                    ->fetchOne();

                if ($count > 0) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update sys_file_reference table names from old to new table names
     */
    protected function updateFileReferenceTableNames(ConnectionPool $connectionPool): void
    {
        try {
            $connection = $connectionPool->getConnectionForTable('sys_file_reference');

            // Update table names in sys_file_reference for each mapped table
            foreach ($this->tableMapping as $oldTable => $newTable) {
                $queryBuilder = $connection->createQueryBuilder();
                $queryBuilder
                    ->update('sys_file_reference')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'tablenames',
                            $queryBuilder->createNamedParameter($oldTable, Connection::PARAM_STR)
                        )
                    )
                    ->set('tablenames', $newTable)
                    ->executeStatement();
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the entire migration
            error_log('Failed to update sys_file_reference table names: ' . $e->getMessage());
        }
    }
}
