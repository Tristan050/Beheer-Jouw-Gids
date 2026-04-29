<?php

class VerdiepingKoppelingRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_verdieping_koppeltabel';
    }

    protected function idColumn(): string
    {
        return 'VerdiepingsvraagID';
    }

    protected function orderBy(): string
    {
        return 'VerdiepingsvraagID ASC, OrganisatieID ASC';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function getDetailedRows(): array
    {
        $sql = 'SELECT vk.VerdiepingsvraagID, vv.Vraag, vk.OrganisatieID, o.Naam AS OrganisatieNaam
                FROM gids_verdieping_koppeltabel vk
                INNER JOIN gids_verdieping_vragen vv ON vv.VerdiepingsvraagID = vk.VerdiepingsvraagID
                INNER JOIN gids_organisatie o ON o.OrganisatieID = vk.OrganisatieID
                ORDER BY vk.VerdiepingsvraagID ASC, vk.OrganisatieID ASC';
        $result = execSQL($sql, [], false);

        if (!$result) {
            return [];
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = [
                'verdiepingsvraag_id' => (int) ($row['VerdiepingsvraagID'] ?? 0),
                'vraag' => (string) ($row['Vraag'] ?? ''),
                'organisatie_id' => (int) ($row['OrganisatieID'] ?? 0),
                'organisatie_name' => (string) ($row['OrganisatieNaam'] ?? ''),
            ];
        }

        return $rows;
    }

    public function getOrganisatieIdsForVraag(int $verdiepingsvraagId): array
    {
        $sql = 'SELECT OrganisatieID FROM gids_verdieping_koppeltabel WHERE VerdiepingsvraagID = ? ORDER BY OrganisatieID ASC';
        $result = execSQL($sql, ['i', $verdiepingsvraagId], false);

        if (!$result) {
            return [];
        }

        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = (int) ($row['OrganisatieID'] ?? 0);
        }

        return $ids;
    }

    public function replaceOrganisatiesForVraag(int $verdiepingsvraagId, array $organisatieIds): void
    {
        execSQL(
            'DELETE FROM gids_verdieping_koppeltabel WHERE VerdiepingsvraagID = ?',
            ['i', $verdiepingsvraagId],
            true
        );

        foreach ($organisatieIds as $organisatieId) {
            execSQL(
                'INSERT INTO gids_verdieping_koppeltabel (VerdiepingsvraagID, OrganisatieID) VALUES (?, ?)',
                ['ii', $verdiepingsvraagId, (int) $organisatieId],
                true
            );
        }
    }

    public function deleteLink(int $verdiepingsvraagId, int $organisatieId): int
    {
        return (int) execSQL(
            'DELETE FROM gids_verdieping_koppeltabel WHERE VerdiepingsvraagID = ? AND OrganisatieID = ?',
            ['ii', $verdiepingsvraagId, $organisatieId],
            true
        );
    }

    protected function mapRow(array $row): VerdiepingKoppelingDTO
    {
        return new VerdiepingKoppelingDTO(
            (int) ($row['VerdiepingsvraagID'] ?? 0),
            (int) ($row['OrganisatieID'] ?? 0)
        );
    }
}