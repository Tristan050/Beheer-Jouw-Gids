<?php

class LeefgebiedRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_leefgebied';
    }

    protected function idColumn(): string
    {
        return 'LeefgebiedID';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function findById(int $id): ?LeefgebiedDTO
    {
        return $this->getRowById($id);
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
        return $this->deleteRowById($id);
    }

    protected function mapRow(array $row): LeefgebiedDTO
    {
        return new LeefgebiedDTO(
            (int) ($row['LeefgebiedID'] ?? 0),
            (string) ($row['Naam_leefgebied'] ?? ''),
            (string) ($row['beschrijving_leefgebied'] ?? ''),
            (int) ($row['Sort_order'] ?? 0)
        );
    }
}
