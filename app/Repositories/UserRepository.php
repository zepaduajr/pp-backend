<?php

namespace App\Repositories;

use App\Entities\User;
use App\Repositories\Interfaces\UserInterface;

class UserRepository implements UserInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function findById($id): array
    {
        $user = $this->user
            ->find($id);

        return ($user) ? $user->toArray() : [];
    }

    public function findByIdAndType($id, $type): array
    {
        $user = $this->user
            ->where(['id' => $id])
            ->where('type', $type)
            ->get()
            ->first();

        return ($user) ? $user->toArray() : [];
    }

    public function updateBalanceById($value, $id): bool
    {
        return $this->user
            ->where('id', $id)
            ->update(['balance' => $value]);
    }
}
