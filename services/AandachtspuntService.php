<?php

class AandachtspuntService extends BaseService
{
    public function __construct(
        private readonly AandachtspuntRepository $repository = new AandachtspuntRepository(),
        private readonly FunctieRepository $functieRepository = new FunctieRepository()
    )
    {
    }

    public function getFunctieOptions(): array
    {
        $items = $this->functieRepository->getAll();

        return array_map(static function (FunctieDTO $item): array {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        }, $items);
    }

    public function getIndexItems(): array
    {
        $items = $this->repository->getAll();
        $functies = $this->getFunctieOptions();
        $functieNameById = [];

        foreach ($functies as $functie) {
            $functieNameById[(int) ($functie['id'] ?? 0)] = (string) ($functie['name'] ?? '');
        }

        return array_map(function (AandachtspuntDTO $item) use ($functieNameById): array {
            $id = $item->id;
            $functieId = $item->functieId;
            $sortOrder = $item->sortOrder;
            $aandachtspunt = $item->aandachtspunt;
            $scanTekst = $item->scanTekst;
            $adviesTekst = $item->adviesTekst;
            $functieName = (string) ($functieNameById[$functieId] ?? 'Onbekend');

            return [
                'id' => $id,
                'functie_id' => $functieId,
                'functie_name' => $functieName,
                'sort_order' => $sortOrder,
                'aandachtspunt' => $aandachtspunt,
                'scan_tekst' => $scanTekst,
                'advies_tekst' => $adviesTekst,
                'search' => strtolower(trim($id . ' ' . $functieName . ' ' . $aandachtspunt . ' ' . $scanTekst . ' ' . $adviesTekst . ' ' . $sortOrder)),
                'edit_url' => appUrl('aandachtspunt-edit') . '?id=' . $id,
            ];
        }, $items);
    }

    public function getById(int $id): ?AandachtspuntDTO
    {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->findById($id);
    }

    public function getFormValues(?AandachtspuntDTO $item): array
    {
        return [
            'AandachtspuntID' => old('AandachtspuntID', $item !== null ? (string) $item->id : ''),
            'FunctieID' => old('FunctieID', $item !== null ? (string) $item->functieId : ''),
            'Sort_order' => old('Sort_order', $item !== null ? (string) $item->sortOrder : '0'),
            'Aandachtspunt' => old('Aandachtspunt', $item !== null ? $item->aandachtspunt : ''),
            'Toelichting' => old('Toelichting', $item !== null ? $item->toelichting : ''),
            'Scan_tekst' => old('Scan_tekst', $item !== null ? $item->scanTekst : ''),
            'Advies_tekst' => old('Advies_tekst', $item !== null ? $item->adviesTekst : ''),
        ];
    }

    public function save(array $input): array
    {
        $id = (int) ($input['AandachtspuntID'] ?? 0);
        $functieIdRaw = trim((string) ($input['FunctieID'] ?? ''));
        $sortOrderRaw = trim((string) ($input['Sort_order'] ?? '0'));
        $aandachtspunt = trim((string) ($input['Aandachtspunt'] ?? ''));
        $toelichting = trim((string) ($input['Toelichting'] ?? ''));
        $scanTekst = trim((string) ($input['Scan_tekst'] ?? ''));
        $adviesTekst = trim((string) ($input['Advies_tekst'] ?? ''));

        $functieId = is_numeric($functieIdRaw) ? (int) $functieIdRaw : 0;
        $sortOrder = is_numeric($sortOrderRaw) ? (int) $sortOrderRaw : 0;

        rememberInput([
            'AandachtspuntID' => (string) $id,
            'FunctieID' => (string) $functieId,
            'Sort_order' => (string) $sortOrder,
            'Aandachtspunt' => $aandachtspunt,
            'Toelichting' => $toelichting,
            'Scan_tekst' => $scanTekst,
            'Advies_tekst' => $adviesTekst,
        ]);

        if ($functieId <= 0) {
            return $this->error('aandachtspunten_form_error', 'Selecteer een functie.', appUrl('aandachtspunt-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($this->functieRepository->findById($functieId) === null) {
            return $this->error('aandachtspunten_form_error', 'Geselecteerde functie bestaat niet.', appUrl('aandachtspunt-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($aandachtspunt === '') {
            return $this->error('aandachtspunten_form_error', 'Aandachtspunt is verplicht.', appUrl('aandachtspunt-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($id > 0) {
            $existing = $this->repository->findById($id);
            if ($existing === null) {
                clearOldInput();

                return $this->error('aandachtspunten_error', 'Aandachtspunt niet gevonden.', appUrl('aandachtspunten'));
            }

            $this->repository->update($id, $functieId, $sortOrder, $aandachtspunt, $toelichting, $scanTekst, $adviesTekst);
            clearOldInput();

            return $this->success('aandachtspunten_success', 'Aandachtspunt succesvol bijgewerkt.', appUrl('aandachtspunten'));
        }

        $newId = $this->repository->create($functieId, $sortOrder, $aandachtspunt, $toelichting, $scanTekst, $adviesTekst);
        clearOldInput();

        return $this->success('aandachtspunten_success', 'Aandachtspunt succesvol toegevoegd (ID: ' . $newId . ').', appUrl('aandachtspunten'));
    }

    public function delete(array $input): array
    {
        $id = (int) ($input['AandachtspuntID'] ?? 0);

        if ($id <= 0) {
            return $this->error('aandachtspunten_error', 'Ongeldig aandachtspunt geselecteerd.', appUrl('aandachtspunten'));
        }

        $existing = $this->repository->findById($id);
        if ($existing === null) {
            return $this->error('aandachtspunten_error', 'Aandachtspunt niet gevonden.', appUrl('aandachtspunten'));
        }

        $affectedRows = $this->repository->delete($id);

        if ($affectedRows < 1) {
            return $this->error('aandachtspunten_error', 'Aandachtspunt kon niet worden verwijderd.', appUrl('aandachtspunten'));
        }

        return [
            'ok' => true,
            'flash_key' => 'aandachtspunten_success',
            'message' => 'Aandachtspunt succesvol verwijderd.',
            'redirect' => appUrl('aandachtspunten'),
        ];
    }
}