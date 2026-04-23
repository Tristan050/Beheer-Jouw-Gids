<?php

final class VerdiepingsvraagDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $aandachtspuntId,
        public readonly string $vraag
    ) {
    }
}