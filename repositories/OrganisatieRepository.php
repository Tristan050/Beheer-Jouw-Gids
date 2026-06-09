<?php

class OrganisatieRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_organisatie';
    }

    protected function idColumn(): string
    {
        return 'organisatie_id';
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
            'INSERT INTO gids_organisatie (naam, adres, telefoon, email, website) VALUES (?, ?, ?, ?, ?)',
            ['sssss', $name, $address, $phone, $email, $website],
            true
        );
    }

    public function update(int $id, string $name, string $address, string $phone, string $email, string $website): int
    {
        return (int) execSQL(
            'UPDATE gids_organisatie SET naam = ?, adres = ?, telefoon = ?, email = ?, website = ? WHERE organisatie_id = ?',
            ['sssssi', $name, $address, $phone, $email, $website, $id],
            true
        );
    }

    public function delete(int $id): int
    {
        execSQL(
            'DELETE FROM gids_verdieping_koppeltabel WHERE organisatie_id = ?',
            ['i', $id],
            true
        );

        return $this->deleteRowById($id);
    }

    protected function mapRow(array $row): OrganisatieDTO
    {
        return new OrganisatieDTO(
            (int) ($row['organisatie_id'] ?? 0),
            (string) ($row['naam'] ?? ''),
            (string) ($row['adres'] ?? ''),
            (string) ($row['telefoon'] ?? ''),
            (string) ($row['email'] ?? ''),
            (string) ($row['website'] ?? '')
        );
    }
}