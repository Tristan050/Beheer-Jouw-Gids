<?php

class LeefgebiedHulpbronKoppelingRepository
{
    public function getDetailedRows(): array
    {
        $result = execSQL(
            'SELECT 
                lhb.LeefgebiedID, 
                lhb.HulpbronID,
                lg.Naam_leefgebied,
                hb.Hulpbron,
                lhb.Sort_order
            FROM gids_leefgebied_hulpbron lhb
            JOIN gids_leefgebied lg ON lg.LeefgebiedID = lhb.LeefgebiedID
            JOIN gids_hulpbron hb ON hb.HulpbronID = lhb.HulpbronID
            ORDER BY lg.Naam_leefgebied, lhb.Sort_order',
            [],
            false
        );

        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = [
                    'leefgebied_id' => (int) ($row['LeefgebiedID'] ?? 0),
                    'hulpbron_id' => (int) ($row['HulpbronID'] ?? 0),
                    'leefgebied_name' => (string) ($row['Naam_leefgebied'] ?? ''),
                    'hulpbron_name' => (string) ($row['Hulpbron'] ?? ''),
                    'sort_order' => (int) ($row['Sort_order'] ?? 0),
                ];
            }
        }

        return $rows;
    }

    public function getHulpbronIdsForLeefgebied(int $leefgebiedId): array
    {
        $result = execSQL(
            'SELECT HulpbronID FROM gids_leefgebied_hulpbron WHERE LeefgebiedID = ? ORDER BY Sort_order',
            ['i', $leefgebiedId],
            false
        );

        $hulpbronIds = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $hulpbronIds[] = (int) ($row['HulpbronID'] ?? 0);
            }
        }

        return $hulpbronIds;
    }

    public function replaceHulpbronnenForLeefgebied(int $leefgebiedId, array $hulpbronIds): void
    {
        // Verwijder bestaande koppelingen
        execSQL(
            'DELETE FROM gids_leefgebied_hulpbron WHERE LeefgebiedID = ?',
            ['i', $leefgebiedId],
            true
        );

        // Voeg nieuwe koppelingen toe
        foreach ($hulpbronIds as $index => $hulpbronId) {
            execSQL(
                'INSERT INTO gids_leefgebied_hulpbron (LeefgebiedID, HulpbronID, Sort_order) VALUES (?, ?, ?)',
                ['iii', $leefgebiedId, (int) $hulpbronId, $index + 1],
                true
            );
        }
    }

    public function deleteLink(int $leefgebiedId, int $hulpbronId): int
    {
        return (int) execSQL(
            'DELETE FROM gids_leefgebied_hulpbron WHERE LeefgebiedID = ? AND HulpbronID = ?',
            ['ii', $leefgebiedId, $hulpbronId],
            true
        );
    }
}
