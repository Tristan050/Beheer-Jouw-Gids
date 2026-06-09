<?php

class LinkService extends BaseService
{
    public function __construct(private readonly LinkRepository $repository = new LinkRepository())
    {
    }

    public function getIndexItems(): array
    {
        $items = $this->repository->getAll();

        return array_map(function (LinkDTO $item): array {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'url' => $item->url,
                'important_message' => $item->importantMessage,
                'show_popup' => $item->showPopup,
                'search' => strtolower(trim($item->id . ' ' . $item->title . ' ' . $item->url . ' ' . $item->importantMessage)),
                'edit_url' => appUrl('link-edit') . '?id=' . $item->id,
            ];
        }, $items);
    }

    public function getById(int $id): ?LinkDTO
    {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->findById($id);
    }

    public function getFormValues(?LinkDTO $item): array
    {
        return [
            'LinkID' => old('LinkID', $item !== null ? (string) $item->id : ''),
            'titel' => old('titel', $item !== null ? $item->title : ''),
            'url' => old('url', $item !== null ? $item->url : ''),
            'belangrijk_bericht' => old('belangrijk_bericht', $item !== null ? $item->importantMessage : ''),
            'toon_popup' => old('toon_popup', $item !== null && $item->showPopup ? '1' : '0'),
        ];
    }

    public function save(array $input): array
    {
        $id = (int) ($input['LinkID'] ?? 0);
        $title = trim((string) ($input['titel'] ?? ''));
        $url = trim((string) ($input['url'] ?? ''));
        $importantMessage = trim((string) ($input['belangrijk_bericht'] ?? ''));
        $showPopup = isset($input['toon_popup']) && (string) $input['toon_popup'] === '1';

        rememberInput([
            'LinkID' => (string) $id,
            'titel' => $title,
            'url' => $url,
            'belangrijk_bericht' => $importantMessage,
            'toon_popup' => $showPopup ? '1' : '0',
        ]);

        if ($title === '') {
            return $this->error('links_form_error', 'Titel is verplicht.', appUrl('link-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($url === '') {
            return $this->error('links_form_error', 'URL is verplicht.', appUrl('link-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $this->error('links_form_error', 'Vul een geldige URL in, bijvoorbeeld https://www.voorbeeld.nl.', appUrl('link-edit') . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($id > 0) {
            $existing = $this->repository->findById($id);
            if ($existing === null) {
                clearOldInput();

                return $this->error('links_error', 'Link niet gevonden.', appUrl('links'));
            }

            $this->repository->update($id, $title, $url, $importantMessage, $showPopup);
            clearOldInput();

            return $this->success('links_success', 'Link succesvol bijgewerkt.', appUrl('links'));
        }

        $newId = $this->repository->create($title, $url, $importantMessage, $showPopup);
        clearOldInput();

        return $this->success('links_success', 'Link succesvol toegevoegd (ID: ' . $newId . ').', appUrl('links'));
    }

    public function delete(array $input): array
    {
        $id = (int) ($input['LinkID'] ?? 0);

        if ($id <= 0) {
            return $this->error('links_error', 'Ongeldige link geselecteerd.', appUrl('links'));
        }

        $existing = $this->repository->findById($id);
        if ($existing === null) {
            return $this->error('links_error', 'Link niet gevonden.', appUrl('links'));
        }

        $affectedRows = $this->repository->delete($id);
        if ($affectedRows < 1) {
            return $this->error('links_error', 'Link kon niet worden verwijderd.', appUrl('links'));
        }

        return $this->success('links_success', 'Link succesvol verwijderd.', appUrl('links'));
    }
}
