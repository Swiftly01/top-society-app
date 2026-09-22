<?php

namespace App\Services;

use App\Models\TeamMember;
use App\Repositories\Contracts\TeamMemberRepositoryInterface;

class TeamMemberService
{
    public function __construct(protected TeamMemberRepositoryInterface $teamMembers) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): TeamMember
    {
        $attributes['display_order'] ??= $this->nextDisplayOrder();

        return $this->teamMembers->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(TeamMember $member, array $attributes): TeamMember
    {
        return $this->teamMembers->update($member, $attributes);
    }

    public function delete(TeamMember $member): bool
    {
        return $this->teamMembers->delete($member);
    }

    protected function nextDisplayOrder(): int
    {
        return (int) $this->teamMembers->all()->max('display_order') + 1;
    }
}