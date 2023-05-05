<?php

namespace App\Document\SecureDocument;

use App\Security\OpenSSL;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\HasLifecycleCallbacks]
#[ODM\Document(collection: 'Trace')]
class Trace
{
    #[ODM\Id(type: 'string', strategy: 'auto')]
    private ?string $id = null;

    #[ODM\Field(type: 'string', nullable: false)]
    private ?string $username = null;

    #[ODM\Field(type: 'string', nullable: false)]
    private ?string $ipAddress = null;

    /**
     * Document TTL, expired automatic after 3600 seconds
     * Useful for data retention
     */
    #[ODM\Index(name: 'trace_ttl', expireAfterSeconds: 3600)]
    #[ODM\Field(type: 'date', nullable: false)]
    private ?\DateTimeInterface $createdDate = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    #[ODM\PrePersist]
    public function onPrePersist(): void
    {
        // encrypt username and IP address before persist
        $this->username = OpenSSL::encrypt($this->username);
        $this->ipAddress = OpenSSL::encrypt($this->ipAddress);

        $this->createdDate = new \DateTime();
    }

    #[ODM\PostLoad]
    public function onPostLoad(LifecycleEventArgs $eventArgs): void
    {
        // decrypt username and IP address
        $eventArgs->getDocument()->setUsername(OpenSSL::decrypt($eventArgs->getDocument()->getUsername()));
        $eventArgs->getDocument()->setIpAddress(OpenSSL::decrypt($eventArgs->getDocument()->getIpAddress()));
    }
}