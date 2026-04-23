<?php

final class LeefgebiedDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly int $sortOrder
    ) {
    }
}
