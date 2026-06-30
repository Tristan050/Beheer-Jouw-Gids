<?php

class LeefgebiedHulpbronKoppelingRepository
{
    public function getDetailedRows(): array
    {
        $result = execSQL(
            'SELECT 
                lhb.leefgebied_id,
                lhb.hulpbron_id,
                lg.naam_leefgebied,
                hb.hulpbron,
                lhb.sort_order
            FROM gids_leefgebied_hulpbron lhb
            JOIN gids_leefgebied lg ON lg.leefgebied_id = lhb.leefgebied_id
            JOIN gids_hulpbron hb ON hb.hulpbron_id = lhb.hulpbron_id
            ORDER BY lg.naam_leefgebied, lhb.sort_order',
            [],
            false
        );

        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = [
                    'leefgebied_id' => (int) ($row['leefgebied_id'] ?? 0),
                    'hulpbron_id' => (int) ($row['hulpbron_id'] ?? 0),
                    'leefgebied_name' => (string) ($row['naam_leefgebied'] ?? ''),
                    'hulpbron_name' => (string) ($row['hulpbron'] ?? ''),
                    'sort_order' => (int) ($row['sort_order'] ?? 0),
                ];
            }
        }

        return $rows;
    }

    public function getHulpbronIdsForLeefgebied(int $leefgebiedId): array
    {
        $result = execSQL(
            'SELECT hulpbron_id FROM gids_leefgebied_hulpbron WHERE leefgebied_id = ? ORDER BY sort_order',
            ['i', $leefgebiedId],
            false
        );

        $hulpbronIds = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $hulpbronIds[] = (int) ($row['hulpbron_id'] ?? 0);
            }
        }

        return $hulpbronIds;
    }

    public function replaceHulpbronnenForLeefgebied(int $leefgebiedId, array $hulpbronIds): void
    {
        // Verwijder bestaande koppelingen
        execSQL(
            'DELETE FROM gids_leefgebied_hulpbron WHERE leefgebied_id = ?',
            ['i', $leefgebiedId],
            true
        );

        // Voeg nieuwe koppelingen toe
        foreach ($hulpbronIds as $index => $hulpbronId) {
            execSQL(
                'INSERT INTO gids_leefgebied_hulpbron (leefgebied_id, hulpbron_id, sort_order) VALUES (?, ?, ?)',
                ['iii', $leefgebiedId, (int) $hulpbronId, $index + 1],
                true
            );
        }
    }

    public function deleteLink(int $leefgebiedId, int $hulpbronId): int
    {
        return (int) execSQL(
            'DELETE FROM gids_leefgebied_hulpbron WHERE leefgebied_id = ? AND hulpbron_id = ?',
            ['ii', $leefgebiedId, $hulpbronId],
            true
        );
    }
}
