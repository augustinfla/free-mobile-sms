<?php

namespace FreeMobile;

interface SmsInterface
{
    public function getUrlApi(): string;

    public function setUrlApi(string $urlApi): void;

    public function setMessage(string $message): void;

    public function getMessage(): ?string;

    public function reset();

    public function send();
}
