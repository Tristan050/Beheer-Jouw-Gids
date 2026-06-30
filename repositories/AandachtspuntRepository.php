<?php

class AandachtspuntRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_aandachtspunt';
    }

    protected function idColumn(): string
    {
        return 'aandachtspunt_id';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function findById(int $id): ?AandachtspuntDTO
    {
        return $this->getRowById($id);
    }

    public function create(int $functieId, int $sortOrder, string $aandachtspunt, string $toelichting, string $scanTekst, string $adviesTekst): int
    {
        return (int) execSQL(
            'INSERT INTO gids_aandachtspunt (functie_id, sort_order, aandachtspunt, toelichting, scan_tekst, advies_tekst) VALUES (?, ?, ?, ?, ?, ?)',
            ['iissss', $functieId, $sortOrder, $aandachtspunt, $toelichting, $scanTekst, $adviesTekst],
            true
        );
    }

    public function update(int $id, int $functieId, int $sortOrder, string $aandachtspunt, string $toelichting, string $scanTekst, string $adviesTekst): int
    {
        return (int) execSQL(
            'UPDATE gids_aandachtspunt SET functie_id = ?, sort_order = ?, aandachtspunt = ?, toelichting = ?, scan_tekst = ?, advies_tekst = ? WHERE aandachtspunt_id = ?',
            ['iissssi', $functieId, $sortOrder, $aandachtspunt, $toelichting, $scanTekst, $adviesTekst, $id],
            true
        );
    }

    public function delete(int $id): int
    {
        return $this->deleteRowById($id);
    }

    protected function mapRow(array $row): AandachtspuntDTO
    {
        return new AandachtspuntDTO(
            (int) ($row['aandachtspunt_id'] ?? 0),
            (int) ($row['functie_id'] ?? 0),
            (int) ($row['sort_order'] ?? 0),
            (string) ($row['aandachtspunt'] ?? ''),
            (string) ($row['toelichting'] ?? ''),
            (string) ($row['scan_tekst'] ?? ''),
            (string) ($row['advies_tekst'] ?? '')
        );
    }
}