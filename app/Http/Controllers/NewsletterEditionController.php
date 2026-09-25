<?php

namespace App\Http\Controllers;

use App\Presenters\NewsletterPresenter;
use App\Repositories\Contracts\NewsletterEditionRepositoryInterface;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsletterEditionController extends Controller
{
    protected const PER_PAGE = 12;

    public function __construct(protected NewsletterEditionRepositoryInterface $editions) {}

    /**
     * The full "View Full Archive" listing at /newsletter/archive — the
     * newsletter/index page only teases the 3 most recent editions;
     * paginateSentArchive() already existed on the repository for this,
     * it just had no controller/route in front of it yet. paginate()
     * reads ?page= off the current request itself, so no Request
     * parameter is needed here.
     */
    public function index(): Response
    {
        $paginated = $this->editions->paginateSentArchive(self::PER_PAGE);

        return Inertia::render('newsletter/archive', [
            'entries' => collect($paginated->items())
                ->map(fn ($edition) => NewsletterPresenter::toArchiveEntry($edition))
                ->all(),
            'currentPage' => $paginated->currentPage(),
            'lastPage' => $paginated->lastPage(),
        ]);
    }

    public function show(string $slug): Response
    {
        $edition = $this->editions->findBySlug($slug);

        if (! $edition || ! $edition->isSent()) {
            throw new NotFoundHttpException();
        }

        return Inertia::render('newsletter/edition', NewsletterPresenter::toEditionShowPage($edition));
    }
}