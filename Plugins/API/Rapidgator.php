<?php

include('./vendor/autoload.php');

use Xanax\Classes\ClientURL as ClientURL;

class Rapidgator
{

    public function loginInformation($token)
    {
        $query = http_build_query(array(
            'token' => $token
        ));

        $cURL = new ClientURL();
        $cURL->option->setURL("https://rapidgator.net/api/v2/user/info")
            ->setReturnTransfer(true)
            ->setPostMethod()
            ->setPostField($query);

        $result = $cURL->execute();

        return $result;
    }

    public function fileInformation($file_id, $token)
    {
        $query = http_build_query(array(
            'file_id' => $file_id,
            'token' => $token
        ));

        $cURL = new ClientURL();
        $cURL->option->setURL("https://rapidgator.net/api/v2/file/info")
            ->setReturnTransfer(true)
            ->setPostMethod()
            ->setPostField($query);

        $result = $cURL->execute();

        return $result;
    }

    public function download($file_id, $token)
    {
        $query = http_build_query(array(
            'file_id' => $file_id,
            'token' => $token
        ));

        $cURL = new ClientURL();
        $cURL->option->setURL("https://rapidgator.net/api/v2/file/download")
            ->setReturnTransfer(true)
            ->setPostMethod()
            ->setPostField($query);

        $result = $cURL->execute();

        return $result;
    }

    public function login($email, $password)
    {
        $query = http_build_query(array(
            'login' => $email,
            'password' => $password
        ));

        $cURL = new ClientURL();
        $cURL->option->setURL("https://rapidgator.net/api/v2/user/login")
            ->setReturnTransfer(true)
            ->setPostMethod()
            ->setPostField($query);

        $result = $cURL->execute();

        return $result;
    }
}
