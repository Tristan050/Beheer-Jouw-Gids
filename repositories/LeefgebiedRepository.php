<?php

class LeefgebiedRepository
{
    public function getAll(): array
    {
        $result = execSQL(
            'SELECT LeefgebiedID, Naam_leefgebied, beschrijving_leefgebied, Sort_order FROM gids_leefgebied ORDER BY Sort_order ASC, LeefgebiedID ASC',
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
            'SELECT LeefgebiedID, Naam_leefgebied, beschrijving_leefgebied, Sort_order FROM gids_leefgebied WHERE LeefgebiedID = ? LIMIT 1',
            ['i', $id],
            false
        );

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        return $this->mapRow($result->fetch_assoc());
    }

    public function create(string $name, string $description, int $sortOrder): int
    {
        return (int) execSQL(
            'INSERT INTO gids_leefgebied (Naam_leefgebied, beschrijving_leefgebied, Sort_order) VALUES (?, ?, ?)',
            ['ssi', $name, $description, $sortOrder],
            true
        );
    }

    public function update(int $id, string $name, string $description, int $sortOrder): int
    {
        return (int) execSQL(
            'UPDATE gids_leefgebied SET Naam_leefgebied = ?, beschrijving_leefgebied = ?, Sort_order = ? WHERE LeefgebiedID = ?',
            ['ssii', $name, $description, $sortOrder, $id],
            true
        );
    }

    public function delete(int $id): int
    {
        return (int) execSQL(
            'DELETE FROM gids_leefgebied WHERE LeefgebiedID = ?',
            ['i', $id],
            true
        );
    }

    private function mapRow(array $row): array
    {
        return [
            'id' => (int) ($row['LeefgebiedID'] ?? 0),
            'name' => (string) ($row['Naam_leefgebied'] ?? ''),
            'description' => (string) ($row['beschrijving_leefgebied'] ?? ''),
            'sort_order' => (int) ($row['Sort_order'] ?? 0),
        ];
    }
}
