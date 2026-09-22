<?php

namespace App\Http\Controllers;

use App\Presenters\SponsoredFeaturePresenter;
use App\Repositories\Contracts\SponsoredFeatureRepositoryInterface;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SponsoredFeatureController extends Controller
{
    public function __construct(protected SponsoredFeatureRepositoryInterface $sponsoredFeatures) {}

    public function show(string $slug): Response
    {
        $feature = $this->sponsoredFeatures->findBySlug($slug);

        if (! $feature || ! $feature->isLive()) {
            throw new NotFoundHttpException();
        }

        return Inertia::render('sponsored-features/show', SponsoredFeaturePresenter::toShowPage($feature));
    }
}
