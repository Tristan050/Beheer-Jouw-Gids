<?php

class VragenlijstQuestionTypeRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_vragenlijst_question_type';
    }

    protected function idColumn(): string
    {
        return 'id';
    }

    protected function orderBy(): string
    {
        return 'id ASC';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function findById(int $id): ?VragenlijstQuestionTypeDTO
    {
        return $this->getRowById($id);
    }

    protected function mapRow(array $row): VragenlijstQuestionTypeDTO
    {
        return new VragenlijstQuestionTypeDTO(
            (int) ($row['id'] ?? 0),
            (string) ($row['name'] ?? ''),
            (int) ($row['has_options'] ?? 0) === 1
        );
    }
}