<?php

class HulpbronnenController extends BaseController
{
    public function __construct(private readonly HulpbronService $service = new HulpbronService())
    {
    }
    
    public function index(): void
    {
        $this->requireSuperAdmin();

        $this->render('admin/hulpbronnen', [
            'items' => $this->service->getIndexItems(),
            'success' => getFlash('hulpbronnen_success'),
            'error' => getFlash('hulpbronnen_error'),
        ]);
    }

    public function edit(): void
    {
        $this->requireSuperAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $item = $this->service->getById($id);

        if ($id > 0 && $item === null) {
            setFlash('hulpbronnen_error', 'Hulpbron niet gevonden.');
            redirect(appUrl('hulpbronnen'));
        }

        $this->render('admin/hulpbron-edit', [
            'item' => $item,
            'form_error' => getFlash('hulpbronnen_form_error'),
            'form_values' => $this->service->getFormValues($item),
            'leefgebieden' => $this->service->getLeefgebiedOptions(),
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
