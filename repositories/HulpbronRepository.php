<?php

class HulpbronRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_hulpbron';
    }

    protected function idColumn(): string
    {
        return 'hulpbron_id';
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
            'INSERT INTO gids_hulpbron (hulpbron, toelichting) VALUES (?, ?)',
            ['ss', $name, $description],
            true
        );
    }

    public function update(int $id, string $name, ?string $description): int
    {
        return (int) execSQL(
            'UPDATE gids_hulpbron SET hulpbron = ?, toelichting = ? WHERE hulpbron_id = ?',
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
            'SELECT leefgebied_id, sort_order FROM gids_leefgebied_hulpbron WHERE hulpbron_id = ? ORDER BY sort_order',
            ['i', $hulpbronId],
            false
        );

        $leefgebieden = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $leefgebieden[] = [
                    'leefgebied_id' => (int) ($row['leefgebied_id'] ?? 0),
                    'sort_order' => (int) ($row['sort_order'] ?? 0),
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
            'DELETE FROM gids_leefgebied_hulpbron WHERE hulpbron_id = ?',
            ['i', $hulpbronId],
            true
        );

        // Add new assignments
        foreach ($leefgebiedIds as $index => $leefgebiedId) {
            execSQL(
                'INSERT INTO gids_leefgebied_hulpbron (leefgebied_id, hulpbron_id, sort_order) VALUES (?, ?, ?)',
                ['iii', (int) $leefgebiedId, $hulpbronId, $index + 1],
                true
            );
        }
    }

    protected function mapRow(array $row): HulpbronDTO
    {
        return new HulpbronDTO(
            (int) ($row['hulpbron_id'] ?? 0),
            (string) ($row['hulpbron'] ?? ''),
            (string) ($row['toelichting'] ?? '') ?: null
        );
    }
}
