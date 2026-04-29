<?php

final class VragenlijstQuestionTypeDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly bool $hasOptions
    ) {
    }
}