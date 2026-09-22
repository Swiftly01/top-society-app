<?php

namespace App\Repositories\Eloquent;

use App\Models\TeamMember;
use App\Repositories\Contracts\TeamMemberRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepository<TeamMember>
 */
class TeamMemberRepository extends BaseRepository implements TeamMemberRepositoryInterface
{
    public function __construct(TeamMember $model)
    {
        parent::__construct($model);
    }

    protected function query(): Builder
    {
        return parent::query()->with('photo');
    }

    public function activeOrdered(): Collection
    {
        return $this->query()->active()->ordered()->get();
    }
}