<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Guests, customers, operators, and admins can view lists
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Category $category): bool
    {
        // Guests and customers can only view active categories
        if (!$user || $user->role === User::ROLE_CUSTOMER) {
            return $category->is_active;
        }

        // Operators and admins can view any category (active or inactive)
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin(); // Only administrators can create categories
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->isAdmin(); // Only administrators can edit categories
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->isAdmin(); // Only administrators can delete categories
    }
}
