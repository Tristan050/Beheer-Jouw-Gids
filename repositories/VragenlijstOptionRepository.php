<?php

class VragenlijstOptionRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_vragenlijst_option';
    }

    protected function idColumn(): string
    {
        return 'id';
    }

    protected function orderBy(): string
    {
        return 'question_id ASC, sort_order ASC, id ASC';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function getByQuestionId(int $questionId): array
    {
        $result = execSQL(
            'SELECT * FROM gids_vragenlijst_option WHERE question_id = ? ORDER BY sort_order ASC, id ASC',
            ['i', $questionId],
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

    public function getByQuestionIds(array $questionIds): array
    {
        $questionIds = array_values(array_unique(array_map('intval', $questionIds)));
        $questionIds = array_values(array_filter($questionIds, static fn (int $id): bool => $id > 0));

        if (empty($questionIds)) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($questionIds), '?'));
        $types = str_repeat('i', count($questionIds));
        $params = array_merge([$types], $questionIds);

        $result = execSQL(
            'SELECT * FROM gids_vragenlijst_option WHERE question_id IN (' . $placeholders . ') ORDER BY question_id ASC, sort_order ASC, id ASC',
            $params,
            false
        );

        if (!$result) {
            return [];
        }

        $grouped = [];
        while ($row = $result->fetch_assoc()) {
            $item = $this->mapRow($row);
            if (!isset($grouped[$item->questionId])) {
                $grouped[$item->questionId] = [];
            }
            $grouped[$item->questionId][] = $item;
        }

        return $grouped;
    }

    public function replaceForQuestion(int $questionId, array $options): void
    {
        $this->deleteByQuestionId($questionId);

        foreach ($options as $option) {
            $optionValue = trim((string) ($option['option_value'] ?? ''));
            $label = trim((string) ($option['label'] ?? ''));
            $sortOrder = (int) ($option['sort_order'] ?? 0);

            if ($optionValue === '') {
                continue;
            }

            execSQL(
                'INSERT INTO gids_vragenlijst_option (question_id, option_value, label, sort_order) VALUES (?, ?, ?, ?)',
                ['issi', $questionId, $optionValue, $label, $sortOrder],
                true
            );
        }
    }

    public function deleteByQuestionId(int $questionId): int
    {
        return (int) execSQL(
            'DELETE FROM gids_vragenlijst_option WHERE question_id = ?',
            ['i', $questionId],
            true
        );
    }

    protected function mapRow(array $row): VragenlijstOptionDTO
    {
        return new VragenlijstOptionDTO(
            (int) ($row['id'] ?? 0),
            (int) ($row['question_id'] ?? 0),
            (string) ($row['option_value'] ?? ''),
            (string) ($row['label'] ?? ''),
            (int) ($row['sort_order'] ?? 0)
        );
    }
}