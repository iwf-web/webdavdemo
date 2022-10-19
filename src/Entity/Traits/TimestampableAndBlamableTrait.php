<?php
/**
 * TimestampableAndBlamableTrait.php
 *
 * @author Jens Hassler / IWF AG / Web Solutions
 * @since  01/2016
 */

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait TimestampableAndBlamableTrait
{
    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Gedmo\Timestampable(on: 'create')]
    protected ?DateTime $createdAt = null;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Gedmo\Timestampable(on: 'update')]
    protected ?DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
//    #[Gedmo\Blameable(on: 'create')]
    protected ?string $createdBy = null;

    #[ORM\Column(nullable: true)]
//    #[Gedmo\Blameable(on: 'update')]
    protected ?string $updatedBy = null;

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }
}
