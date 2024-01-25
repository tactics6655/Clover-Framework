<?php

declare(strict_types=1);

namespace Neko\Classes\Crypt;

class OpenSSL
{

    /**
     * Encrypts data
     */
    public static function encrypt($data, $algorithm, $passphrase, $options = OPENSSL_RAW_DATA, $initializationVector = "")
    {
        return openssl_encrypt($data, $algorithm, $passphrase, $options, $initializationVector);
    }

    /**
     * Retrieve the available certificate locations
     */
    public static function getCertificateLocation()
    {
        return openssl_get_cert_locations();
    }

    /**
     * Returns the subject of a CSR
     */
    public static function getCertificateSigningRequestSubject(\OpenSSLCertificateSigningRequest|string $csr, bool|null $short_names = true)
    {
        return openssl_csr_get_subject($csr, $short_names);
    }

    /**
     * Gets available digest methods
     */
    public static function getDigestMethods(bool|null $aliases = false)
    {
        return openssl_get_md_methods();
    }

    /**
     * Decrypts data
     */
    public static function decrypt($data, $algorithm, $passphrase, $options = OPENSSL_RAW_DATA, $initializationVector = "")
    {
        return openssl_decrypt($data, $algorithm, $passphrase, $options, $initializationVector);
    }

    /**
     * Gets the cipher key length.
     */
    public static function getCipherKeyLength($algorithm)
    {
        return openssl_cipher_key_length($algorithm);
    }

    /**
     * Generate a pseudo-random string of bytes
     */
    public static function generatePseudoRandomStringOfBytes($length, bool|null $strong_result = null)
    {
        return openssl_random_pseudo_bytes($length, $strong_result);
    }

    /**
     * The cipher method, see openssl_get_cipher_methods() for a list of potential values
     */
    public static function getCipherInitializationVectorLength($algorithm)
    {
        return openssl_cipher_iv_length($algorithm);
    }

    /**
     * Gets available cipher methods
     */
    public static function getCipherMethods()
    {
        return openssl_get_cipher_methods();
    }
}
