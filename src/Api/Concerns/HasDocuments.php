<?php

namespace AmaizingCompany\CertifactionClient\Api\Concerns;

use AmaizingCompany\CertifactionClient\Api\DataObjects\DocumentItem;
use Illuminate\Support\Collection;

trait HasDocuments
{
    /**
     * @var Collection<DocumentItem>
     */
    protected Collection $documents;

    public function addDocument(DocumentItem $document): static
    {
        $this->documents->add($document);

        return $this;
    }

    public function addDocuments(DocumentItem ...$documents): static
    {
        foreach ($documents as $document) {
            $this->addDocument($document);
        }

        return $this;
    }

    /**
     * @return Collection<DocumentItem>
     */
    public function getDocuments(): Collection
    {
        return $this->documents ?? Collection::empty();
    }

    protected function getDocumentsBody(): string
    {
        foreach ($this->documents as $document) {
            $body['files'][] = [
                'url' => $document->getUrl(),
                'name' => $document->getName(),
            ];
        }

        return json_encode($body ?? ['files' => []]);
    }

    protected function initDocuments(): void
    {
        $this->documents = Collection::empty();
    }
}
