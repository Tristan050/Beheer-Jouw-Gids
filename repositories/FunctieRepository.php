<?php

class FunctieRepository
{
    public function getAll(): array
    {
        $result = execSQL(
            'SELECT FunctieID, LeefgebiedID, Naam_functie, Beschrijving_functie, Sort_order FROM gids_functie ORDER BY Sort_order ASC, FunctieID ASC',
            [],
            false
        );

        if (!$result) {
            return [];
        }

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $this->mapRow($row);
        }

        return $items;
    }

    public function findById(int $id): ?array
    {
        $result = execSQL(
            'SELECT FunctieID, LeefgebiedID, Naam_functie, Beschrijving_functie, Sort_order FROM gids_functie WHERE FunctieID = ? LIMIT 1',
            ['i', $id],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        return $this->mapRow($result->fetch_assoc());
    }

    public function create(int $leefgebiedId, string $name, string $description, int $sortOrder): int
    {
        return (int) execSQL(
            'INSERT INTO gids_functie (LeefgebiedID, Naam_functie, Beschrijving_functie, Sort_order) VALUES (?, ?, ?, ?)',
            ['issi', $leefgebiedId, $name, $description, $sortOrder],
            true
        );
    }

    public function update(int $id, int $leefgebiedId, string $name, string $description, int $sortOrder): int
    {
        return (int) execSQL(
            'UPDATE gids_functie SET LeefgebiedID = ?, Naam_functie = ?, Beschrijving_functie = ?, Sort_order = ? WHERE FunctieID = ?',
            ['issii', $leefgebiedId, $name, $description, $sortOrder, $id],
            true
        );
    }

    public function delete(int $id): int
    {
        return (int) execSQL(
            'DELETE FROM gids_functie WHERE FunctieID = ?',
            ['i', $id],
            true
        );
    }

    private function mapRow(array $row): array
    {
        return [
            'id' => (int) ($row['FunctieID'] ?? 0),
            'leefgebied_id' => (int) ($row['LeefgebiedID'] ?? 0),
            'name' => (string) ($row['Naam_functie'] ?? ''),
            'description' => (string) ($row['Beschrijving_functie'] ?? ''),
            'sort_order' => (int) ($row['Sort_order'] ?? 0),
        ];
    }
}