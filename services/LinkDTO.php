<?php

final class LinkDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $url,
        public readonly string $importantMessage,
        public readonly bool $showPopup
    ) {
    }
}
