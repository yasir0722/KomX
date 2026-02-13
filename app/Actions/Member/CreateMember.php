<?php

namespace App\Actions\Member;

use App\Models\Member;
use App\Repositories\Member\MemberRepository;

class CreateMember
{
    public function __construct(
        private readonly MemberRepository $memberRepository,
    ) {}

    /**
     * Create a new member.
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Member
    {
        // Add any pre-creation business logic here
        // e.g. generate membership number, validate business rules, etc.

        if (empty($data['status'])) {
            $data['status'] = 'active';
        }

        if (empty($data['joined_at'])) {
            $data['joined_at'] = now();
        }

        return $this->memberRepository->create($data);
    }
}
