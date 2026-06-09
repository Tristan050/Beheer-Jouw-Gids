<?php

class LinkAandachtspuntKoppelingService extends BaseService
{
    public function __construct(
        private readonly LinkAandachtspuntKoppelingRepository $repository = new LinkAandachtspuntKoppelingRepository(),
        private readonly LinkRepository $linkRepository = new LinkRepository(),
        private readonly AandachtspuntRepository $aandachtspuntRepository = new AandachtspuntRepository(),
        private readonly FunctieRepository $functieRepository = new FunctieRepository(),
        private readonly LeefgebiedRepository $leefgebiedRepository = new LeefgebiedRepository()
    ) {
    }

    public function getLinkOptions(): array
    {
        return array_map(static function (LinkDTO $item): array {
            return [
                'id' => $item->id,
                'name' => $item->title,
                'url' => $item->url,
            ];
        }, $this->linkRepository->getAll());
    }

    public function getAandachtspuntOptions(): array
    {
        $functieNameById = [];
        $functieLeefgebiedIdById = [];
        $leefgebiedNameById = [];

        foreach ($this->functieRepository->getAll() as $functie) {
            $functieNameById[$functie->id] = $functie->name;
            $functieLeefgebiedIdById[$functie->id] = $functie->leefgebiedId;
        }

        foreach ($this->leefgebiedRepository->getAll() as $leefgebied) {
            $leefgebiedNameById[$leefgebied->id] = $leefgebied->name;
        }

        return array_map(function (AandachtspuntDTO $item) use ($functieNameById, $functieLeefgebiedIdById, $leefgebiedNameById): array {
            $functieName = (string) ($functieNameById[$item->functieId] ?? 'Onbekende functie');
            $leefgebiedId = (int) ($functieLeefgebiedIdById[$item->functieId] ?? 0);
            $leefgebiedName = (string) ($leefgebiedNameById[$leefgebiedId] ?? 'Onbekend leefgebied');

            return [
                'id' => $item->id,
                'name' => $item->aandachtspunt,
                'context' => $leefgebiedName . ' / ' . $functieName,
            ];
        }, $this->aandachtspuntRepository->getAll());
    }

    public function getIndexItems(): array
    {
        $rows = $this->repository->getDetailedRows();

        return array_map(static function (array $row): array {
            $linkId = (int) ($row['link_id'] ?? 0);
            $aandachtspuntId = (int) ($row['aandachtspunt_id'] ?? 0);
            $linkTitle = (string) ($row['link_title'] ?? '');
            $linkUrl = (string) ($row['link_url'] ?? '');
            $aandachtspunt = (string) ($row['aandachtspunt'] ?? '');
            $functieName = (string) ($row['functie_name'] ?? '');
            $leefgebiedName = (string) ($row['leefgebied_name'] ?? '');

            return [
                'link_id' => $linkId,
                'aandachtspunt_id' => $aandachtspuntId,
                'link_title' => $linkTitle,
                'link_url' => $linkUrl,
                'aandachtspunt' => $aandachtspunt,
                'functie_name' => $functieName,
                'leefgebied_name' => $leefgebiedName,
                'search' => strtolower(trim($linkId . ' ' . $aandachtspuntId . ' ' . $linkTitle . ' ' . $linkUrl . ' ' . $aandachtspunt . ' ' . $functieName . ' ' . $leefgebiedName)),
            ];
        }, $rows);
    }

    public function getFormValues(int $selectedLinkId = 0): array
    {
        $selectedLink = (int) old('LinkID', (string) $selectedLinkId);
        $fromOld = $_SESSION['old_input']['AandachtspuntIDs'] ?? null;

        if (is_array($fromOld)) {
            $selectedAandachtspuntIds = array_values(array_unique(array_map('intval', $fromOld)));
        } else {
            $selectedAandachtspuntIds = $selectedLink > 0
                ? $this->repository->getAandachtspuntIdsForLink($selectedLink)
                : [];
        }

        return [
            'LinkID' => (string) $selectedLink,
            'AandachtspuntIDs' => $selectedAandachtspuntIds,
        ];
    }

    public function save(array $input): array
    {
        $linkId = (int) ($input['LinkID'] ?? 0);
        $aandachtspuntIdsRaw = $input['AandachtspuntIDs'] ?? [];
        $aandachtspuntIdsRaw = is_array($aandachtspuntIdsRaw) ? $aandachtspuntIdsRaw : [];
        $aandachtspuntIds = [];

        foreach ($aandachtspuntIdsRaw as $aandachtspuntIdRaw) {
            if (is_numeric((string) $aandachtspuntIdRaw)) {
                $aandachtspuntId = (int) $aandachtspuntIdRaw;
                if ($aandachtspuntId > 0) {
                    $aandachtspuntIds[] = $aandachtspuntId;
                }
            }
        }

        $aandachtspuntIds = array_values(array_unique($aandachtspuntIds));

        rememberInput([
            'LinkID' => (string) $linkId,
            'AandachtspuntIDs' => array_map('strval', $aandachtspuntIds),
        ]);

        if ($linkId <= 0) {
            return $this->error('link_aandachtspunt_koppelingen_form_error', 'Selecteer een link.', appUrl('link-aandachtspunt-koppelingen'));
        }

        if ($this->linkRepository->findById($linkId) === null) {
            return $this->error('link_aandachtspunt_koppelingen_form_error', 'Geselecteerde link bestaat niet.', appUrl('link-aandachtspunt-koppelingen'));
        }

        $validAandachtspuntIds = [];
        foreach ($aandachtspuntIds as $aandachtspuntId) {
            if ($this->aandachtspuntRepository->findById($aandachtspuntId) !== null) {
                $validAandachtspuntIds[] = $aandachtspuntId;
            }
        }

        $this->repository->replaceAandachtspuntenForLink($linkId, $validAandachtspuntIds);
        clearOldInput();

        return $this->success('link_aandachtspunt_koppelingen_success', 'Koppelingen succesvol opgeslagen.', appUrl('link-aandachtspunt-koppelingen') . '?link_id=' . $linkId);
    }

    public function delete(array $input): array
    {
        $linkId = (int) ($input['LinkID'] ?? 0);
        $aandachtspuntId = (int) ($input['AandachtspuntID'] ?? 0);

        if ($linkId <= 0 || $aandachtspuntId <= 0) {
            return $this->error('link_aandachtspunt_koppelingen_error', 'Ongeldige koppeling geselecteerd.', appUrl('link-aandachtspunt-koppelingen'));
        }

        $affectedRows = $this->repository->deleteLink($linkId, $aandachtspuntId);
        if ($affectedRows < 1) {
            return $this->error('link_aandachtspunt_koppelingen_error', 'Koppeling niet gevonden of al verwijderd.', appUrl('link-aandachtspunt-koppelingen'));
        }

        return $this->success('link_aandachtspunt_koppelingen_success', 'Koppeling succesvol verwijderd.', appUrl('link-aandachtspunt-koppelingen') . '?link_id=' . $linkId);
    }
}
