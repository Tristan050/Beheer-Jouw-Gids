<?php

class VerdiepingsvraagService extends BaseService
{
    public function __construct(
        private readonly VerdiepingsvraagRepository $repository = new VerdiepingsvraagRepository(),
        private readonly AandachtspuntRepository $aandachtspuntRepository = new AandachtspuntRepository()
    ) {
    }

    public function getAandachtspuntOptions(): array
    {
        $items = $this->aandachtspuntRepository->getAll();

        return array_map(static function (AandachtspuntDTO $item): array {
            return [
                'id' => $item->id,
                'name' => $item->aandachtspunt,
            ];
        }, $items);
    }

    public function getIndexItems(): array
    {
        $items = $this->repository->getAll();
        $aandachtspunten = $this->getAandachtspuntOptions();
        $aandachtspuntNameById = [];

        foreach ($aandachtspunten as $aandachtspunt) {
            $aandachtspuntNameById[(int) ($aandachtspunt['id'] ?? 0)] = (string) ($aandachtspunt['name'] ?? '');
        }

        return array_map(function (VerdiepingsvraagDTO $item) use ($aandachtspuntNameById): array {
            $id = $item->id;
            $aandachtspuntId = $item->aandachtspuntId;
            $vraag = $item->vraag;
            $aandachtspuntName = (string) ($aandachtspuntNameById[$aandachtspuntId] ?? 'Onbekend');

            return [
                'id' => $id,
                'aandachtspunt_id' => $aandachtspuntId,
                'aandachtspunt_name' => $aandachtspuntName,
                'vraag' => $vraag,
                'search' => strtolower(trim($id . ' ' . $vraag . ' ' . $aandachtspuntName . ' ' . $aandachtspuntId)),
                'edit_url' => appUrl('verdieping-vraag-edit') . '?id=' . $id,
            ];
        }, $items);
    }

    public function getById(int $id): ?VerdiepingsvraagDTO
    {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->findById($id);
    }

    public function getFormValues(?VerdiepingsvraagDTO $item): array
    {
        return [
            'VerdiepingsvraagID' => old('VerdiepingsvraagID', $item !== null ? (string) $item->id : ''),
            'AandachtspuntID' => old('AandachtspuntID', $item !== null ? (string) $item->aandachtspuntId : ''),
            'Vraag' => old('Vraag', $item !== null ? $item->vraag : ''),
        ];
    }

    public function save(array $input): array
    {
        $id = (int) ($input['VerdiepingsvraagID'] ?? 0);
        $aandachtspuntIdRaw = trim((string) ($input['AandachtspuntID'] ?? ''));
        $vraag = trim((string) ($input['Vraag'] ?? ''));

        $aandachtspuntId = is_numeric($aandachtspuntIdRaw) ? (int) $aandachtspuntIdRaw : 0;

        rememberInput([
            'VerdiepingsvraagID' => (string) $id,
            'AandachtspuntID' => (string) $aandachtspuntId,
            'Vraag' => $vraag,
        ]);

        if ($aandachtspuntId <= 0) {
            return $this->error('verdiepingsvragen_form_error', 'Selecteer een aandachtspunt.', appUrl('verdieping-vraag-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($this->aandachtspuntRepository->findById($aandachtspuntId) === null) {
            return $this->error('verdiepingsvragen_form_error', 'Geselecteerd aandachtspunt bestaat niet.', appUrl('verdieping-vraag-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($vraag === '') {
            return $this->error('verdiepingsvragen_form_error', 'Vraag is verplicht.', appUrl('verdieping-vraag-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($id > 0) {
            $existing = $this->repository->findById($id);
            if ($existing === null) {
                clearOldInput();

                return $this->error('verdiepingsvragen_error', 'Verdiepingsvraag niet gevonden.', appUrl('verdiepingsvragen'));
            }

            $this->repository->update($id, $aandachtspuntId, $vraag);
            clearOldInput();

            return $this->success('verdiepingsvragen_success', 'Verdiepingsvraag succesvol bijgewerkt.', appUrl('verdiepingsvragen'));
        }

        $newId = $this->repository->create($aandachtspuntId, $vraag);
        clearOldInput();

        return $this->success('verdiepingsvragen_success', 'Verdiepingsvraag succesvol toegevoegd (ID: ' . $newId . ').', appUrl('verdiepingsvragen'));
    }

    public function delete(array $input): array
    {
        $id = (int) ($input['VerdiepingsvraagID'] ?? 0);

        if ($id <= 0) {
            return $this->error('verdiepingsvragen_error', 'Ongeldige verdiepingsvraag geselecteerd.', appUrl('verdiepingsvragen'));
        }

        $existing = $this->repository->findById($id);
        if ($existing === null) {
            return $this->error('verdiepingsvragen_error', 'Verdiepingsvraag niet gevonden.', appUrl('verdiepingsvragen'));
        }

        $affectedRows = $this->repository->delete($id);

        if ($affectedRows < 1) {
            return $this->error('verdiepingsvragen_error', 'Verdiepingsvraag kon niet worden verwijderd.', appUrl('verdiepingsvragen'));
        }

        return $this->success('verdiepingsvragen_success', 'Verdiepingsvraag succesvol verwijderd.', appUrl('verdiepingsvragen'));
    }
}
