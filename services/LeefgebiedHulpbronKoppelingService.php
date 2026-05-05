<?php

class LeefgebiedHulpbronKoppelingService extends BaseService
{
    public function __construct(
        private readonly LeefgebiedHulpbronKoppelingRepository $repository = new LeefgebiedHulpbronKoppelingRepository(),
        private readonly LeefgebiedRepository $leefgebiedRepository = new LeefgebiedRepository(),
        private readonly HulpbronRepository $hulpbronRepository = new HulpbronRepository()
    ) {
    }

    public function getLeefgebiedOptions(): array
    {
        $items = $this->leefgebiedRepository->getAll();

        return array_map(static function (LeefgebiedDTO $item): array {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        }, $items);
    }

    public function getHulpbronOptions(): array
    {
        $items = $this->hulpbronRepository->getAll();

        return array_map(static function (HulpbronDTO $item): array {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        }, $items);
    }

    public function getIndexItems(): array
    {
        $rows = $this->repository->getDetailedRows();

        return array_map(function (array $row): array {
            $leefgebiedId = (int) ($row['leefgebied_id'] ?? 0);
            $hulpbronId = (int) ($row['hulpbron_id'] ?? 0);
            $leefgebiedName = (string) ($row['leefgebied_name'] ?? '');
            $hulpbronName = (string) ($row['hulpbron_name'] ?? '');

            return [
                'leefgebied_id' => $leefgebiedId,
                'hulpbron_id' => $hulpbronId,
                'leefgebied_name' => $leefgebiedName,
                'hulpbron_name' => $hulpbronName,
                'search' => strtolower(trim($leefgebiedId . ' ' . $hulpbronId . ' ' . $leefgebiedName . ' ' . $hulpbronName)),
            ];
        }, $rows);
    }

    public function getFormValues(int $selectedLeefgebiedId = 0): array
    {
        $selectedLeefgebied = (int) old('LeefgebiedID', (string) $selectedLeefgebiedId);
        $fromOld = $_SESSION['old_input']['HulpbronIDs'] ?? null;

        if (is_array($fromOld)) {
            $selectedHulpbronIds = array_values(array_unique(array_map('intval', $fromOld)));
        } else {
            $selectedHulpbronIds = $selectedLeefgebied > 0
                ? $this->repository->getHulpbronIdsForLeefgebied($selectedLeefgebied)
                : [];
        }

        return [
            'LeefgebiedID' => (string) $selectedLeefgebied,
            'HulpbronIDs' => $selectedHulpbronIds,
        ];
    }

    public function save(array $input): array
    {
        $leefgebiedId = (int) ($input['LeefgebiedID'] ?? 0);
        $hulpbronIdsRaw = $input['HulpbronIDs'] ?? [];
        $hulpbronIdsRaw = is_array($hulpbronIdsRaw) ? $hulpbronIdsRaw : [];
        $hulpbronIds = [];

        foreach ($hulpbronIdsRaw as $hulpbronIdRaw) {
            if (is_numeric((string) $hulpbronIdRaw)) {
                $hulpbronId = (int) $hulpbronIdRaw;
                if ($hulpbronId > 0) {
                    $hulpbronIds[] = $hulpbronId;
                }
            }
        }

        $hulpbronIds = array_values(array_unique($hulpbronIds));

        rememberInput([
            'LeefgebiedID' => (string) $leefgebiedId,
            'HulpbronIDs' => array_map('strval', $hulpbronIds),
        ]);

        if ($leefgebiedId <= 0) {
            return $this->error('leefgebied_hulpbron_koppelingen_form_error', 'Selecteer een leefgebied.', appUrl('leefgebied-hulpbron-koppelingen'));
        }

        if ($this->leefgebiedRepository->findById($leefgebiedId) === null) {
            return $this->error('leefgebied_hulpbron_koppelingen_form_error', 'Geselecteerd leefgebied bestaat niet.', appUrl('leefgebied-hulpbron-koppelingen'));
        }

        if (count($hulpbronIds) === 0) {
            return $this->error('leefgebied_hulpbron_koppelingen_form_error', 'Selecteer minstens één hulpbron.', appUrl('leefgebied-hulpbron-koppelingen'));
        }

        $validHulpbronIds = [];
        foreach ($hulpbronIds as $hulpbronId) {
            if ($this->hulpbronRepository->findById($hulpbronId) !== null) {
                $validHulpbronIds[] = $hulpbronId;
            }
        }

        $this->repository->replaceHulpbronnenForLeefgebied($leefgebiedId, $validHulpbronIds);
        clearOldInput();

        return $this->success('leefgebied_hulpbron_koppelingen_success', 'Koppelingen succesvol opgeslagen.', appUrl('leefgebied-hulpbron-koppelingen') . '?leefgebied_id=' . $leefgebiedId);
    }

    public function delete(array $input): array
    {
        $leefgebiedId = (int) ($input['LeefgebiedID'] ?? 0);
        $hulpbronId = (int) ($input['HulpbronID'] ?? 0);

        if ($leefgebiedId <= 0 || $hulpbronId <= 0) {
            return $this->error('leefgebied_hulpbron_koppelingen_error', 'Ongeldige koppeling geselecteerd.', appUrl('leefgebied-hulpbron-koppelingen'));
        }

        $affectedRows = $this->repository->deleteLink($leefgebiedId, $hulpbronId);
        if ($affectedRows < 1) {
            return $this->error('leefgebied_hulpbron_koppelingen_error', 'Koppeling niet gevonden of al verwijderd.', appUrl('leefgebied-hulpbron-koppelingen'));
        }

        return $this->success('leefgebied_hulpbron_koppelingen_success', 'Koppeling succesvol verwijderd.', appUrl('leefgebied-hulpbron-koppelingen') . '?leefgebied_id=' . $leefgebiedId);
    }
}
