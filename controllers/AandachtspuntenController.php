<?php

class AandachtspuntenController extends BaseController
{
    public function __construct(private readonly AandachtspuntService $service = new AandachtspuntService())
    {
    }

    public function index(): void
    {
        $this->auth();

        $this->render('admin/aandachtspunten', [
            'items' => $this->service->getIndexItems(),
            'success' => getFlash('aandachtspunten_success'),
            'error' => getFlash('aandachtspunten_error'),
        ]);
    }

    public function edit(): void
    {
        $this->auth();

        $id = (int) ($_GET['id'] ?? 0);
        $item = $this->service->getById($id);

        if ($id > 0 && $item === null) {
            setFlash('aandachtspunten_error', 'Aandachtspunt niet gevonden.');
            redirect(appUrl('aandachtspunten'));
        }

        $this->render('admin/aandachtspunt-edit', [
            'item' => $item,
            'form_error' => getFlash('aandachtspunten_form_error'),
            'form_values' => $this->service->getFormValues($item),
            'functies' => $this->service->getFunctieOptions(),
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
