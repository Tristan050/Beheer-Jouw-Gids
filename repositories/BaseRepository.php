<?php

abstract class BaseRepository
{
    abstract protected function tableName(): string;

    abstract protected function idColumn(): string;

    abstract protected function mapRow(array $row): mixed;

    protected function orderBy(): string
    {
        if ($this->hasColumn('Sort_order')) {
            return 'Sort_order ASC';
        }

        return $this->idColumn() . ' ASC';
    }

    /**
     * Check whether the current table has the given column.
     * Uses a simple cache to avoid repeated SHOW COLUMNS calls.
     */
    protected function hasColumn(string $column): bool
    {
        static $cache = [];

        $key = $this->tableName() . '|' . $column;
        if (isset($cache[$key])) {
            return $cache[$key];
        }

        $sql = "SHOW COLUMNS FROM " . $this->tableName() . " LIKE '" . $column . "'";
        $result = execSQL($sql, [], false);
        $has = (bool) ($result && $result->num_rows > 0);
        $cache[$key] = $has;

        return $has;
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