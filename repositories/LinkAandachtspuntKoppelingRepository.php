<?php

class LinkAandachtspuntKoppelingRepository
{
    public function getDetailedRows(): array
    {
        $result = execSQL(
            'SELECT 
                ak.id,
                ak.link_id,
                l.titel,
                l.url,
                ak.aandachtspunt_id,
                a.aandachtspunt,
                f.naam_functie,
                lg.naam_leefgebied
            FROM gids_aandachtspunt_koppeltabel ak
            INNER JOIN gids_links l ON l.link_id = ak.link_id
            INNER JOIN gids_aandachtspunt a ON a.aandachtspunt_id = ak.aandachtspunt_id
            INNER JOIN gids_functie f ON f.functie_id = a.functie_id
            INNER JOIN gids_leefgebied lg ON lg.leefgebied_id = f.leefgebied_id
            ORDER BY l.titel ASC, lg.sort_order ASC, f.sort_order ASC, a.sort_order ASC',
            [],
            false
        );

        if (!$result) {
            return [];
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = [
                'id' => (int) ($row['id'] ?? 0),
                'link_id' => (int) ($row['link_id'] ?? 0),
                'link_title' => (string) ($row['titel'] ?? ''),
                'link_url' => (string) ($row['url'] ?? ''),
                'aandachtspunt_id' => (int) ($row['aandachtspunt_id'] ?? 0),
                'aandachtspunt' => (string) ($row['aandachtspunt'] ?? ''),
                'functie_name' => (string) ($row['naam_functie'] ?? ''),
                'leefgebied_name' => (string) ($row['naam_leefgebied'] ?? ''),
            ];
        }

        return $rows;
    }

    public function getAandachtspuntIdsForLink(int $linkId): array
    {
        $result = execSQL(
            'SELECT aandachtspunt_id FROM gids_aandachtspunt_koppeltabel WHERE link_id = ? ORDER BY aandachtspunt_id ASC',
            ['i', $linkId],
            false
        );

        if (!$result) {
            return [];
        }

        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = (int) ($row['aandachtspunt_id'] ?? 0);
        }

        return $ids;
    }

    public function replaceAandachtspuntenForLink(int $linkId, array $aandachtspuntIds): void
    {
        execSQL(
            'DELETE FROM gids_aandachtspunt_koppeltabel WHERE link_id = ?',
            ['i', $linkId],
            true
        );

        foreach ($aandachtspuntIds as $aandachtspuntId) {
            execSQL(
                'INSERT INTO gids_aandachtspunt_koppeltabel (link_id, aandachtspunt_id) VALUES (?, ?)',
                ['ii', $linkId, (int) $aandachtspuntId],
                true
            );
        }
    }

    public function deleteLink(int $linkId, int $aandachtspuntId): int
    {
        return (int) execSQL(
            'DELETE FROM gids_aandachtspunt_koppeltabel WHERE link_id = ? AND aandachtspunt_id = ?',
            ['ii', $linkId, $aandachtspuntId],
            true
        );
    }
}
