<?php

class VragenlijstenController extends BaseController
{
    public function __construct(private readonly VragenlijstService $service = new VragenlijstService())
    {
    }

    public function index(): void
    {
        $this->requireSuperAdmin();

        $roles = $this->service->getRoleOptions();
        $selectedRoleId = (int) ($_GET['role_id'] ?? 0);
        if ($selectedRoleId <= 0) {
            $selectedRoleId = !empty($roles) ? (int) ($roles[0]['id'] ?? 0) : 0;
        }

        $this->render('admin/vragenlijsten', [
            'roles' => $roles,
            'selected_role_id' => $selectedRoleId,
            'items' => $this->service->getIndexItems($selectedRoleId),
            'success' => getFlash('vragenlijst_success'),
            'error' => getFlash('vragenlijst_error'),
        ]);
    }

    public function editVraag(): void
    {
        $this->requireSuperAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $roleId = (int) ($_GET['role_id'] ?? 0);
        $item = $this->service->getById($id);

        if ($id > 0 && $item === null) {
            setFlash('vragenlijst_error', 'Vraag niet gevonden.');
            redirect(appUrl('vragenlijsten') . ($roleId > 0 ? '?role_id=' . $roleId : ''));
        }

        $selectedRoleId = $item?->roleId ?? $roleId;

        $this->render('admin/vragenlijst-vraag-edit', [
            'item' => $item,
            'roles' => $this->service->getRoleOptions(),
            'question_types' => $this->service->getQuestionTypeOptions(),
            'form_error' => getFlash('vragenlijst_form_error'),
            'form_values' => $this->service->getFormValues($item, $selectedRoleId),
        ]);

        clearOldInput();
    }

    public function saveVraag(): void
    {
        $this->requireSuperAdmin();
        $this->requirePost();
        CSRF::check();

        $result = $this->service->save($_POST);
        setFlash((string) $result['flash_key'], (string) $result['message']);
        redirect((string) $result['redirect']);
    }

    public function deleteVraag(): void
    {
        $this->requireSuperAdmin();
        $this->requirePost();
        CSRF::check();

        $result = $this->service->delete($_POST);
        setFlash((string) $result['flash_key'], (string) $result['message']);
        redirect((string) $result['redirect']);
    }
}