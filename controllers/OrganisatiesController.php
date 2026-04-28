<?php

class OrganisatiesController extends BaseController
{
    public function __construct(private readonly OrganisatieService $service = new OrganisatieService())
    {
    }

    public function index(): void
    {
        $this->requireSuperAdmin();

        $this->render('admin/organisaties', [
            'items' => $this->service->getIndexItems(),
            'success' => getFlash('organisaties_success'),
            'error' => getFlash('organisaties_error'),
        ]);
    }

    public function edit(): void
    {
        $this->requireSuperAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $item = $this->service->getById($id);

        if ($id > 0 && $item === null) {
            setFlash('organisaties_error', 'Organisatie niet gevonden.');
            redirect(appUrl('organisaties'));
        }

        $this->render('admin/organisatie-edit', [
            'item' => $item,
            'form_error' => getFlash('organisaties_form_error'),
            'form_values' => $this->service->getFormValues($item),
        ]);

        clearOldInput();
    }

    public function save(): void
    {
        $this->requireSuperAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            throw new HttpException(405, 'Methode niet toegestaan');
        }

        CSRF::check();

        $result = $this->service->save($_POST);
        setFlash((string) $result['flash_key'], (string) $result['message']);
        redirect((string) $result['redirect']);
    }

    public function delete(): void
    {
        $this->requireSuperAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            throw new HttpException(405, 'Methode niet toegestaan');
        }

        CSRF::check();

        $result = $this->service->delete($_POST);
        setFlash((string) $result['flash_key'], (string) $result['message']);
        redirect((string) $result['redirect']);
    }
}