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
        $rows = $this->leefgebiedRepository->getAll();

        return array_map(static function (array $row): array {
            return [
                'id' => (int) ($row['id'] ?? 0),
                'name' => (string) ($row['name'] ?? ''),
            ];
        }, $rows);
    }

    public function getIndexItems(): array
    {
        $rows = $this->repository->getAll();

        return array_map(function (array $row): array {
            $id = (int) ($row['id'] ?? 0);
            $leefgebiedId = (int) ($row['leefgebied_id'] ?? 0);
            $name = (string) ($row['name'] ?? '');
            $description = (string) ($row['description'] ?? '');
            $sortOrder = (int) ($row['sort_order'] ?? 0);

            return [
                'id' => $id,
                'leefgebied_id' => $leefgebiedId,
                'name' => $name,
                'description' => $description,
                'sort_order' => $sortOrder,
                'search' => strtolower(trim($id . ' ' . $leefgebiedId . ' ' . $name . ' ' . $description . ' ' . $sortOrder)),
                'edit_url' => appUrl('functie-edit') . '?id=' . $id,
            ];
        }, $rows);
    }

    public function getById(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->findById($id);
    }

    public function getFormValues(?array $item): array
    {
        return [
            'FunctieID' => old('FunctieID', $item !== null ? (string) $item['id'] : ''),
            'LeefgebiedID' => old('LeefgebiedID', $item !== null ? (string) $item['leefgebied_id'] : ''),
            'Naam_functie' => old('Naam_functie', $item !== null ? (string) $item['name'] : ''),
            'Beschrijving_functie' => old('Beschrijving_functie', $item !== null ? (string) $item['description'] : ''),
            'Sort_order' => old('Sort_order', $item !== null ? (string) $item['sort_order'] : '0'),
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