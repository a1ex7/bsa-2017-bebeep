<?php

namespace App\Services\Requests;

interface SaveCarRequestInterface
{
    public function getBrand() : string;

    public function getModel() : string;

    public function getColor() : string;

    public function getBody() : string;

    public function getSeats() : int;

    public function getYear() : int;

    public function getPhoto();

    public function getUserId() : int;

    public function getCarBrandId(): ?int;
}
