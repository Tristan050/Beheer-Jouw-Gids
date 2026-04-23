<?php

class FunctieRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_functie';
    }

    protected function idColumn(): string
    {
        return 'FunctieID';
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
        return $this->deleteRowById($id);
    }

    protected function mapRow(array $row): FunctieDTO
    {
        return new FunctieDTO(
            (int) ($row['FunctieID'] ?? 0),
            (int) ($row['LeefgebiedID'] ?? 0),
            (string) ($row['Naam_functie'] ?? ''),
            (string) ($row['Beschrijving_functie'] ?? ''),
            (int) ($row['Sort_order'] ?? 0)
        );
    }
}