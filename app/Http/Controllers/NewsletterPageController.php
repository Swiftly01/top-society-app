<?php

namespace App\Http\Controllers;

use App\Presenters\NewsletterPresenter;
use App\Repositories\Contracts\NewsletterEditionRepositoryInterface;
use App\Repositories\Contracts\NewsletterRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsletterPageController extends Controller
{
    public function __construct(
        protected NewsletterRepositoryInterface $newsletters,
        protected NewsletterEditionRepositoryInterface $editions,
    ) {}

    public function index(): Response
    {
        return Inertia::render('newsletter/index', [
            'heading' => 'Curated Journalism, Delivered.',
            'subheading' => 'Select from our flagship newsletters for deep dives, daily briefings, and weekend reflections crafted by our expert editors.',

            'plans' => $this->newsletters->activeOrdered()
                ->map(fn ($newsletter) => NewsletterPresenter::toPlan($newsletter))
                ->all(),

            'archive' => $this->editions->recentSent(3)
                ->map(fn ($edition) => NewsletterPresenter::toArchiveEntry($edition))
                ->all(),

            'archiveHref' => route('newsletter.archive.index'),
        ]);
    }

    /**
     * "Preview Latest Edition" on a newsletter's signup card — there's no
     * standalone preview page; this just finds that newsletter's most
     * recently sent edition and forwards to its normal archive page. 404s
     * if the newsletter doesn't exist or hasn't sent anything yet, rather
     * than silently landing on a blank page.
     */
    public function preview(string $newsletter): RedirectResponse
    {
        $newsletterModel = $this->newsletters->findBySlug($newsletter);

        if (! $newsletterModel) {
            throw new NotFoundHttpException();
        }

        $edition = $this->editions->latestSentFor($newsletterModel->id);

        if (! $edition) {
            throw new NotFoundHttpException();
        }

        return redirect()->route('newsletter.archive.show', $edition->slug);
    }
}