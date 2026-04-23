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
        $sql = sprintf('SELECT * FROM %s ORDER BY %s', $this->tableName(), $this->orderBy());
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
        $sql = sprintf('SELECT * FROM %s WHERE %s = ? LIMIT 1', $this->tableName(), $this->idColumn());
        $result = execSQL($sql, ['i', $id], false);

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        return $this->mapRow($result->fetch_assoc());
    }

    protected function deleteRowById(int $id): int
    {
        $sql = sprintf('DELETE FROM %s WHERE %s = ?', $this->tableName(), $this->idColumn());
        return (int) execSQL($sql, ['i', $id], true);
    }
}