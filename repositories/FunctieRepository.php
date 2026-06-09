<?php

class FunctieRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_functie';
    }

    protected function idColumn(): string
    {
        return 'functie_id';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function findById(int $id): ?FunctieDTO
    {
        return $this->getRowById($id);
    }

    public function create(int $leefgebiedId, string $name, string $description, int $sortOrder): int
    {
        return (int) execSQL(
            'INSERT INTO gids_functie (leefgebied_id, naam_functie, beschrijving_functie, sort_order) VALUES (?, ?, ?, ?)',
            ['issi', $leefgebiedId, $name, $description, $sortOrder],
            true
        );
    }

    public function update(int $id, int $leefgebiedId, string $name, string $description, int $sortOrder): int
    {
        return (int) execSQL(
            'UPDATE gids_functie SET leefgebied_id = ?, naam_functie = ?, beschrijving_functie = ?, sort_order = ? WHERE functie_id = ?',
            ['issii', $leefgebiedId, $name, $description, $sortOrder, $id],
            true
        );
    }

    public function delete(int $id): int
    {
        return $this->deleteRowById($id);
    }

    protected function mapRow(array $row): FunctieDTO
    {
        return new FunctieDTO(
            (int) ($row['functie_id'] ?? 0),
            (int) ($row['leefgebied_id'] ?? 0),
            (string) ($row['naam_functie'] ?? ''),
            (string) ($row['beschrijving_functie'] ?? ''),
            (int) ($row['sort_order'] ?? 0)
        );
    }
}