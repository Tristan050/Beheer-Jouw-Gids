<?php

class LinksController extends BaseController
{
    public function __construct(private readonly LinkService $service = new LinkService())
    {
    }

    public function index(): void
    {
        $this->requireSuperAdmin();

        $this->render('admin/links', [
            'items' => $this->service->getIndexItems(),
            'success' => getFlash('links_success'),
            'error' => getFlash('links_error'),
        ]);
    }

    public function edit(): void
    {
        $this->requireSuperAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $item = $this->service->getById($id);

        if ($id > 0 && $item === null) {
            setFlash('links_error', 'Link niet gevonden.');
            redirect(appUrl('links'));
        }

        $this->render('admin/link-edit', [
            'item' => $item,
            'form_error' => getFlash('links_form_error'),
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
