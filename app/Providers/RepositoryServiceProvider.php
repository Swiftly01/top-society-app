<?php

namespace App\Providers;

use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\MagazineRepositoryInterface;
use App\Repositories\Contracts\MediaRepositoryInterface;
use App\Repositories\Contracts\SponsoredFeatureRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Contracts\TeamMemberRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\ArticleRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\MediaRepository;
use App\Repositories\Eloquent\SponsoredFeatureRepository;
use App\Repositories\Eloquent\TagRepository;
use App\Repositories\Eloquent\TeamMemberRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Contracts\NewsletterEditionRepositoryInterface;
use App\Repositories\Contracts\NewsletterRepositoryInterface;
use App\Repositories\Contracts\NewsletterSubscriptionRepositoryInterface;
use App\Repositories\Eloquent\MagazineRepository;
use App\Repositories\Eloquent\NewsletterEditionRepository;
use App\Repositories\Eloquent\NewsletterRepository;
use App\Repositories\Eloquent\NewsletterSubscriptionRepository;
use App\Services\Contracts\MediaStorageInterface;
use App\Services\Storage\SupabaseStorageService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        ArticleRepositoryInterface::class => ArticleRepository::class,
        CategoryRepositoryInterface::class => CategoryRepository::class,
        TagRepositoryInterface::class => TagRepository::class,
        MediaRepositoryInterface::class => MediaRepository::class,
         MagazineRepositoryInterface::class => MagazineRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        SponsoredFeatureRepositoryInterface::class => SponsoredFeatureRepository::class,
        TeamMemberRepositoryInterface::class => TeamMemberRepository::class,
        NewsletterRepositoryInterface::class => NewsletterRepository::class,
        NewsletterSubscriptionRepositoryInterface::class => NewsletterSubscriptionRepository::class,
        NewsletterEditionRepositoryInterface::class => NewsletterEditionRepository::class,
        MediaStorageInterface::class => SupabaseStorageService::class,
    ];
}
