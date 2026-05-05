<?php

class HulpbronService extends BaseService
{
    public function __construct(
        private readonly HulpbronRepository $repository = new HulpbronRepository(),
        private readonly LeefgebiedRepository $leefgebiedRepository = new LeefgebiedRepository()
    )
    {
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

    public function getIndexItems(): array
    {
        $items = $this->repository->getAll();

        return array_map(function (HulpbronDTO $item): array {
            $id = $item->id;
            $name = $item->name;
            $description = $item->description;

            return [
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'search' => strtolower(trim($id . ' ' . $name . ' ' . ($description ?? ''))),
                'edit_url' => appUrl('hulpbron-edit') . '?id=' . $id,
            ];
        }, $items);
    }

    public function getById(int $id): ?HulpbronDTO
    {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->findById($id);
    }

    public function getFormValues(?HulpbronDTO $item): array
    {
        $selectedLeefgebieden = [];
        if ($item !== null) {
            $assigned = $this->repository->getLeefgebiedenForHulpbron($item->id);
            $selectedLeefgebieden = array_map(static fn($lg) => $lg['LeefgebiedID'], $assigned);
        }

        return [
            'HulpbronID' => old('HulpbronID', $item !== null ? (string) $item->id : ''),
            'Hulpbron' => old('Hulpbron', $item !== null ? $item->name : ''),
            'Toelichting' => old('Toelichting', $item !== null ? ($item->description ?? '') : ''),
            'selected_leefgebieden' => old('selected_leefgebieden', $selectedLeefgebieden),
        ];
    }

    public function save(array $input): array
    {
        $id = (int) ($input['HulpbronID'] ?? 0);
        $name = trim((string) ($input['Hulpbron'] ?? ''));
        $description = trim((string) ($input['Toelichting'] ?? '')) ?: null;
        $leefgebiedenRaw = $input['selected_leefgebieden'] ?? [];

        // Ensure it's an array
        if (!is_array($leefgebiedenRaw)) {
            $leefgebiedenRaw = [];
        }

        $leefgebieden = array_filter(array_map(static function ($val): ?int {
            $num = (int) $val;
            return $num > 0 ? $num : null;
        }, $leefgebiedenRaw));

        rememberInput([
            'HulpbronID' => (string) $id,
            'Hulpbron' => $name,
            'Toelichting' => $description ?? '',
            'selected_leefgebieden' => array_values($leefgebieden),
        ]);

        if ($name === '') {
            return $this->error('hulpbronnen_form_error', 'Hulpbron naam is verplicht.', appUrl('hulpbron-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if (count($leefgebieden) === 0) {
            return $this->error('hulpbronnen_form_error', 'Selecteer minstens één leefgebied.', appUrl('hulpbron-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        // Validate that all selected leefgebieden exist
        $allLeefgebieden = $this->leefgebiedRepository->getAll();
        $validIds = array_map(static fn(LeefgebiedDTO $lg) => $lg->id, $allLeefgebieden);

        foreach ($leefgebieden as $leefgebiedId) {
            if (!in_array($leefgebiedId, $validIds, true)) {
                return $this->error('hulpbronnen_form_error', 'Één of meer geselecteerde leefgebieden bestaan niet.', appUrl('hulpbron-edit') . ($id > 0 ? '?id=' . $id : ''));
            }
        }

        if ($id > 0) {
            $existing = $this->repository->findById($id);
            if ($existing === null) {
                clearOldInput();
                return $this->error('hulpbronnen_error', 'Hulpbron niet gevonden.', appUrl('hulpbronnen'));
            }

            $this->repository->update($id, $name, $description);
            $this->repository->assignToLeefgebieden($id, $leefgebieden);
            clearOldInput();

            return $this->success('hulpbronnen_success', 'Hulpbron succesvol bijgewerkt.', appUrl('hulpbronnen'));
        }

        $newId = $this->repository->create($name, $description);
        $this->repository->assignToLeefgebieden($newId, $leefgebieden);
        clearOldInput();

        return $this->success('hulpbronnen_success', 'Hulpbron succesvol toegevoegd (ID: ' . $newId . ').', appUrl('hulpbronnen'));
    }

    public function delete(array $input): array
    {
        $id = (int) ($input['HulpbronID'] ?? 0);

        if ($id <= 0) {
            return $this->error('hulpbronnen_error', 'Ongeldige hulpbron geselecteerd.', appUrl('hulpbronnen'));
        }

        $existing = $this->repository->findById($id);
        if ($existing === null) {
            return $this->error('hulpbronnen_error', 'Hulpbron niet gevonden.', appUrl('hulpbronnen'));
        }

        $affectedRows = $this->repository->delete($id);

        if ($affectedRows < 1) {
            return $this->error('hulpbronnen_error', 'Hulpbron kon niet worden verwijderd.', appUrl('hulpbronnen'));
        }

        return $this->success('hulpbronnen_success', 'Hulpbron succesvol verwijderd.', appUrl('hulpbronnen'));
    }
}
