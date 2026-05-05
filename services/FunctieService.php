<?php

class FunctieService extends BaseService
{
    public function __construct(
        private readonly FunctieRepository $repository = new FunctieRepository(),
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

        $leefgebieden = $this->leefgebiedRepository->getAll();
        $leefgebiedMap = [];
        foreach ($leefgebieden as $l) {
            $leefgebiedMap[$l->id] = $l->name;
        }

        return array_map(function (FunctieDTO $item) use ($leefgebiedMap): array {
            $id = $item->id;
            $leefgebiedId = $item->leefgebiedId;
            $leefgebiedName = $leefgebiedMap[$leefgebiedId] ?? '';
            $name = $item->name;
            $description = $item->description;
            $sortOrder = $item->sortOrder;

            return [
                'id' => $id,
                'leefgebied_id' => $leefgebiedId,
                'leefgebied' => $leefgebiedName,
                'name' => $name,
                'description' => $description,
                'sort_order' => $sortOrder,
                'search' => strtolower(trim($id . ' ' . $leefgebiedName . ' ' . $name . ' ' . $description . ' ' . $sortOrder)),
                'edit_url' => appUrl('functie-edit') . '?id=' . $id,
            ];
        }, $items);
    }

    public function getById(int $id): ?FunctieDTO
    {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->findById($id);
    }

    public function getFormValues(?FunctieDTO $item): array
    {
        return [
            'FunctieID' => old('FunctieID', $item !== null ? (string) $item->id : ''),
            'LeefgebiedID' => old('LeefgebiedID', $item !== null ? (string) $item->leefgebiedId : ''),
            'Naam_functie' => old('Naam_functie', $item !== null ? $item->name : ''),
            'Beschrijving_functie' => old('Beschrijving_functie', $item !== null ? $item->description : ''),
            'Sort_order' => old('Sort_order', $item !== null ? (string) $item->sortOrder : '0'),
        ];
    }

    public function save(array $input): array
    {
        $id = (int) ($input['FunctieID'] ?? 0);
        $leefgebiedIdRaw = trim((string) ($input['LeefgebiedID'] ?? ''));
        $name = trim((string) ($input['Naam_functie'] ?? ''));
        $description = trim((string) ($input['Beschrijving_functie'] ?? ''));
        $sortOrderRaw = trim((string) ($input['Sort_order'] ?? '0'));

        $leefgebiedId = is_numeric($leefgebiedIdRaw) ? (int) $leefgebiedIdRaw : 0;
        $sortOrder = is_numeric($sortOrderRaw) ? (int) $sortOrderRaw : 0;

        rememberInput([
            'FunctieID' => (string) $id,
            'LeefgebiedID' => (string) $leefgebiedId,
            'Naam_functie' => $name,
            'Beschrijving_functie' => $description,
            'Sort_order' => (string) $sortOrder,
        ]);

        if ($leefgebiedId <= 0) {
            return $this->error('functies_form_error', 'Selecteer een leefgebied.', appUrl('functie-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($this->leefgebiedRepository->findById($leefgebiedId) === null) {
            return $this->error('functies_form_error', 'Geselecteerd leefgebied bestaat niet.', appUrl('functie-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($name === '') {
            return $this->error('functies_form_error', 'Naam functie is verplicht.', appUrl('functie-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($id > 0) {
            $existing = $this->repository->findById($id);
            if ($existing === null) {
                clearOldInput();

                return $this->error('functies_error', 'Functie niet gevonden.', appUrl('functies'));
            }

            $this->repository->update($id, $leefgebiedId, $name, $description, $sortOrder);
            clearOldInput();

            return $this->success('functies_success', 'Functie succesvol bijgewerkt.', appUrl('functies'));
        }

        $newId = $this->repository->create($leefgebiedId, $name, $description, $sortOrder);
        clearOldInput();

        return $this->success('functies_success', 'Functie succesvol toegevoegd (ID: ' . $newId . ').', appUrl('functies'));
    }

    public function delete(array $input): array
    {
        $id = (int) ($input['FunctieID'] ?? 0);

        if ($id <= 0) {
            return $this->error('functies_error', 'Ongeldige functie geselecteerd.', appUrl('functies'));
        }

        $existing = $this->repository->findById($id);
        if ($existing === null) {
            return $this->error('functies_error', 'Functie niet gevonden.', appUrl('functies'));
        }

        $affectedRows = $this->repository->delete($id);

        if ($affectedRows < 1) {
            return $this->error('functies_error', 'Functie kon niet worden verwijderd.', appUrl('functies'));
        }

        return $this->success('functies_success', 'Functie succesvol verwijderd.', appUrl('functies'));
    }
}