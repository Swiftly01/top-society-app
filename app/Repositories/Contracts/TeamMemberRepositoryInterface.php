<?php

namespace App\Repositories\Contracts;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends RepositoryInterface<TeamMember>
 */
interface TeamMemberRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<int, TeamMember>
     */
    public function activeOrdered(): Collection;
}