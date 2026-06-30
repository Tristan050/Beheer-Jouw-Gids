<?php

class LeefgebiedRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_leefgebied';
    }

    protected function idColumn(): string
    {
        return 'leefgebied_id';
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
            'INSERT INTO gids_leefgebied (naam_leefgebied, beschrijving_leefgebied, sort_order) VALUES (?, ?, ?)',
            ['ssi', $name, $description, $sortOrder],
            true
        );
    }

    public function update(int $id, string $name, string $description, int $sortOrder): int
    {
        return (int) execSQL(
            'UPDATE gids_leefgebied SET naam_leefgebied = ?, beschrijving_leefgebied = ?, sort_order = ? WHERE leefgebied_id = ?',
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
            (int) ($row['leefgebied_id'] ?? 0),
            (string) ($row['naam_leefgebied'] ?? ''),
            (string) ($row['beschrijving_leefgebied'] ?? ''),
            (int) ($row['sort_order'] ?? 0)
        );
    }
}
