<?php

namespace App\Service;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class Message

{
    private static $instances = [];

    public $data = [];
    public $message = [];
    public $status;


    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): Message
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    public function addMessage($message, $type, $status)
    {
        $this->message[] =["message" => $message, "type" => $type, "status" => $status];
    }

    public function getResponse(): JsonResponse
    {
        return new JsonResponse($this->message);
    }

}



