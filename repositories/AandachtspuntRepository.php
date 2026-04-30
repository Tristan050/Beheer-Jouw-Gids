<?php

class AandachtspuntRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_aandachtspunt';
    }

    protected function idColumn(): string
    {
        return 'AandachtspuntID';
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
            'INSERT INTO gids_aandachtspunt (FunctieID, Sort_order, Aandachtspunt, Toelichting, Scan_tekst, Advies_tekst) VALUES (?, ?, ?, ?, ?, ?)',
            ['iissss', $functieId, $sortOrder, $aandachtspunt, $toelichting, $scanTekst, $adviesTekst],
            true
        );
    }

    public function update(int $id, int $functieId, int $sortOrder, string $aandachtspunt, string $toelichting, string $scanTekst, string $adviesTekst): int
    {
        return (int) execSQL(
            'UPDATE gids_aandachtspunt SET FunctieID = ?, Sort_order = ?, Aandachtspunt = ?, Toelichting = ?, Scan_tekst = ?, Advies_tekst = ? WHERE AandachtspuntID = ?',
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
            (int) ($row['AandachtspuntID'] ?? 0),
            (int) ($row['FunctieID'] ?? 0),
            (int) ($row['Sort_order'] ?? 0),
            (string) ($row['Aandachtspunt'] ?? ''),
            (string) ($row['Toelichting'] ?? ''),
            (string) ($row['Scan_tekst'] ?? ''),
            (string) ($row['Advies_tekst'] ?? '')
        );
    }
}