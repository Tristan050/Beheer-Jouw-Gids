<?php

class VerdiepingsvragenController extends BaseController
{
    public function __construct(private readonly VerdiepingsvraagService $service = new VerdiepingsvraagService())
    {
    }

    public function index(): void
    {
        $this->requireSuperAdmin();

        $this->render('admin/verdiepingsvragen', [
            'items' => $this->service->getIndexItems(),
            'success' => getFlash('verdiepingsvragen_success'),
            'error' => getFlash('verdiepingsvragen_error'),
        ]);
    }

    public function edit(): void
    {
        $this->requireSuperAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $item = $this->service->getById($id);

        if ($id > 0 && $item === null) {
            setFlash('verdiepingsvragen_error', 'Verdiepingsvraag niet gevonden.');
            redirect(appUrl('verdiepingsvragen'));
        }

        $this->render('admin/verdieping-vraag-edit', [
            'item' => $item,
            'form_error' => getFlash('verdiepingsvragen_form_error'),
            'form_values' => $this->service->getFormValues($item),
            'aandachtspunten' => $this->service->getAandachtspuntOptions(),
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
