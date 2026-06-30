<?php

class VragenlijstQuestionRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_vragenlijst_question';
    }

    protected function idColumn(): string
    {
        return 'id';
    }

    protected function orderBy(): string
    {
        return 'role_id ASC, sort_order ASC, id ASC';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function getByRoleId(int $roleId): array
    {
        $result = execSQL(
            'SELECT * FROM gids_vragenlijst_question WHERE role_id = ? ORDER BY sort_order ASC, id ASC',
            ['i', $roleId],
            false
        );

        if (!$result) {
            return [];
        }

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $this->mapRow($row);
        }

        return $items;
    }

    public function findById(int $id): ?VragenlijstQuestionDTO
    {
        return $this->getRowById($id);
    }

    public function create(int $roleId, string $questionKey, string $label, int $questionTypeId, ?string $defaultValue, int $sortOrder): int
    {
        if ($defaultValue === null) {
            return (int) execSQL(
                'INSERT INTO gids_vragenlijst_question (role_id, question_key, label, question_type_id, default_value, sort_order) VALUES (?, ?, ?, ?, NULL, ?)',
                ['issii', $roleId, $questionKey, $label, $questionTypeId, $sortOrder],
                true
            );
        }

        return (int) execSQL(
            'INSERT INTO gids_vragenlijst_question (role_id, question_key, label, question_type_id, default_value, sort_order) VALUES (?, ?, ?, ?, ?, ?)',
            ['issisi', $roleId, $questionKey, $label, $questionTypeId, $defaultValue, $sortOrder],
            true
        );
    }

    public function update(int $id, int $roleId, string $questionKey, string $label, int $questionTypeId, ?string $defaultValue, int $sortOrder): int
    {
        if ($defaultValue === null) {
            return (int) execSQL(
                'UPDATE gids_vragenlijst_question SET role_id = ?, question_key = ?, label = ?, question_type_id = ?, default_value = NULL, sort_order = ? WHERE id = ?',
                ['issiii', $roleId, $questionKey, $label, $questionTypeId, $sortOrder, $id],
                true
            );
        }

        return (int) execSQL(
            'UPDATE gids_vragenlijst_question SET role_id = ?, question_key = ?, label = ?, question_type_id = ?, default_value = ?, sort_order = ? WHERE id = ?',
            ['issisii', $roleId, $questionKey, $label, $questionTypeId, $defaultValue, $sortOrder, $id],
            true
        );
    }

    public function delete(int $id): int
    {
        return $this->deleteRowById($id);
    }

    public function existsRoleAndKey(int $roleId, string $questionKey, int $excludeId = 0): bool
    {
        if ($excludeId > 0) {
            $result = execSQL(
                'SELECT id FROM gids_vragenlijst_question WHERE role_id = ? AND question_key = ? AND id <> ? LIMIT 1',
                ['isi', $roleId, $questionKey, $excludeId],
                false
            );
        } else {
            $result = execSQL(
                'SELECT id FROM gids_vragenlijst_question WHERE role_id = ? AND question_key = ? LIMIT 1',
                ['is', $roleId, $questionKey],
                false
            );
        }

        return $result && $result->num_rows > 0;
    }

    protected function mapRow(array $row): VragenlijstQuestionDTO
    {
        return new VragenlijstQuestionDTO(
            (int) ($row['id'] ?? 0),
            (int) ($row['role_id'] ?? 0),
            (string) ($row['question_key'] ?? ''),
            (string) ($row['label'] ?? ''),
            (int) ($row['question_type_id'] ?? 0),
            isset($row['default_value']) ? (string) $row['default_value'] : null,
            (int) ($row['sort_order'] ?? 0)
        );
    }
}