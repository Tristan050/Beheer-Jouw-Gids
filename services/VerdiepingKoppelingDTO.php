<?php

final class VerdiepingKoppelingDTO
{
    public function __construct(
        public readonly int $verdiepingsvraagId,
        public readonly int $organisatieId
    ) {
    }
}