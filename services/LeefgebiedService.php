<?php

class LeefgebiedService extends BaseService
{
    public function __construct(private readonly LeefgebiedRepository $repository = new LeefgebiedRepository())
    {
    }

    public function getIndexItems(): array
    {
        $items = $this->repository->getAll();

        return array_map(function (LeefgebiedDTO $dto): array {

            return [
                'id' => $dto->id,
                'name' => $dto->name,
                'description' => $dto->description,
                'sort_order' => $dto->sortOrder,
                'search' => strtolower(trim($dto->id . ' ' . $dto->name . ' ' . $dto->description . ' ' . $dto->sortOrder)),
                'edit_url' => appUrl('leefgebied-edit') . '?id=' . $dto->id,
            ];
        }, $items);
    }

    public function getById(int $id): ?LeefgebiedDTO
    {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->findById($id);
    }

    public function getFormValues(?LeefgebiedDTO $item): array
    {
        return [
            'LeefgebiedID' => old('LeefgebiedID', $item !== null ? (string) $item->id : ''),
            'Naam_leefgebied' => old('Naam_leefgebied', $item !== null ? $item->name : ''),
            'beschrijving_leefgebied' => old('beschrijving_leefgebied', $item !== null ? $item->description : ''),
            'Sort_order' => old('Sort_order', $item !== null ? (string) $item->sortOrder : '0'),
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
            return $this->error('leefgebieden_form_error', 'Naam leefgebied is verplicht.', appUrl('leefgebied-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($id > 0) {
            $existing = $this->getById($id);
            if ($existing === null) {
                clearOldInput();

                return $this->error('leefgebieden_error', 'Leefgebied niet gevonden.', appUrl('leefgebieden'));
            }

            $this->repository->update($id, $name, $description, $sortOrder);
            clearOldInput();

            return $this->success('leefgebieden_success', 'Leefgebied succesvol bijgewerkt.', appUrl('leefgebieden'));
        }

        $newId = $this->repository->create($name, $description, $sortOrder);
        clearOldInput();

        return $this->success('leefgebieden_success', 'Leefgebied succesvol toegevoegd (ID: ' . $newId . ').', appUrl('leefgebieden'));
    }

    public function delete(array $input): array
    {
        $id = (int) ($input['LeefgebiedID'] ?? 0);

        if ($id <= 0) {
            return $this->error('leefgebieden_error', 'Ongeldig leefgebied geselecteerd.', appUrl('leefgebieden'));
        }

        $existing = $this->repository->findById($id);
        if ($existing === null) {
            return $this->error('leefgebieden_error', 'Leefgebied niet gevonden.', appUrl('leefgebieden'));
        }

        $affectedRows = $this->repository->delete($id);

        if ($affectedRows < 1) {
            return $this->error('leefgebieden_error', 'Leefgebied kon niet worden verwijderd.', appUrl('leefgebieden'));
        }

        return $this->success('leefgebieden_success', 'Leefgebied succesvol verwijderd.', appUrl('leefgebieden'));
    }
}