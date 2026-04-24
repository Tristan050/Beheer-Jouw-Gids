<?php

class VerdiepingsvraagRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_verdieping_vragen';
    }

    protected function idColumn(): string
    {
        return 'VerdiepingsvraagID';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function findById(int $id): ?VerdiepingsvraagDTO
    {
        return $this->getRowById($id);
    }

    public function create(int $aandachtspuntId, string $vraag): int
    {
        return (int) execSQL(
            'INSERT INTO gids_verdieping_vragen (AandachtspuntID, Vraag) VALUES (?, ?)',
            ['is', $aandachtspuntId, $vraag],
            true
        );
    }

    public function update(int $id, int $aandachtspuntId, string $vraag): int
    {
        return (int) execSQL(
            'UPDATE gids_verdieping_vragen SET AandachtspuntID = ?, Vraag = ? WHERE VerdiepingsvraagID = ?',
            ['isi', $aandachtspuntId, $vraag, $id],
            true
        );
    }

    public function delete(int $id): int
    {
        execSQL(
            'DELETE FROM gids_verdieping_koppeltabel WHERE VerdiepingsvraagID = ?',
            ['i', $id],
            true
        );

        return $this->deleteRowById($id);
    }

    protected function mapRow(array $row): VerdiepingsvraagDTO
    {
        return new VerdiepingsvraagDTO(
            (int) ($row['VerdiepingsvraagID'] ?? 0),
            (int) ($row['AandachtspuntID'] ?? 0),
            (string) ($row['Vraag'] ?? '')
        );
    }
}