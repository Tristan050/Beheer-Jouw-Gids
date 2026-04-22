<?php

class LeefgebiedenController extends BaseController
{
    public function __construct(private readonly LeefgebiedService $service = new LeefgebiedService())
    {
    }

    public function index(): void
    {
        $this->auth();

        $this->render('admin/leefgebieden', [
            'items' => $this->service->getIndexItems(),
            'success' => getFlash('leefgebieden_success'),
            'error' => getFlash('leefgebieden_error'),
        ]);
    }

    public function edit(): void
    {
        $this->auth();

        $id = (int) ($_GET['id'] ?? 0);
        $item = $this->service->getById($id);

        if ($id > 0 && $item === null) {
            setFlash('leefgebieden_error', 'Leefgebied niet gevonden.');
            redirect(appUrl('leefgebieden'));
        }

        $this->render('admin/leefgebied-edit', [
            'item' => $item,
            'form_error' => getFlash('leefgebieden_form_error'),
            'form_values' => $this->service->getFormValues($item),
        ]);

        clearOldInput();
    }

    public function save(): void
    {
        $this->auth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            throw new HttpException(405, 'Methode niet toegestaan');
        }

        CSRF::check();

        $result = $this->service->save($_POST);
        setFlash((string) $result['flash_key'], (string) $result['message']);
        redirect((string) $result['redirect']);
    }
}
