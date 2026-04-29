<?php

final class AandachtspuntDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $functieId,
        public readonly int $sortOrder,
        public readonly string $aandachtspunt,
        public readonly string $toelichting,
        public readonly string $scanTekst,
        public readonly string $adviesTekst
    ) {
    }
}