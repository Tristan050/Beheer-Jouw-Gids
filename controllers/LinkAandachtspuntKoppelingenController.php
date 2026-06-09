<?php

class LinkAandachtspuntKoppelingenController extends BaseController
{
    public function __construct(private readonly LinkAandachtspuntKoppelingService $service = new LinkAandachtspuntKoppelingService())
    {
    }

    public function index(): void
    {
        $this->requireSuperAdmin();

        $selectedLinkId = (int) ($_GET['link_id'] ?? 0);

        $this->render('admin/link-aandachtspunt-koppelingen', [
            'items' => $this->service->getIndexItems(),
            'links' => $this->service->getLinkOptions(),
            'aandachtspunten' => $this->service->getAandachtspuntOptions(),
            'form_values' => $this->service->getFormValues($selectedLinkId),
            'success' => getFlash('link_aandachtspunt_koppelingen_success'),
            'error' => getFlash('link_aandachtspunt_koppelingen_error'),
            'form_error' => getFlash('link_aandachtspunt_koppelingen_form_error'),
        ]);

        clearOldInput();
    }

    public function save(): void
    {
        $this->requireSuperAdmin();
        $this->requirePost();
        CSRF::check();

        $result = $this->service->save($_POST);
        setFlash((string) $result['flash_key'], (string) $result['message']);
        redirect((string) $result['redirect']);
    }

    public function delete(): void
    {
        $this->requireSuperAdmin();
        $this->requirePost();
        CSRF::check();

        $result = $this->service->delete($_POST);
        setFlash((string) $result['flash_key'], (string) $result['message']);
        redirect((string) $result['redirect']);
    }
}
