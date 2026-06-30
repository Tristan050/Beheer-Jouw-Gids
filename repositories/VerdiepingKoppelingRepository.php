<?php

class VerdiepingKoppelingRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_verdieping_koppeltabel';
    }

    protected function idColumn(): string
    {
        return 'verdiepingsvraag_id';
    }

    protected function orderBy(): string
    {
        return 'verdiepingsvraag_id ASC, organisatie_id ASC';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function getDetailedRows(): array
    {
        $sql = 'SELECT vk.verdiepingsvraag_id, vv.vraag, vk.organisatie_id, o.naam AS organisatie_naam
                FROM gids_verdieping_koppeltabel vk
                INNER JOIN gids_verdieping_vragen vv ON vv.verdiepingsvraag_id = vk.verdiepingsvraag_id
                INNER JOIN gids_organisatie o ON o.organisatie_id = vk.organisatie_id
                ORDER BY vk.verdiepingsvraag_id ASC, vk.organisatie_id ASC';
        $result = execSQL($sql, [], false);

        if (!$result) {
            return [];
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = [
                'verdiepingsvraag_id' => (int) ($row['verdiepingsvraag_id'] ?? 0),
                'vraag' => (string) ($row['vraag'] ?? ''),
                'organisatie_id' => (int) ($row['organisatie_id'] ?? 0),
                'organisatie_name' => (string) ($row['organisatie_naam'] ?? ''),
            ];
        }

        return $rows;
    }

    public function getOrganisatieIdsForVraag(int $verdiepingsvraagId): array
    {
        $sql = 'SELECT organisatie_id FROM gids_verdieping_koppeltabel WHERE verdiepingsvraag_id = ? ORDER BY organisatie_id ASC';
        $result = execSQL($sql, ['i', $verdiepingsvraagId], false);

        if (!$result) {
            return [];
        }

        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = (int) ($row['organisatie_id'] ?? 0);
        }

        return $ids;
    }

    public function replaceOrganisatiesForVraag(int $verdiepingsvraagId, array $organisatieIds): void
    {
        execSQL(
            'DELETE FROM gids_verdieping_koppeltabel WHERE verdiepingsvraag_id = ?',
            ['i', $verdiepingsvraagId],
            true
        );

        foreach ($organisatieIds as $organisatieId) {
            execSQL(
                'INSERT INTO gids_verdieping_koppeltabel (verdiepingsvraag_id, organisatie_id) VALUES (?, ?)',
                ['ii', $verdiepingsvraagId, (int) $organisatieId],
                true
            );
        }
    }

    public function deleteLink(int $verdiepingsvraagId, int $organisatieId): int
    {
        return (int) execSQL(
            'DELETE FROM gids_verdieping_koppeltabel WHERE verdiepingsvraag_id = ? AND organisatie_id = ?',
            ['ii', $verdiepingsvraagId, $organisatieId],
            true
        );
    }

    protected function mapRow(array $row): VerdiepingKoppelingDTO
    {
        return new VerdiepingKoppelingDTO(
            (int) ($row['verdiepingsvraag_id'] ?? 0),
            (int) ($row['organisatie_id'] ?? 0)
        );
    }
}