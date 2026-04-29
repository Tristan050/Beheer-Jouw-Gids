<?php

final class OrganisatieDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $address,
        public readonly string $phone,
        public readonly string $email,
        public readonly string $website
    ) {
    }
}