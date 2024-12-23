<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function index(array $filters);
    public function store(array $data);
}
