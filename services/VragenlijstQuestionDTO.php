<?php

final class VragenlijstQuestionDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $roleId,
        public readonly string $questionKey,
        public readonly string $label,
        public readonly int $questionTypeId,
        public readonly ?string $defaultValue,
        public readonly int $sortOrder
    ) {
    }
}