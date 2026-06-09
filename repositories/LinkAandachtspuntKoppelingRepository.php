<?php

class LinkAandachtspuntKoppelingRepository
{
    public function getDetailedRows(): array
    {
        $result = execSQL(
            'SELECT 
                ak.id,
                ak.LinkID,
                l.titel,
                l.url,
                ak.AandachtspuntID,
                a.Aandachtspunt,
                f.Naam_functie,
                lg.Naam_leefgebied
            FROM gids_aandachtspunt_koppeltabel ak
            INNER JOIN gids_links l ON l.LinkID = ak.LinkID
            INNER JOIN gids_aandachtspunt a ON a.AandachtspuntID = ak.AandachtspuntID
            INNER JOIN gids_functie f ON f.FunctieID = a.FunctieID
            INNER JOIN gids_leefgebied lg ON lg.LeefgebiedID = f.LeefgebiedID
            ORDER BY l.titel ASC, lg.Sort_order ASC, f.Sort_order ASC, a.Sort_order ASC',
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
                'link_id' => (int) ($row['LinkID'] ?? 0),
                'link_title' => (string) ($row['titel'] ?? ''),
                'link_url' => (string) ($row['url'] ?? ''),
                'aandachtspunt_id' => (int) ($row['AandachtspuntID'] ?? 0),
                'aandachtspunt' => (string) ($row['Aandachtspunt'] ?? ''),
                'functie_name' => (string) ($row['Naam_functie'] ?? ''),
                'leefgebied_name' => (string) ($row['Naam_leefgebied'] ?? ''),
            ];
        }

        return $rows;
    }

    public function getAandachtspuntIdsForLink(int $linkId): array
    {
        $result = execSQL(
            'SELECT AandachtspuntID FROM gids_aandachtspunt_koppeltabel WHERE LinkID = ? ORDER BY AandachtspuntID ASC',
            ['i', $linkId],
            false
        );

        if (!$result) {
            return [];
        }

        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = (int) ($row['AandachtspuntID'] ?? 0);
        }

        return $ids;
    }

    public function replaceAandachtspuntenForLink(int $linkId, array $aandachtspuntIds): void
    {
        execSQL(
            'DELETE FROM gids_aandachtspunt_koppeltabel WHERE LinkID = ?',
            ['i', $linkId],
            true
        );

        foreach ($aandachtspuntIds as $aandachtspuntId) {
            execSQL(
                'INSERT INTO gids_aandachtspunt_koppeltabel (LinkID, AandachtspuntID) VALUES (?, ?)',
                ['ii', $linkId, (int) $aandachtspuntId],
                true
            );
        }
    }

    public function deleteLink(int $linkId, int $aandachtspuntId): int
    {
        return (int) execSQL(
            'DELETE FROM gids_aandachtspunt_koppeltabel WHERE LinkID = ? AND AandachtspuntID = ?',
            ['ii', $linkId, $aandachtspuntId],
            true
        );
    }
}
