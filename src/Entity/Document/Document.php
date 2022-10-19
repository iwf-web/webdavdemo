<?php
/**
 * Document.php
 *
 * @author Michael Bogucki / IWF AG / Web Solutions
 * @since  05/2019
 */

namespace App\Entity\Document;

use App\Entity\Traits\TimestampableAndBlamableTrait;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;

#[ORM\Table]
#[ORM\Entity]
class Document
{
    use TimestampableAndBlamableTrait;

    #[ORM\Column(name: 'id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[ORM\Column(length: 255)]
    protected string $name;

    #[ORM\Column(length: 10)]
    protected string $fileExtension;

    #[ORM\Column(length: 255)]
    protected string $author;

    #[ORM\Column(type: 'datetime')]
    protected DateTimeInterface $creationDate;

    #[ORM\OneToMany(mappedBy: 'document', targetEntity: DocumentFile::class, cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['versionNr' => 'DESC'])]
    protected Collection $fileVersions;

    #[ORM\OneToOne(targetEntity: DocumentFile::class, cascade: ['persist', 'remove'])]
    protected ?DocumentFile $currentFile = null;

    public function __construct()
    {
        $this->fileVersions = new ArrayCollection();
    }

    public static function createFromDocumentFile(DocumentFile $documentFile): Document
    {
        $self = new static();
        $self->name = $documentFile->getOriginalFilenameWithoutExtension();
        $self->fileExtension = $documentFile->getFileExtension();
        $self->author = $documentFile->getCreatedBy() ?? 'not known';
        $self->creationDate = $documentFile->getCreatedAt();
        $self->createdAt = new \DateTime();

        $self->addFile($documentFile);

        return $self;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $filename): Document
    {
        $this->name = $filename;

        return $this;
    }

    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    public function getFilename(): ?string
    {
        return $this->getName() . '.' . $this->getFileExtension();
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): Document
    {
        $this->author = $author;

        return $this;
    }

    public function getCreationDate(): ?DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTimeInterface $creationDate): Document
    {
        $this->creationDate = $creationDate;

        return $this;
    }


    public function addFile(DocumentFile $file, $sameExtension = true): void
    {
        if ($this->getFileVersions()->contains($file)) {
            return;
        }

        if ($sameExtension && $file->getFileExtension() !== $this->getFileExtension()) {
            throw new RuntimeException('uploaded version must have the same extension');
        }

        if ($sameExtension === false && $file->getFileExtension() !== $this->getFileExtension()) {
            $this->fileExtension = $file->getFileExtension();
        }

        $versionNr = $this->getCurrentFile() ? $this->getCurrentFile()->getVersionNr() + 1 : 1;
        $file->setDocument($this, $versionNr);
        $this->getFileVersions()->add($file);
        $this->setCurrentFile($file);
        $this->updatedAt = new \DateTime();
    }

    /**
     * @param $versionNr
     * @return DocumentFile|null
     */
    public function getFileVersion($versionNr): ?DocumentFile
    {
        foreach ($this->getFileVersions() as $fileVersion) {
            if($fileVersion->getVersionNr() === $versionNr) {
                return $fileVersion;
            }
        }

        return null;
    }

    public function getOrderedFileVersions($dir = Criteria::DESC): Collection
    {
        $versions = $this->getFileVersions();
        if(method_exists($versions, 'matching')) {
            $sort = new Criteria(null, ['versionNr' => $dir]);

            return $versions->matching($sort) ;
        }

        return $versions;
    }

    public function getFileVersions(): Collection
    {
        return $this->fileVersions;
    }

    public function getCurrentFile(): ?DocumentFile
    {
        return $this->currentFile;
    }

    protected function setCurrentFile(DocumentFile $file): void
    {
        $this->currentFile = $file;
    }
}
