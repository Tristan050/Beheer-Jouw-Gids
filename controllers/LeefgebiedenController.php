<?php

class LeefgebiedenController extends BaseController
{
    public function __construct(private readonly LeefgebiedService $service = new LeefgebiedService())
    {
    }
    
    public function index(): void
    {
        $this->requireSuperAdmin();

        $this->render('admin/leefgebieden', [
            'items' => $this->service->getIndexItems(),
            'success' => getFlash('leefgebieden_success'),
            'error' => getFlash('leefgebieden_error'),
        ]);
    }

    public function edit(): void
    {
        $this->requireSuperAdmin();

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
