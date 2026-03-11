<?php
/**
 * Simple TOTP implementation without libraries
 */
class TOTP {
    private static $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    public static function generateSecret($length = 16) {
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= self::$base32chars[rand(0, 31)];
        }
        return $secret;
    }

    public static function getCode($secret, $timeSlice = null) {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }

        $secretKey = self::base32Decode($secret);
        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        $hmac = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord($hmac[19]) & 0xf;
        $hashpart = substr($hmac, $offset, 4);
        $value = unpack('N', $hashpart);
        $value = $value[1];
        $value = $value & 0x7fffffff;

        return str_pad($value % 1000000, 6, '0', STR_PAD_LEFT);
    }

    private static function base32Decode($base32) {
        $base32 = strtoupper($base32);
        if (!preg_match('/^['.self::$base32chars.']+$/', $base32)) return false;

        $bits = '';
        foreach (str_split($base32) as $char) {
            $bits .= str_pad(decbin(strpos(self::$base32chars, $char)), 5, '0', STR_PAD_LEFT);
        }

        $binary = '';
        foreach (str_split($bits, 8) as $byte) {
            if (strlen($byte) < 8) break;
            $binary .= chr(bindec($byte));
        }
        return $binary;
    }

    public static function verifyCode($secret, $code, $discrepancy = 1) {
        $currentTimeSlice = floor(time() / 30);
        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            if (self::getCode($secret, $currentTimeSlice + $i) === $code) {
                return true;
            }
        }
        return false;
    }
}
