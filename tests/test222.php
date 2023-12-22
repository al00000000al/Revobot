<?php

use Revobot\Util\Hash;
use Revobot\Util\Strings;

require '../vendor/autoload.php';
require '../config.php';
class Test
{

    public static function generateRestorePass(int $user_id)
    {
        $randomString = bin2hex(openssl_random_pseudo_bytes(12));
        $hashedData = (string) substr(Hash::generate($user_id), 0, 12);
        $xor_key = Strings::xor($hashedData, $randomString);
        $xor_user_id = Strings::xor(dechex($user_id), $randomString);
        return $randomString . $xor_key . $xor_user_id;
    }

    public static function checkRecoveryPassword(string $recoveryPassword, int $current_user_id): bool
    {
        $randomString = (string) substr($recoveryPassword, 0, 12);
        $xor_key = (string) substr($recoveryPassword, 24, 12);
        $xor_user_id = (string) substr($recoveryPassword, 36);
        $expectedXorUserId = (int) hexdec(Strings::xor($xor_user_id, $randomString));
        $hashedData = substr(Hash::generate($expectedXorUserId), 0, 12);
        $expectedXorKey = Strings::xor($xor_key, $randomString);
        return ($expectedXorKey === $hashedData) && ($current_user_id !== $expectedXorUserId);
    }

    public static function getUserFromPassword(string $recoveryPassword)
    {
        $randomString = (string) substr($recoveryPassword, 0, 12);
        $xor_user_id = (string) substr($recoveryPassword, 36);
        $expectedXorUserId = (int) hexdec(Strings::xor($xor_user_id, $randomString));
        return $expectedXorUserId;
    }
}
$pass = Test::generateRestorePass(6012754858);
$check = Test::checkRecoveryPassword($pass, 2345) ? 'ok' : 'fail';
$user = Test::getUserFromPassword($pass);
echo "Pass={$pass};\nCheck={$check};\nUser={$user};\n";
