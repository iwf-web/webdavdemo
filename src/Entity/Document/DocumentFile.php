<?php
/**
 * File.php
 *
 * @author Michael Bogucki / IWF AG / Web Solutions
 * @since  05/2019
 */

namespace App\Entity\Document;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Table]
#[ORM\Entity]
class DocumentFile
{
    #[ORM\Column, ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[ORM\Column(length: 255)]
    protected string $path;

    #[ORM\Column(length: 255)]
    protected string $filename;

    #[ORM\Column(length: 255)]
    protected string $mimetype;

    #[ORM\Column(length: 255)]
    protected string $originalFilename;

    #[ORM\Column(length: 255)]
    protected string $checksum;

    #[ORM\Column]
    protected int $size;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected DateTime $createdAt;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected DateTime $updatedAt;

    #[ORM\Column(nullable: true)]
    protected ?string $createdBy = null;
    
    #[ORM\Column(nullable: true)]
    protected ?string $updatedBy = null;

    #[ORM\ManyToOne(inversedBy: 'fileVersions')]
    protected Document $document;

    #[ORM\Column(type: 'smallint')]
    private int $versionNr = 1;

    public function __construct($path, $filename, $mimetype, $originalFilename = null, $size = null, $checksum = null)
    {
        $this->filename = $filename; //detect path
        $this->path = $path; //detect filename

        $this->mimetype = $mimetype;
        $this->originalFilename = $originalFilename ?: $this->filename;
        $this->size = $size ?? filesize($this->getFullPath());
        $this->checksum = $checksum ?? $this->createChecksum();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullPath(): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $this->filename;
    }

    public function getMimetype(): string
    {
        return $this->mimetype;
    }

    public function getFileExtension(): string
    {
        return $this->splitOriginalFilename(2);
    }

    public function getOriginalFilenameWithoutExtension(): string
    {
        return $this->splitOriginalFilename(1);
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

    public function createChecksum(): string
    {
        return md5_file($this->getFullPath());
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getOriginalFilename(): string
    {
        return $this->originalFilename;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
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


    protected function splitOriginalFilename($partNr, $pattern = '/(.*)\\.([a-zA-Z\\d]+)$/')
    {
        $matches = null;

        return preg_match($pattern, $this->originalFilename, $matches) ? ($matches[$partNr] ?? '') : '';
    }

    public function getVersionNr(): int
    {
        return $this->versionNr;
    }

    public function getDocument(): Document
    {
        return $this->document;
    }

    public function setDocument(Document $document, $versionNr = 1): DocumentFile
    {
        $this->document = $document;
        $this->versionNr = $versionNr;

        return $this;
    }
}
