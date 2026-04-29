<?php

class VragenlijstRoleRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'gids_vragenlijst_role';
    }

    protected function idColumn(): string
    {
        return 'id';
    }

    protected function orderBy(): string
    {
        return 'Name ASC';
    }

    public function getAll(): array
    {
        return $this->getAllRows();
    }

    public function findById(int $id): ?VragenlijstRoleDTO
    {
        return $this->getRowById($id);
    }

    protected function mapRow(array $row): VragenlijstRoleDTO
    {
        return new VragenlijstRoleDTO(
            (int) ($row['id'] ?? 0),
            (string) ($row['Name'] ?? ($row['name'] ?? ''))
        );
    }
}