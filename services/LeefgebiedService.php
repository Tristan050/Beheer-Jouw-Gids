<?php

class LeefgebiedService
{
    public function __construct(private readonly LeefgebiedRepository $repository = new LeefgebiedRepository())
    {
    }

    public function getIndexItems(): array
    {
        $rows = $this->repository->getAll();

        return array_map(function (array $row): array {
            $id = (int) ($row['id'] ?? 0);
            $name = (string) ($row['name'] ?? '');
            $description = (string) ($row['description'] ?? '');
            $sortOrder = (int) ($row['sort_order'] ?? 0);

            return [
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'sort_order' => $sortOrder,
                'search' => strtolower(trim($id . ' ' . $name . ' ' . $description . ' ' . $sortOrder)),
                'edit_url' => appUrl('leefgebied-edit') . '?id=' . $id,
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
            'LeefgebiedID' => old('LeefgebiedID', $item !== null ? (string) $item['id'] : ''),
            'Naam_leefgebied' => old('Naam_leefgebied', $item !== null ? (string) $item['name'] : ''),
            'beschrijving_leefgebied' => old('beschrijving_leefgebied', $item !== null ? (string) $item['description'] : ''),
            'Sort_order' => old('Sort_order', $item !== null ? (string) $item['sort_order'] : '0'),
        ];
    }

    public function save(array $input): array
    {
        $id = (int) ($input['LeefgebiedID'] ?? 0);
        $name = trim((string) ($input['Naam_leefgebied'] ?? ''));
        $description = trim((string) ($input['beschrijving_leefgebied'] ?? ''));
        $sortOrderRaw = trim((string) ($input['Sort_order'] ?? '0'));
        $sortOrder = is_numeric($sortOrderRaw) ? (int) $sortOrderRaw : 0;

        rememberInput([
            'LeefgebiedID' => (string) $id,
            'Naam_leefgebied' => $name,
            'beschrijving_leefgebied' => $description,
            'Sort_order' => (string) $sortOrder,
        ]);

        if ($name === '') {
            return [
                'ok' => false,
                'flash_key' => 'leefgebieden_form_error',
                'message' => 'Naam leefgebied is verplicht.',
                'redirect' => appUrl('leefgebied-edit') . ($id > 0 ? '?id=' . $id : ''),
            ];
        }

        if ($id > 0) {
            $existing = $this->repository->findById($id);
            if ($existing === null) {
                clearOldInput();

                return [
                    'ok' => false,
                    'flash_key' => 'leefgebieden_error',
                    'message' => 'Leefgebied niet gevonden.',
                    'redirect' => appUrl('leefgebieden'),
                ];
            }

            $this->repository->update($id, $name, $description, $sortOrder);
            clearOldInput();

            return [
                'ok' => true,
                'flash_key' => 'leefgebieden_success',
                'message' => 'Leefgebied succesvol bijgewerkt.',
                'redirect' => appUrl('leefgebieden'),
            ];
        }

        $newId = $this->repository->create($name, $description, $sortOrder);
        clearOldInput();

        return [
            'ok' => true,
            'flash_key' => 'leefgebieden_success',
            'message' => 'Leefgebied succesvol toegevoegd (ID: ' . $newId . ').',
            'redirect' => appUrl('leefgebieden'),
        ];
    }
}