<?php

namespace App\Repositories\Interfaces;

/**
 * Class UserInterface
 * @package namespace App\Repositories\Interfaces;
 */
interface UserInterface
{
    public function findById($id): array;

    public function findByIdAndType($id, $type): array;

    public function updateBalanceById($value, $id): bool;
}
