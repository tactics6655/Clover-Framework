<?php

namespace Clover\Classes\Client;

use FTP\Connection;

class FTP
{
    private Connection $context;

    public function __construct(string $hostname, int $port = 21, int $timeout = 90)
    {
        $connection = ftp_connect($hostname, $port, $timeout);

        if (!$connection)
        {
            return;
        }

        $this->context = $connection;
    }

    public function login(string $username, string $password)
    {
        return ftp_login($this->context, $username, $password);
    }

    public function put(Connection $ftp, string $remote_filename, string $local_filename, int $mode = FTP_BINARY, int $offset = 0)
    {
        return ftp_put($this->context, $remote_filename, $local_filename, $mode, $offset);
    }

    public function close()
    {
        return ftp_close($this->context);
    }

    public function delete(string $filename)
    {
        return ftp_delete($this->context, $filename);
    }

    public function downloadFile($stream, $remote_filename, int $mode = FTP_BINARY, int $offset = 0)
    {
        return ftp_fget($this->context, $stream, $remote_filename, $mode, $offset);
    }

    public function uploadFile(string $remote_filename, $stream, int $mode = FTP_BINARY, int $offset = 0)
    {
        return ftp_fput($this->context, $remote_filename, $stream, $mode, $offset);
    }

    public function createDirectory(string $directory)
    {
        return ftp_mkdir($this->context, $directory);
    }

    public function getListOfFiles(string $directory)
    {
        return ftp_mlsd($this->context, $directory);
    }

    public function getCurrentDirectory()
    {
        return ftp_pwd($this->context);
    }

    public function removeDirectory(string $directory)
    {
        return ftp_rmdir($this->context, $directory);
    }

    public function getFileSize(string $filename)
    {
        return ftp_size($this->context, $filename);
    }

}