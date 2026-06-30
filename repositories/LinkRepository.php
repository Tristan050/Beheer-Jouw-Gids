<?php

class LinkRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_links';
    }

    protected function idColumn(): string
    {
        return 'link_id';
    }

    protected function orderBy(): string
    {
        return 'titel ASC, link_id ASC';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function findById(int $id): ?LinkDTO
    {
        return $this->getRowById($id);
    }

    public function create(string $title, string $url, string $importantMessage, bool $showPopup): int
    {
        return (int) execSQL(
            'INSERT INTO gids_links (titel, url, belangrijk_bericht, toon_popup) VALUES (?, ?, ?, ?)',
            ['sssi', $title, $url, $importantMessage, $showPopup ? 1 : 0],
            true
        );
    }

    public function update(int $id, string $title, string $url, string $importantMessage, bool $showPopup): int
    {
        return (int) execSQL(
            'UPDATE gids_links SET titel = ?, url = ?, belangrijk_bericht = ?, toon_popup = ? WHERE link_id = ?',
            ['sssii', $title, $url, $importantMessage, $showPopup ? 1 : 0, $id],
            true
        );
    }

    public function delete(int $id): int
    {
        return $this->deleteRowById($id);
    }

    protected function mapRow(array $row): LinkDTO
    {
        return new LinkDTO(
            (int) ($row['link_id'] ?? 0),
            (string) ($row['titel'] ?? ''),
            (string) ($row['url'] ?? ''),
            (string) ($row['belangrijk_bericht'] ?? ''),
            (bool) ((int) ($row['toon_popup'] ?? 0))
        );
    }
}
