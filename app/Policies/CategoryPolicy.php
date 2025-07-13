<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'root';
    }

    public function view(User $user, Category $category): bool
    {
        return $user->role === 'root';
    }

    public function create(User $user): bool
    {
        return $user->role === 'root';
    }

    public function update(User $user, Category $category): bool
    {
        return $user->role === 'root';
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->role === 'root';
    }
}
