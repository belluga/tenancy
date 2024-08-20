<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Contracts;

interface Syncable
{
    public function getGlobalIdentifierKeyName(): string;

    public function getGlobalIdentifierKey();

    public function getCentralModelName(): string;

    public function getSyncedAttributeNames(): array;

    public function triggerSyncEvent();
}
