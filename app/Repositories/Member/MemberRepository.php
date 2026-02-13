<?php

namespace App\Repositories\Member;

use App\Models\Member;
use Illuminate\Pagination\LengthAwarePaginator;

class MemberRepository
{
    /**
     * Get a paginated list of members with their user relationship.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Member::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Find a member by ID.
     */
    public function findById(int $id): ?Member
    {
        return Member::with('user')->find($id);
    }

    /**
     * Find a member by user ID.
     */
    public function findByUserId(int $userId): ?Member
    {
        return Member::with('user')->where('user_id', $userId)->first();
    }

    /**
     * Create a new member.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Member
    {
        $member = Member::create($data);

        return $member->load('user');
    }

    /**
     * Update an existing member.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Member $member, array $data): Member
    {
        $member->update($data);

        return $member->fresh('user');
    }

    /**
     * Delete a member.
     */
    public function delete(Member $member): void
    {
        $member->delete();
    }
}
