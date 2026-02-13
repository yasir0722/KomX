<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Http\Resources\Member\MemberResource;
use App\Models\Member;
use App\Services\Member\MemberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class MemberController extends Controller
{
    public function __construct(
        private readonly MemberService $memberService,
    ) {}

    /**
     * List all members (paginated).
     */
    public function index(): AnonymousResourceCollection
    {
        $members = $this->memberService->listMembers();

        return MemberResource::collection($members);
    }

    /**
     * Show a single member.
     */
    public function show(Member $member): MemberResource
    {
        return new MemberResource($member->load('user'));
    }

    /**
     * Create a new member.
     */
    public function store(StoreMemberRequest $request): JsonResponse
    {
        $member = $this->memberService->createMember($request->validated());

        return (new MemberResource($member))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Update an existing member.
     */
    public function update(UpdateMemberRequest $request, Member $member): MemberResource
    {
        $member = $this->memberService->updateMember($member, $request->validated());

        return new MemberResource($member);
    }

    /**
     * Delete a member.
     */
    public function destroy(Member $member): JsonResponse
    {
        $this->memberService->deleteMember($member);

        return response()->json([
            'message' => 'Member deleted successfully.',
        ]);
    }
}
