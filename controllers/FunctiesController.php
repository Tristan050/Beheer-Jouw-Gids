<?php

class FunctiesController extends BaseController
{
    public function __construct(private readonly FunctieService $service = new FunctieService())
    {
    }

    public function index(): void
    {
        $this->auth();

        $this->render('admin/functies', [
            'items' => $this->service->getIndexItems(),
            'success' => getFlash('functies_success'),
            'error' => getFlash('functies_error'),
        ]);
    }

    public function edit(): void
    {
        $this->auth();

        $id = (int) ($_GET['id'] ?? 0);
        $item = $this->service->getById($id);

        if ($id > 0 && $item === null) {
            setFlash('functies_error', 'Functie niet gevonden.');
            redirect(appUrl('functies'));
        }

        $this->render('admin/functie-edit', [
            'item' => $item,
            'form_error' => getFlash('functies_form_error'),
            'form_values' => $this->service->getFormValues($item),
            'leefgebieden' => $this->service->getLeefgebiedOptions(),
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

    public function delete(): void
    {
        $this->auth();

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
