<?php

class LeefgebiedHulpbronKoppelingenController extends BaseController
{
    public function __construct(private readonly LeefgebiedHulpbronKoppelingService $service = new LeefgebiedHulpbronKoppelingService())
    {
    }

    public function index(): void
    {
        $this->requireSuperAdmin();

        $selectedLeefgebiedId = (int) ($_GET['leefgebied_id'] ?? 0);

        $this->render('admin/leefgebied-hulpbron-koppelingen', [
            'items' => $this->service->getIndexItems(),
            'leefgebieden' => $this->service->getLeefgebiedOptions(),
            'hulpbronnen' => $this->service->getHulpbronOptions(),
            'form_values' => $this->service->getFormValues($selectedLeefgebiedId),
            'success' => getFlash('leefgebied_hulpbron_koppelingen_success'),
            'error' => getFlash('leefgebied_hulpbron_koppelingen_error'),
            'form_error' => getFlash('leefgebied_hulpbron_koppelingen_form_error'),
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
