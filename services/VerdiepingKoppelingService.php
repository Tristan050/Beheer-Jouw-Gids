<?php

class VerdiepingKoppelingService extends BaseService
{
    public function __construct(
        private readonly VerdiepingKoppelingRepository $repository = new VerdiepingKoppelingRepository(),
        private readonly VerdiepingsvraagRepository $verdiepingsvraagRepository = new VerdiepingsvraagRepository(),
        private readonly OrganisatieRepository $organisatieRepository = new OrganisatieRepository()
    ) {
    }

    public function getVerdiepingsvraagOptions(): array
    {
        $items = $this->verdiepingsvraagRepository->getAll();

        return array_map(static function (VerdiepingsvraagDTO $item): array {
            return [
                'id' => $item->id,
                'name' => $item->vraag,
            ];
        }, $items);
    }

    public function getOrganisatieOptions(): array
    {
        $items = $this->organisatieRepository->getAll();

        return array_map(static function (OrganisatieDTO $item): array {
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
            $verdiepingsvraagId = (int) ($row['verdiepingsvraag_id'] ?? 0);
            $organisatieId = (int) ($row['organisatie_id'] ?? 0);
            $vraag = (string) ($row['vraag'] ?? '');
            $organisatieName = (string) ($row['organisatie_name'] ?? '');

            return [
                'verdiepingsvraag_id' => $verdiepingsvraagId,
                'organisatie_id' => $organisatieId,
                'vraag' => $vraag,
                'organisatie_name' => $organisatieName,
                'search' => strtolower(trim($verdiepingsvraagId . ' ' . $organisatieId . ' ' . $vraag . ' ' . $organisatieName)),
            ];
        }, $rows);
    }

    public function getFormValues(int $selectedVraagId = 0): array
    {
        $selectedVraag = (int) old('VerdiepingsvraagID', (string) $selectedVraagId);
        $fromOld = $_SESSION['old_input']['OrganisatieIDs'] ?? null;

        if (is_array($fromOld)) {
            $selectedOrganisatieIds = array_values(array_unique(array_map('intval', $fromOld)));
        } else {
            $selectedOrganisatieIds = $selectedVraag > 0
                ? $this->repository->getOrganisatieIdsForVraag($selectedVraag)
                : [];
        }

        return [
            'VerdiepingsvraagID' => (string) $selectedVraag,
            'OrganisatieIDs' => $selectedOrganisatieIds,
        ];
    }

    public function save(array $input): array
    {
        $verdiepingsvraagId = (int) ($input['VerdiepingsvraagID'] ?? 0);
        $organisatieIdsRaw = $input['OrganisatieIDs'] ?? [];
        $organisatieIdsRaw = is_array($organisatieIdsRaw) ? $organisatieIdsRaw : [];
        $organisatieIds = [];

        foreach ($organisatieIdsRaw as $organisatieIdRaw) {
            if (is_numeric((string) $organisatieIdRaw)) {
                $organisatieId = (int) $organisatieIdRaw;
                if ($organisatieId > 0) {
                    $organisatieIds[] = $organisatieId;
                }
            }
        }

        $organisatieIds = array_values(array_unique($organisatieIds));

        rememberInput([
            'VerdiepingsvraagID' => (string) $verdiepingsvraagId,
            'OrganisatieIDs' => array_map('strval', $organisatieIds),
        ]);

        if ($verdiepingsvraagId <= 0) {
            return $this->error('verdieping_koppelingen_form_error', 'Selecteer een verdiepingsvraag.', appUrl('verdieping-koppelingen'));
        }

        if ($this->verdiepingsvraagRepository->findById($verdiepingsvraagId) === null) {
            return $this->error('verdieping_koppelingen_form_error', 'Geselecteerde verdiepingsvraag bestaat niet.', appUrl('verdieping-koppelingen'));
        }

        $validOrganisatieIds = [];
        foreach ($organisatieIds as $organisatieId) {
            if ($this->organisatieRepository->findById($organisatieId) !== null) {
                $validOrganisatieIds[] = $organisatieId;
            }
        }

        $this->repository->replaceOrganisatiesForVraag($verdiepingsvraagId, $validOrganisatieIds);
        clearOldInput();

        return $this->success('verdieping_koppelingen_success', 'Koppelingen succesvol opgeslagen.', appUrl('verdieping-koppelingen') . '?verdiepingsvraag_id=' . $verdiepingsvraagId);
    }

    public function delete(array $input): array
    {
        $verdiepingsvraagId = (int) ($input['VerdiepingsvraagID'] ?? 0);
        $organisatieId = (int) ($input['OrganisatieID'] ?? 0);

        if ($verdiepingsvraagId <= 0 || $organisatieId <= 0) {
            return $this->error('verdieping_koppelingen_error', 'Ongeldige koppeling geselecteerd.', appUrl('verdieping-koppelingen'));
        }

        $affectedRows = $this->repository->deleteLink($verdiepingsvraagId, $organisatieId);
        if ($affectedRows < 1) {
            return $this->error('verdieping_koppelingen_error', 'Koppeling niet gevonden of al verwijderd.', appUrl('verdieping-koppelingen'));
        }

        return $this->success('verdieping_koppelingen_success', 'Koppeling succesvol verwijderd.', appUrl('verdieping-koppelingen') . '?verdiepingsvraag_id=' . $verdiepingsvraagId);
    }
}