<?php

class VerdiepingKoppelingenController extends BaseController
{
    public function __construct(private readonly VerdiepingKoppelingService $service = new VerdiepingKoppelingService())
    {
    }

    public function index(): void
    {
        $this->requireSuperAdmin();

        $selectedVraagId = (int) ($_GET['verdiepingsvraag_id'] ?? 0);

        $this->render('admin/verdieping-koppelingen', [
            'items' => $this->service->getIndexItems(),
            'verdiepingsvragen' => $this->service->getVerdiepingsvraagOptions(),
            'organisaties' => $this->service->getOrganisatieOptions(),
            'form_values' => $this->service->getFormValues($selectedVraagId),
            'success' => getFlash('verdieping_koppelingen_success'),
            'error' => getFlash('verdieping_koppelingen_error'),
            'form_error' => getFlash('verdieping_koppelingen_form_error'),
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