<?php

class OrganisatieRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_organisatie';
    }

    protected function idColumn(): string
    {
        return 'OrganisatieID';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function findById(int $id): ?OrganisatieDTO
    {
        return $this->getRowById($id);
    }

    public function create(string $name, string $address, string $phone, string $email, string $website): int
    {
        return (int) execSQL(
            'INSERT INTO gids_organisatie (Naam, Adres, Telefoon, Email, Website) VALUES (?, ?, ?, ?, ?)',
            ['sssss', $name, $address, $phone, $email, $website],
            true
        );
    }

    public function update(int $id, string $name, string $address, string $phone, string $email, string $website): int
    {
        return (int) execSQL(
            'UPDATE gids_organisatie SET Naam = ?, Adres = ?, Telefoon = ?, Email = ?, Website = ? WHERE OrganisatieID = ?',
            ['sssssi', $name, $address, $phone, $email, $website, $id],
            true
        );
    }

    public function delete(int $id): int
    {
        execSQL(
            'DELETE FROM gids_verdieping_koppeltabel WHERE OrganisatieID = ?',
            ['i', $id],
            true
        );

        return $this->deleteRowById($id);
    }

    protected function mapRow(array $row): OrganisatieDTO
    {
        return new OrganisatieDTO(
            (int) ($row['OrganisatieID'] ?? 0),
            (string) ($row['Naam'] ?? ''),
            (string) ($row['Adres'] ?? ''),
            (string) ($row['Telefoon'] ?? ''),
            (string) ($row['Email'] ?? ''),
            (string) ($row['Website'] ?? '')
        );
    }
}