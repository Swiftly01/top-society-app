<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Inertia\Inertia;
// use Inertia\Response;

// class SearchController extends Controller
// {
//     /**
//      * All mock results below live in memory so filters/sort/pagination can
//      * be demonstrated end-to-end. A real implementation would replace the
//      * body of this method with something like:
//      *
//      *   Article::search($query)
//      *       ->when($dateRange !== 'any', fn ($q) => $q->publishedAfter($dateRange))
//      *       ->orderBy($sortColumn)
//      *       ->paginate(10);
//      *
//      * ...and the prop shape below (query/results/filters/pagination) would
//      * stay the same, so the frontend needs no changes.
//      */
//     public function index(Request $request): Response
//     {
//         $query = $request->string('q', 'Quantum Computing')->toString();
//         $page = max(1, (int) $request->integer('page', 1));
//         $activeDateRange = $request->string('dateRange', 'any')->toString();
//         $activeSort = $request->string('sort', 'relevance')->toString();

//         $allResults = $this->results();
//         $total = 245; // mock total, independent of the in-memory sample size

//         return Inertia::render('search/index', [
//             'query' => $query,
//             'activeSection' => 'Science',
//             'totalResults' => $total,
//             'resultsStart' => $allResults->isEmpty() ? 0 : (($page - 1) * 10) + 1,
//             'resultsEnd' => min($page * 10, $total),
//             'results' => $allResults->values()->all(),
//             'currentPage' => $page,
//             'lastPage' => (int) ceil($total / 10),

//             'filters' => [
//                 'dateRange' => [
//                     ['label' => 'Any time', 'value' => 'any'],
//                     ['label' => 'Past 24 hours', 'value' => '24h'],
//                     ['label' => 'Past week', 'value' => 'week'],
//                     ['label' => 'Past month', 'value' => 'month'],
//                 ],
//                 'activeDateRange' => $activeDateRange,
//                 'sortOptions' => [
//                     ['label' => 'Relevance', 'value' => 'relevance'],
//                     ['label' => 'Newest first', 'value' => 'newest'],
//                     ['label' => 'Oldest first', 'value' => 'oldest'],
//                 ],
//                 'activeSort' => $activeSort,
//                 'contentTypes' => [
//                     ['label' => 'Article', 'value' => 'article', 'checked' => $request->boolean('type_article', true)],
//                     ['label' => 'Video', 'value' => 'video', 'checked' => $request->boolean('type_video', false)],
//                     ['label' => 'Podcast', 'value' => 'podcast', 'checked' => $request->boolean('type_podcast', false)],
//                 ],
//             ],
//         ]);
//     }

//     /**
//      * @return \Illuminate\Support\Collection<int, array<string, mixed>>
//      */
//     protected function results(): \Illuminate\Support\Collection
//     {
//         return collect([
//             [
//                 'id' => 1,
//                 'type' => 'Analysis',
//                 'date' => 'Oct 24, 2024',
//                 'title' => 'The Next Leap in Quantum Computing Infrastructure',
//                 'excerpt' => 'As major tech firms race toward practical applications, the infrastructure supporting quantum computing remains a critical bottleneck. We examine the latest breakthroughs in cooling...',
//                 'byline' => 'By Dr. Elena Rostova',
//                 'image' => null,
//                 'href' => '/articles/next-leap-quantum-infrastructure',
//             ],
//             [
//                 'id' => 2,
//                 'type' => 'Video',
//                 'date' => 'Oct 18, 2024',
//                 'title' => "Inside the Lab Building Tomorrow's Quantum Processors",
//                 'excerpt' => 'Exclusive access to the facilities where engineers are testing next-generation materials for quantum computing arrays. The challenges of maintaining near-absolute zero temperatures are staggering, but...',
//                 'byline' => 'By Marcus Thorne',
//                 'image' => null,
//                 'href' => '/videos/lab-building-quantum-processors',
//             ],
//             [
//                 'id' => 3,
//                 'type' => 'Podcast',
//                 'date' => 'Sep 30, 2024',
//                 'title' => 'The Cryptography Crisis: Preparing for Quantum Supremacy',
//                 'excerpt' => 'In this episode, we discuss how financial institutions are quietly upgrading their security protocols. The imminent arrival of practical quantum computing threatens to render current encryption methods...',
//                 'byline' => 'Hosted by Sarah Jenkins',
//                 'image' => null,
//                 'href' => '/podcasts/cryptography-crisis-quantum-supremacy',
//             ],
//         ]);
//     }
// }



namespace App\Http\Controllers;

use App\Presenters\ArticlePresenter;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    protected const PER_PAGE = 10;

    public function __construct(protected ArticleRepositoryInterface $articles) {}

    /**
     * The date-range/sort/content-type filters wired up in
     * SearchFiltersPanel.tsx don't have a backing implementation here yet
     * — they round-trip correctly (query params in, same params reflected
     * back as `activeDateRange`/`activeSort`/`contentTypes[].checked`) but
     * don't yet narrow the query. `$term`-based search against the
     * articles table is real; go here first when wiring the rest up.
     */
    public function index(Request $request): Response
    {
        $query = $request->string('q', '')->toString();
        $page = max(1, (int) $request->integer('page', 1));

        $results = $query !== ''
            ? $this->articles->search($query, self::PER_PAGE, $page)
            : collect();

        $total = $query !== '' ? $this->articles->countMatchingSearch($query) : 0;

        return Inertia::render('search/index', [
            'query' => $query,
            'activeSection' => 'Science',
            'totalResults' => $total,
            'resultsStart' => $total === 0 ? 0 : (($page - 1) * self::PER_PAGE) + 1,
            'resultsEnd' => min($page * self::PER_PAGE, $total),
            'results' => $results->map(fn ($article) => ArticlePresenter::toSearchResult($article))->all(),
            'currentPage' => $page,
            'lastPage' => max(1, (int) ceil($total / self::PER_PAGE)),

            'filters' => [
                'dateRange' => [
                    ['label' => 'Any time', 'value' => 'any'],
                    ['label' => 'Past 24 hours', 'value' => '24h'],
                    ['label' => 'Past week', 'value' => 'week'],
                    ['label' => 'Past month', 'value' => 'month'],
                ],
                'activeDateRange' => $request->string('dateRange', 'any')->toString(),
                'sortOptions' => [
                    ['label' => 'Relevance', 'value' => 'relevance'],
                    ['label' => 'Newest first', 'value' => 'newest'],
                    ['label' => 'Oldest first', 'value' => 'oldest'],
                ],
                'activeSort' => $request->string('sort', 'relevance')->toString(),
                'contentTypes' => [
                    ['label' => 'Article', 'value' => 'article', 'checked' => $request->boolean('type_article', true)],
                    ['label' => 'Video', 'value' => 'video', 'checked' => $request->boolean('type_video', false)],
                    ['label' => 'Podcast', 'value' => 'podcast', 'checked' => $request->boolean('type_podcast', false)],
                ],
            ],
        ]);
    }
}
