<?php

class OrganisatieService
{
    public function __construct(private readonly OrganisatieRepository $repository = new OrganisatieRepository())
    {
    }

    public function getIndexItems(): array
    {
        $items = $this->repository->getAll();

        return array_map(function (OrganisatieDTO $item): array {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'address' => $item->address,
                'phone' => $item->phone,
                'email' => $item->email,
                'website' => $item->website,
                'search' => strtolower(trim($item->id . ' ' . $item->name . ' ' . $item->address . ' ' . $item->phone . ' ' . $item->email . ' ' . $item->website)),
                'edit_url' => appUrl('organisatie-edit') . '?id=' . $item->id,
            ];
        }, $items);
    }

    public function getById(int $id): ?OrganisatieDTO
    {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->findById($id);
    }

    public function getFormValues(?OrganisatieDTO $item): array
    {
        return [
            'OrganisatieID' => old('OrganisatieID', $item !== null ? (string) $item->id : ''),
            'Naam' => old('Naam', $item !== null ? $item->name : ''),
            'Adres' => old('Adres', $item !== null ? $item->address : ''),
            'Telefoon' => old('Telefoon', $item !== null ? $item->phone : ''),
            'Email' => old('Email', $item !== null ? $item->email : ''),
            'Website' => old('Website', $item !== null ? $item->website : ''),
        ];
    }

    public function save(array $input): array
    {
        $id = (int) ($input['OrganisatieID'] ?? 0);
        $name = trim((string) ($input['Naam'] ?? ''));
        $address = trim((string) ($input['Adres'] ?? ''));
        $phone = trim((string) ($input['Telefoon'] ?? ''));
        $email = trim((string) ($input['Email'] ?? ''));
        $website = trim((string) ($input['Website'] ?? ''));

        rememberInput([
            'OrganisatieID' => (string) $id,
            'Naam' => $name,
            'Adres' => $address,
            'Telefoon' => $phone,
            'Email' => $email,
            'Website' => $website,
        ]);

        if ($name === '') {
            return [
                'ok' => false,
                'flash_key' => 'organisaties_form_error',
                'message' => 'Naam organisatie is verplicht.',
                'redirect' => appUrl('organisatie-edit') . ($id > 0 ? '?id=' . $id : ''),
            ];
        }

        if ($id > 0) {
            $existing = $this->repository->findById($id);
            if ($existing === null) {
                clearOldInput();

                return [
                    'ok' => false,
                    'flash_key' => 'organisaties_error',
                    'message' => 'Organisatie niet gevonden.',
                    'redirect' => appUrl('organisaties'),
                ];
            }

            $this->repository->update($id, $name, $address, $phone, $email, $website);
            clearOldInput();

            return [
                'ok' => true,
                'flash_key' => 'organisaties_success',
                'message' => 'Organisatie succesvol bijgewerkt.',
                'redirect' => appUrl('organisaties'),
            ];
        }

        $newId = $this->repository->create($name, $address, $phone, $email, $website);
        clearOldInput();

        return [
            'ok' => true,
            'flash_key' => 'organisaties_success',
            'message' => 'Organisatie succesvol toegevoegd (ID: ' . $newId . ').',
            'redirect' => appUrl('organisaties'),
        ];
    }

    public function delete(array $input): array
    {
        $id = (int) ($input['OrganisatieID'] ?? 0);

        if ($id <= 0) {
            return [
                'ok' => false,
                'flash_key' => 'organisaties_error',
                'message' => 'Ongeldige organisatie geselecteerd.',
                'redirect' => appUrl('organisaties'),
            ];
        }

        $existing = $this->repository->findById($id);
        if ($existing === null) {
            return [
                'ok' => false,
                'flash_key' => 'organisaties_error',
                'message' => 'Organisatie niet gevonden.',
                'redirect' => appUrl('organisaties'),
            ];
        }

        $affectedRows = $this->repository->delete($id);

        if ($affectedRows < 1) {
            return [
                'ok' => false,
                'flash_key' => 'organisaties_error',
                'message' => 'Organisatie kon niet worden verwijderd.',
                'redirect' => appUrl('organisaties'),
            ];
        }

        return [
            'ok' => true,
            'flash_key' => 'organisaties_success',
            'message' => 'Organisatie succesvol verwijderd.',
            'redirect' => appUrl('organisaties'),
        ];
    }
}