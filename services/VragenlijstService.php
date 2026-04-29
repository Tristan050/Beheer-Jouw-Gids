<?php

class VragenlijstService extends BaseService
{
    public function __construct(
        private readonly VragenlijstRoleRepository $roleRepository = new VragenlijstRoleRepository(),
        private readonly VragenlijstQuestionTypeRepository $questionTypeRepository = new VragenlijstQuestionTypeRepository(),
        private readonly VragenlijstQuestionRepository $questionRepository = new VragenlijstQuestionRepository(),
        private readonly VragenlijstOptionRepository $optionRepository = new VragenlijstOptionRepository()
    ) {
    }

    public function getRoleOptions(): array
    {
        $items = $this->roleRepository->getAll();

        return array_map(static function (VragenlijstRoleDTO $item): array {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        }, $items);
    }

    public function getQuestionTypeOptions(): array
    {
        $items = $this->questionTypeRepository->getAll();

        return array_map(static function (VragenlijstQuestionTypeDTO $item): array {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'has_options' => $item->hasOptions,
            ];
        }, $items);
    }

    public function getDefaultRoleId(): int
    {
        $roles = $this->getRoleOptions();
        return !empty($roles) ? (int) ($roles[0]['id'] ?? 0) : 0;
    }

    public function getIndexItems(int $roleId): array
    {
        if ($roleId <= 0) {
            return [];
        }

        $questions = $this->questionRepository->getByRoleId($roleId);
        $typeOptions = $this->getQuestionTypeOptions();
        $typeById = [];

        foreach ($typeOptions as $typeOption) {
            $typeById[(int) ($typeOption['id'] ?? 0)] = $typeOption;
        }

        $questionIds = array_map(static fn (VragenlijstQuestionDTO $q): int => $q->id, $questions);
        $optionsByQuestionId = $this->optionRepository->getByQuestionIds($questionIds);

        return array_map(function (VragenlijstQuestionDTO $question) use ($roleId, $typeById, $optionsByQuestionId): array {
            $type = $typeById[$question->questionTypeId] ?? ['name' => 'Onbekend', 'has_options' => false];
            $questionOptions = $optionsByQuestionId[$question->id] ?? [];

            $optionLabels = [];
            foreach ($questionOptions as $questionOption) {
                $optionLabels[] = $questionOption->optionValue . '|' . $questionOption->label . '|' . $questionOption->sortOrder;
            }

            $optionsPreview = implode(', ', $optionLabels);

            return [
                'id' => $question->id,
                'role_id' => $question->roleId,
                'question_key' => $question->questionKey,
                'label' => $question->label,
                'question_type' => (string) ($type['name'] ?? 'Onbekend'),
                'has_options' => (bool) ($type['has_options'] ?? false),
                'default_value' => $question->defaultValue ?? '',
                'sort_order' => $question->sortOrder,
                'options_preview' => $optionsPreview,
                'edit_url' => appUrl('vragenlijst-vraag-edit') . '?id=' . $question->id . '&role_id=' . $roleId,
                'search' => strtolower(trim($question->id . ' ' . $question->questionKey . ' ' . $question->label . ' ' . $optionsPreview)),
            ];
        }, $questions);
    }

    public function getById(int $id): ?VragenlijstQuestionDTO
    {
        if ($id <= 0) {
            return null;
        }

        return $this->questionRepository->findById($id);
    }

    public function getFormValues(?VragenlijstQuestionDTO $item, int $selectedRoleId = 0): array
    {
        $questionId = $item?->id ?? 0;
        $optionLines = old('Option_lines', $questionId > 0 ? $this->getOptionLinesForQuestion($questionId) : '');

        return [
            'QuestionID' => old('QuestionID', $item !== null ? (string) $item->id : ''),
            'Roleid' => old('Roleid', $item !== null ? (string) $item->roleId : (string) $selectedRoleId),
            'Question_key' => old('Question_key', $item !== null ? $item->questionKey : ''),
            'Label' => old('Label', $item !== null ? $item->label : ''),
            'Question_type_id' => old('Question_type_id', $item !== null ? (string) $item->questionTypeId : ''),
            'Default_value' => old('Default_value', $item !== null ? (string) ($item->defaultValue ?? '') : ''),
            'Sort_order' => old('Sort_order', $item !== null ? (string) $item->sortOrder : '0'),
            'Option_lines' => $optionLines,
        ];
    }

    public function save(array $input): array
    {
        $id = (int) ($input['QuestionID'] ?? 0);
        $roleId = (int) ($input['Roleid'] ?? 0);
        $questionKey = trim((string) ($input['Question_key'] ?? ''));
        $label = trim((string) ($input['Label'] ?? ''));
        $questionTypeId = (int) ($input['Question_type_id'] ?? 0);
        $defaultValueRaw = trim((string) ($input['Default_value'] ?? ''));
        $sortOrderRaw = trim((string) ($input['Sort_order'] ?? '0'));
        $optionLinesRaw = trim((string) ($input['Option_lines'] ?? ''));
        $sortOrder = is_numeric($sortOrderRaw) ? (int) $sortOrderRaw : 0;

        rememberInput([
            'QuestionID' => (string) $id,
            'Roleid' => (string) $roleId,
            'Question_key' => $questionKey,
            'Label' => $label,
            'Question_type_id' => (string) $questionTypeId,
            'Default_value' => $defaultValueRaw,
            'Sort_order' => (string) $sortOrder,
            'Option_lines' => $optionLinesRaw,
        ]);

        if ($roleId <= 0 || $this->roleRepository->findById($roleId) === null) {
            return $this->error('vragenlijst_form_error', 'Selecteer een geldige rol.', appUrl('vragenlijst-vraag-edit') . ($id > 0 ? '?id=' . $id . '&role_id=' . $roleId : '?role_id=' . $roleId));
        }

        if ($questionKey === '') {
            return $this->error('vragenlijst_form_error', 'Question_key is verplicht.', appUrl('vragenlijst-vraag-edit') . ($id > 0 ? '?id=' . $id . '&role_id=' . $roleId : '?role_id=' . $roleId));
        }

        if ($label === '') {
            return $this->error('vragenlijst_form_error', 'Label is verplicht.', appUrl('vragenlijst-vraag-edit') . ($id > 0 ? '?id=' . $id . '&role_id=' . $roleId : '?role_id=' . $roleId));
        }

        $questionType = $this->questionTypeRepository->findById($questionTypeId);
        if ($questionType === null) {
            return $this->error('vragenlijst_form_error', 'Selecteer een geldig vraagtype.', appUrl('vragenlijst-vraag-edit') . ($id > 0 ? '?id=' . $id . '&role_id=' . $roleId : '?role_id=' . $roleId));
        }

        $defaultValue = null;
        if ($defaultValueRaw !== '') {
            json_decode($defaultValueRaw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->error('vragenlijst_form_error', 'Default_value moet geldige JSON zijn (of leeg blijven).', appUrl('vragenlijst-vraag-edit') . ($id > 0 ? '?id=' . $id . '&role_id=' . $roleId : '?role_id=' . $roleId));
            }
            $defaultValue = $defaultValueRaw;
        }

        if ($this->questionRepository->existsRoleAndKey($roleId, $questionKey, $id)) {
            return $this->error('vragenlijst_form_error', 'Question_key bestaat al voor deze rol.', appUrl('vragenlijst-vraag-edit') . ($id > 0 ? '?id=' . $id . '&role_id=' . $roleId : '?role_id=' . $roleId));
        }

        if ($id > 0) {
            $existing = $this->questionRepository->findById($id);
            if ($existing === null) {
                clearOldInput();

                return $this->error('vragenlijst_error', 'Vraag niet gevonden.', appUrl('vragenlijsten') . '?role_id=' . $roleId);
            }

            $this->questionRepository->update($id, $roleId, $questionKey, $label, $questionTypeId, $defaultValue, $sortOrder);
            $questionId = $id;
        } else {
            $questionId = $this->questionRepository->create($roleId, $questionKey, $label, $questionTypeId, $defaultValue, $sortOrder);
        }

        if ($questionType->hasOptions) {
            $options = $this->parseOptionLines($optionLinesRaw);

            if (empty($options)) {
                return $this->error('vragenlijst_form_error', 'Dit vraagtype vereist minimaal 1 optie.', appUrl('vragenlijst-vraag-edit') . '?id=' . $questionId . '&role_id=' . $roleId);
            }

            $this->optionRepository->replaceForQuestion($questionId, $options);
        } else {
            $this->optionRepository->deleteByQuestionId($questionId);
        }

        clearOldInput();

        return $this->success('vragenlijst_success', 'Vraag succesvol opgeslagen.', appUrl('vragenlijsten') . '?role_id=' . $roleId);
    }

    public function delete(array $input): array
    {
        $id = (int) ($input['QuestionID'] ?? 0);
        $roleId = (int) ($input['Roleid'] ?? 0);

        if ($id <= 0) {
            return $this->error('vragenlijst_error', 'Ongeldige vraag geselecteerd.', appUrl('vragenlijsten') . ($roleId > 0 ? '?role_id=' . $roleId : ''));
        }

        $existing = $this->questionRepository->findById($id);
        if ($existing === null) {
            return $this->error('vragenlijst_error', 'Vraag niet gevonden.', appUrl('vragenlijsten') . ($roleId > 0 ? '?role_id=' . $roleId : ''));
        }

        $affectedRows = $this->questionRepository->delete($id);
        if ($affectedRows < 1) {
            return $this->error('vragenlijst_error', 'Vraag kon niet worden verwijderd.', appUrl('vragenlijsten') . ($roleId > 0 ? '?role_id=' . $roleId : ''));
        }

        return $this->success('vragenlijst_success', 'Vraag succesvol verwijderd.', appUrl('vragenlijsten') . ($roleId > 0 ? '?role_id=' . $roleId : ''));
    }

    private function getOptionLinesForQuestion(int $questionId): string
    {
        $options = $this->optionRepository->getByQuestionId($questionId);
        if (empty($options)) {
            return '';
        }

        $lines = [];
        foreach ($options as $option) {
            $lines[] = $option->optionValue . '|' . $option->label . '|' . $option->sortOrder;
        }

        return implode("\n", $lines);
    }

    private function parseOptionLines(string $optionLinesRaw): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $optionLinesRaw) ?: [];
        $parsed = [];
        $seen = [];
        $fallbackSort = 1;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = array_map('trim', explode('|', $line));
            $optionValue = (string) ($parts[0] ?? '');

            if ($optionValue === '') {
                continue;
            }

            if (isset($seen[$optionValue])) {
                continue;
            }

            $label = (string) ($parts[1] ?? $optionValue);
            $sortOrder = isset($parts[2]) && is_numeric((string) $parts[2]) ? (int) $parts[2] : $fallbackSort;

            $parsed[] = [
                'option_value' => $optionValue,
                'label' => $label,
                'sort_order' => $sortOrder,
            ];

            $seen[$optionValue] = true;
            $fallbackSort++;
        }

        return $parsed;
    }
}