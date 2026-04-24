<?php

final class VragenlijstOptionDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $questionId,
        public readonly string $optionValue,
        public readonly string $label,
        public readonly int $sortOrder
    ) {
    }
}