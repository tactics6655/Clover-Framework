<?php

namespace Clover\Classes\Hash;

class Handler
{
    public $algorithm;

    public function __construct($algorithm = 'sha256')
    {
        $this->algorithm = $algorithm;
    }

    public function passwordHash(#[\SensitiveParameter] string $password, string|int|null $algo, array $options = [])
    {
        return password_hash($password, $algo, $options);
    }

    public function hash(string $data, bool|null $binary = false, array|null $options = [])
    {
        return hash($this->algorithm, $data, $binary, $options);
    }

    public function hashFile(string $filename, bool|null $binary = false, array|null $options = [])
    {
        return hash_file($this->algorithm, $filename, $binary, $options);
    }

    public function getAlgorithms()
    {
        return hash_algos();
    }
}
