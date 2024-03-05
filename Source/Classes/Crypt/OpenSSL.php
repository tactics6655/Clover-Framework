<?php

declare(strict_types=1);

namespace Clover\Classes\Crypt;

use OpenSSLCertificateSigningRequest;

class OpenSSL
{

    /**
     * Check that openssl extension is loaded
     * 
     * @return bool
     */
    public static function isExtensionLoaded()
    {
        return extension_loaded('openssl');
    }

    /**
     * Encrypts data
     * 
     * @param string $data
     * @param string $algorithm
     * @param string $passphrase
     * @param int $options
     * @param string $initializationVector
     * 
     * @return bool|string
     */
    public static function encrypt(string $data, string $algorithm, string $passphrase, int $options = OPENSSL_RAW_DATA, string $initializationVector = "")
    {
        return openssl_encrypt($data, $algorithm, $passphrase, $options, $initializationVector);
    }

    /**
     * Retrieve the available certificate locations
     * 
     * @return array
     */
    public static function getCertificateLocation()
    {
        return openssl_get_cert_locations();
    }

    /**
     * Returns the subject of a CSR
     * 
     * @param OpenSSLCertificateSigningRequest|string $csr
     * @param bool|null $short_names
     * 
     * @return array|bool
     */
    public static function getCertificateSigningRequestSubject(OpenSSLCertificateSigningRequest|string $csr, bool|null $short_names = true)
    {
        return openssl_csr_get_subject($csr, $short_names);
    }

    /**
     * Gets available digest methods
     * 
     * @param bool|null $aliases
     * 
     * @return array
     */
    public static function getDigestMethods(bool|null $aliases = false)
    {
        return openssl_get_md_methods();
    }

    /**
     * Decrypts data
     * 
     * @param string $data
     * @param string $algorithm
     * @param string $passphrase
     * @param int $options
     * @param string $initializationVector
     * 
     * @return bool|string
     */
    public static function decrypt(string $data, string $algorithm, string $passphrase, int $options = OPENSSL_RAW_DATA, string $initializationVector = "")
    {
        return openssl_decrypt($data, $algorithm, $passphrase, $options, $initializationVector);
    }

    /**
     * Gets the cipher key length.
     * 
     * @param string $algorithm
     * 
     * @return bool|int
     */
    public static function getCipherKeyLength(string $algorithm)
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
