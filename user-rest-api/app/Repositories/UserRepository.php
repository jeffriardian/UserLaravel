<?php

namespace App\Repositories;
use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function index($filters){
        $query = User::query();

        // Filter by active users
        $query->where('active', true);

        // Search by name or email
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Sort by the specified field or default to created_at
        $sortBy = $filters['sortBy'] ?? 'created_at';
        $query->orderBy($sortBy, 'desc');

        // Add orders_count field
        $query->select(['users.id', 'users.name', 'users.email', 'users.created_at'])
              ->withCount('orders as orders_count');

        $perPage = $filters['page'] ?? 1;
        return $query->paginate($perPage);
    }

    public function store(array $data){
       return User::create($data);
    }
}
