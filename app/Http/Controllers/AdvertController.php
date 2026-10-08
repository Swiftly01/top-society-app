<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\AdvertRepositoryInterface;
use App\Services\AdvertService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function __construct(
        protected AdvertRepositoryInterface $adverts,
        protected AdvertService $advertService,
    ) {}

    /**
     * Counts the click, then sends the reader to the advertiser's site.
     * Going through here (rather than linking straight to the advertiser)
     * is what gives the admin a per-advert click count.
     */
    public function click(int $advert): RedirectResponse
    {
        $model = $this->adverts->find($advert);

        // Only live adverts with a plain http(s) destination are followed —
        // an expired, switched-off or malformed one is a 404, not a redirect.
        if (! $model || ! $model->isLive() || ! preg_match('#^https?://#i', $model->target_url)) {
            throw new NotFoundHttpException();
        }

        $this->advertService->recordClick($model);

        return redirect()
            ->away($model->target_url)
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
