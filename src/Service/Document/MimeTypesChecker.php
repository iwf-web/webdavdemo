<?php
/**
 * MimeTypes.php
 *
 * @author Michael Bogucki / IWF AG / Web Solutions
 * @since  05/2019
 */

namespace App\Service\Document;


class MimeTypesChecker
{
    private ?array $allowedMimeTypes;

    public function __construct(array $allowedMimeTypes = null)
    {
        $this->allowedMimeTypes = $allowedMimeTypes;
    }

    public function isAllowed($mimetype): bool
    {
        if (empty($this->allowedMimeTypes)) {
            return true;
        }

        // due to a bug in recent php versions (php > 7.3.2) the mime type from finfo might be returned twice
        // to fix this problem for the moment, it's sufficient for now that the given mime type starts with a allowed mime type
        // @see https://bugs.php.net/bug.php?id=77784
        foreach($this->allowedMimeTypes as $allowedMimeType) {
            if (str_starts_with($mimetype, $allowedMimeType)) {
                return true;
            }
        }
        return false;
    }

}
