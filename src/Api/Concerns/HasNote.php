<?php

namespace AmaizingCompany\CertifactionClient\Api\Concerns;

use Illuminate\Support\Arr;

trait HasNote
{
    public function note(string $note): static
    {
        $this->mergeQueryParams('note', $note);

        return $this;
    }

    public function getNote(): ?string
    {
        return Arr::get($this->getQueryParams(), 'note');
    }
}
