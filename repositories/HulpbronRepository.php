<?php

class HulpbronRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_hulpbron';
    }

    protected function idColumn(): string
    {
        return 'HulpbronID';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function findById(int $id): ?HulpbronDTO
    {
        return $this->getRowById($id);
    }

    public function create(string $name, ?string $description): int
    {
        return (int) execSQL(
            'INSERT INTO gids_hulpbron (Hulpbron, Toelichting) VALUES (?, ?)',
            ['ss', $name, $description],
            true
        );
    }

    public function update(int $id, string $name, ?string $description): int
    {
        return (int) execSQL(
            'UPDATE gids_hulpbron SET Hulpbron = ?, Toelichting = ? WHERE HulpbronID = ?',
            ['ssi', $name, $description, $id],
            true
        );
    }

    public function delete(int $id): int
    {
        return $this->deleteRowById($id);
    }

    /**
     * Get all leefgebieden that have this hulpbron assigned
     */
    public function getLeefgebiedenForHulpbron(int $hulpbronId): array
    {
        $result = execSQL(
            'SELECT LeefgebiedID, Sort_order FROM gids_leefgebied_hulpbron WHERE HulpbronID = ? ORDER BY Sort_order',
            ['i', $hulpbronId],
            false
        );

        $leefgebieden = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $leefgebieden[] = [
                    'LeefgebiedID' => (int) ($row['LeefgebiedID'] ?? 0),
                    'Sort_order' => (int) ($row['Sort_order'] ?? 0),
                ];
            }
        }

        return $leefgebieden;
    }

    /**
     * Assign hulpbron to leefgebieden
     */
    public function assignToLeefgebieden(int $hulpbronId, array $leefgebiedIds): void
    {
        // Remove existing assignments for this hulpbron
        execSQL(
            'DELETE FROM gids_leefgebied_hulpbron WHERE HulpbronID = ?',
            ['i', $hulpbronId],
            true
        );

        // Add new assignments
        foreach ($leefgebiedIds as $index => $leefgebiedId) {
            execSQL(
                'INSERT INTO gids_leefgebied_hulpbron (LeefgebiedID, HulpbronID, Sort_order) VALUES (?, ?, ?)',
                ['iii', (int) $leefgebiedId, $hulpbronId, $index + 1],
                true
            );
        }
    }

    protected function mapRow(array $row): HulpbronDTO
    {
        return new HulpbronDTO(
            (int) ($row['HulpbronID'] ?? 0),
            (string) ($row['Hulpbron'] ?? ''),
            (string) ($row['Toelichting'] ?? '') ?: null
        );
    }
}
