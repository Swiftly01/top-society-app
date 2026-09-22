<?php

namespace App\Presenters;

use App\Models\TeamMember;

class TeamMemberPresenter
{
    /**
     *
     * @return array<string, mixed>
     */
    public static function toCard(TeamMember $member): array
    {
        return [
            'id' => $member->id,
            'name' => $member->name,
            'role' => $member->role,
            'bio' => $member->bio ?? '',
            'photo' => $member->photo?->url,
            'href' => $member->href,
        ];
    }
}