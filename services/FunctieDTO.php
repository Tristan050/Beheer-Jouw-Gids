<?php

final class FunctieDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $leefgebiedId,
        public readonly string $name,
        public readonly string $description,
        public readonly int $sortOrder
    ) {
    }
}