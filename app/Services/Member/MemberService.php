<?php

namespace App\Services\Member;

use App\Models\Member;
use App\Repositories\Member\MemberRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class MemberService
{
    public function __construct(
        private readonly MemberRepository $memberRepository,
    ) {}

    /**
     * Get a paginated list of members.
     */
    public function listMembers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->memberRepository->paginate($perPage);
    }

    /**
     * Create a new member.
     *
     * @param  array<string, mixed>  $data
     */
    public function createMember(array $data): Member
    {
        return $this->memberRepository->create($data);
    }

    /**
     * Update an existing member.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateMember(Member $member, array $data): Member
    {
        return $this->memberRepository->update($member, $data);
    }

    /**
     * Delete a member.
     */
    public function deleteMember(Member $member): void
    {
        $this->memberRepository->delete($member);
    }
}
