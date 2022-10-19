<?php
/**
 * ConfirmationTokenGenerator.php
 *
 * @author nicofurrer / IWF AG / Web Solutions
 * @since  03/2017
 */

namespace App\Service\User\User;

class ConfirmationTokenGenerator
{
    private $useOpenSsl;

    public function __construct()
    {
        $this->useOpenSsl = !!function_exists('openssl_random_pseudo_bytes');
    }

    public function generateToken()
    {
        return rtrim(strtr(base64_encode($this->getRandomNumber()), '+/', '-_'), '=');
    }

    private function getRandomNumber()
    {
        $nbBytes = 32;

        // try OpenSSL
        if ($this->useOpenSsl) {
            $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);

            if (false !== $bytes && true === $strong) {
                return $bytes;
            }
        }

        return hash('sha256', uniqid(mt_rand(), true), true);
    }
}