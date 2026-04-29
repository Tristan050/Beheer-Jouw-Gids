<?php

abstract class BaseRepository
{
    abstract protected function tableName(): string;

    abstract protected function idColumn(): string;

    abstract protected function mapRow(array $row): mixed;

    protected function orderBy(): string
    {
        return $this->idColumn() . ' ASC';
    }

    protected function getAllRows(): array
    {
        $sql = 'SELECT * FROM ' . $this->tableName() . ' ORDER BY ' . $this->orderBy();
        $result = execSQL($sql, [], false);

        if (!$result) {
            return [];
        }

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $this->mapRow($row);
        }

        return $items;
    }

    protected function getRowById(int $id): mixed
    {
        $sql = 'SELECT * FROM ' . $this->tableName() . ' WHERE ' . $this->idColumn() . ' = ? LIMIT 1';
        $result = execSQL($sql, ['i', $id], false);

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        return $this->mapRow($result->fetch_assoc());
    }

    protected function deleteRowById(int $id): int
    {
        $sql = 'DELETE FROM ' . $this->tableName() . ' WHERE ' . $this->idColumn() . ' = ?';
        return (int) execSQL($sql, ['i', $id], true);
    }
}