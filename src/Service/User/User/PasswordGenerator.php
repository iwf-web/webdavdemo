<?php
/**
 * PasswordGenerator.php
 *
 * @author Jens Hassler / IWF AG / Web Solutions
 * @since  02/2015
 */

namespace App\Service\User\User;

class PasswordGenerator
{
    public function generate(int $length = 9): string
    {
        $vowels = 'aeui';
        $consonants = 'bdghjmnpqrstvwyzucd';
        $consonants .= 'BDGHJLMNPQRSTVWYXZUCD';
        $consonants .= '123456789';
        $vowels .= 'AEUI';

        $pw = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt === 1) {
                $pw .= $consonants[(rand() % strlen($consonants))];
            } else {
                $pw .= $vowels[(rand() % strlen($vowels))];
            }
            $alt = ($alt === 1) ? 0 : 1;
        }

        return $pw;
    }
}
