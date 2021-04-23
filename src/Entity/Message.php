<?php

/*
 * This file is part of the Clivern/Events project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="aggregate_id", type="text")
     */
    private $aggregateId;

    /**
     * @var string
     *
     * @ORM\Column(name="headers", type="text")
     */
    private $headers;

    /**
     * @var string
     *
     * @ORM\Column(name="payload", type="text")
     */
    private $payload;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * Class Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('NOW', new \DateTimeZone('UTC'));
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set aggregateId.
     */
    public function setAggregateId(string $aggregateId): self
    {
        $this->aggregateId = $aggregateId;

        return $this;
    }

    /**
     * Get aggregateId.
     */
    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    /**
     * Set headers.
     */
    public function setHeaders(string $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get headers.
     */
    public function getHeaders(): string
    {
        return $this->headers;
    }

    /**
     * Set payload.
     */
    public function setPayload(string $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Get payload.
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * Set createdAt.
     *
     * @return Item
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
