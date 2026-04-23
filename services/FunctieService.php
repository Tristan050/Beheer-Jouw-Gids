<?php

class FunctieService
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

        return array_map(function (FunctieDTO $item): array {
            $id = $item->id;
            $leefgebiedId = $item->leefgebiedId;
            $name = $item->name;
            $description = $item->description;
            $sortOrder = $item->sortOrder;

            return [
                'id' => $id,
                'leefgebied_id' => $leefgebiedId,
                'name' => $name,
                'description' => $description,
                'sort_order' => $sortOrder,
                'search' => strtolower(trim($id . ' ' . $leefgebiedId . ' ' . $name . ' ' . $description . ' ' . $sortOrder)),
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
            return [
                'ok' => false,
                'flash_key' => 'functies_form_error',
                'message' => 'Selecteer een leefgebied.',
                'redirect' => appUrl('functie-edit') . ($id > 0 ? '?id=' . $id : ''),
            ];
        }

        if ($this->leefgebiedRepository->findById($leefgebiedId) === null) {
            return [
                'ok' => false,
                'flash_key' => 'functies_form_error',
                'message' => 'Geselecteerd leefgebied bestaat niet.',
                'redirect' => appUrl('functie-edit') . ($id > 0 ? '?id=' . $id : ''),
            ];
        }

        if ($name === '') {
            return [
                'ok' => false,
                'flash_key' => 'functies_form_error',
                'message' => 'Naam functie is verplicht.',
                'redirect' => appUrl('functie-edit') . ($id > 0 ? '?id=' . $id : ''),
            ];
        }

        if ($id > 0) {
            $existing = $this->repository->findById($id);
            if ($existing === null) {
                clearOldInput();

                return [
                    'ok' => false,
                    'flash_key' => 'functies_error',
                    'message' => 'Functie niet gevonden.',
                    'redirect' => appUrl('functies'),
                ];
            }

            $this->repository->update($id, $leefgebiedId, $name, $description, $sortOrder);
            clearOldInput();

            return [
                'ok' => true,
                'flash_key' => 'functies_success',
                'message' => 'Functie succesvol bijgewerkt.',
                'redirect' => appUrl('functies'),
            ];
        }

        $newId = $this->repository->create($leefgebiedId, $name, $description, $sortOrder);
        clearOldInput();

        return [
            'ok' => true,
            'flash_key' => 'functies_success',
            'message' => 'Functie succesvol toegevoegd (ID: ' . $newId . ').',
            'redirect' => appUrl('functies'),
        ];
    }

    public function delete(array $input): array
    {
        $id = (int) ($input['FunctieID'] ?? 0);

        if ($id <= 0) {
            return [
                'ok' => false,
                'flash_key' => 'functies_error',
                'message' => 'Ongeldige functie geselecteerd.',
                'redirect' => appUrl('functies'),
            ];
        }

        $existing = $this->repository->findById($id);
        if ($existing === null) {
            return [
                'ok' => false,
                'flash_key' => 'functies_error',
                'message' => 'Functie niet gevonden.',
                'redirect' => appUrl('functies'),
            ];
        }

        $affectedRows = $this->repository->delete($id);

        if ($affectedRows < 1) {
            return [
                'ok' => false,
                'flash_key' => 'functies_error',
                'message' => 'Functie kon niet worden verwijderd.',
                'redirect' => appUrl('functies'),
            ];
        }

        return [
            'ok' => true,
            'flash_key' => 'functies_success',
            'message' => 'Functie succesvol verwijderd.',
            'redirect' => appUrl('functies'),
        ];
    }
}