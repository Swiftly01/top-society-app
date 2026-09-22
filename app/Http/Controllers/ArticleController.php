<?php

// namespace App\Http\Controllers;

// use Inertia\Inertia;
// use Inertia\Response;

// class ArticleController extends Controller
// {
//     /**
//      * Show a single article.
//      *
//      * The `$slug` param is unused by the mock data below, but it's kept in
//      * the signature (and the route) since a real implementation would do
//      * `Article::where('slug', $slug)->published()->firstOrFail()` here and
//      * feed its fields into the exact same prop shape.
//      */
//     public function show(string $slug): Response
//     {
//         return Inertia::render('articles/show', [
//             'category' => 'Tech',
//             'categoryHref' => '/categories/technology',
//             'title' => 'The Future of Quantum Computing in Global Markets',
//             'publishedAt' => 'October 24, 2024',
//             'author' => [
//                 'name' => 'Eleanor Vance',
//                 'title' => 'Senior Technology Correspondent',
//                 'avatar' => null,
//             ],
//             'heroImage' => null,
//             'heroCaption' => 'The intricate core of a prototype quantum processor. Photography by J. Smith.',

//             'toc' => [
//                 ['id' => 'the-quantum-leap', 'label' => 'The Quantum Leap'],
//                 ['id' => 'market-disruptions', 'label' => 'Market Disruptions'],
//                 ['id' => 'security-implications', 'label' => 'Security Implications'],
//                 ['id' => 'looking-ahead', 'label' => 'Looking Ahead'],
//             ],

//             'body' => [
//                 [
//                     'type' => 'paragraph',
//                     'dropCap' => true,
//                     'html' => 'The race for quantum supremacy is no longer confined to academic laboratories; it has spilled over into the boardrooms of Wall Street and the strategic planning sessions of global conglomerates. As tech giants and nimble startups alike edge closer to building stable, scalable quantum computers, the implications for global markets are becoming increasingly profound and immediate.',
//                 ],
//                 [
//                     'type' => 'paragraph',
//                     'html' => 'Unlike classical computers, which process information in binary bits (0s and 1s), quantum computers use quantum bits, or qubits. These qubits can exist in multiple states simultaneously, thanks to a phenomenon known as superposition. This allows them to process vast amounts of possibilities concurrently, solving complex problems in seconds that would take traditional supercomputers millennia.',
//                 ],
//                 ['type' => 'heading', 'id' => 'the-quantum-leap', 'text' => 'The Quantum Leap'],
//                 [
//                     'type' => 'paragraph',
//                     'html' => 'The immediate impact of this processing power will likely be felt first in sectors reliant on complex modeling and optimization. In finance, quantum algorithms could revolutionize portfolio management, risk analysis, and algorithmic trading by identifying patterns in massive datasets that are currently invisible to classical algorithms.',
//                 ],
//                 [
//                     'type' => 'quote',
//                     'text' => 'We are standing on the precipice of a computational revolution that will fundamentally rewire how we analyze risk and reward.',
//                 ],
//                 [
//                     'type' => 'paragraph',
//                     'html' => "However, the road ahead is fraught with technical challenges. Qubits are notoriously fragile, requiring extreme conditions&mdash;such as temperatures colder than deep space&mdash;to maintain their quantum state. Error correction remains a significant hurdle, as even slight environmental interference can cause 'decoherence,' leading to calculation errors.",
//                 ],
//             ],

//             'trending' => [
//                 'heading' => 'Trending in Tech',
//                 'links' => [
//                     [
//                         'title' => 'AI Regulations Stiffen in EU',
//                         'description' => 'A look at the new policies affecting deep learning models.',
//                         'href' => '/articles/ai-regulations-eu',
//                     ],
//                     [
//                         'title' => 'The Silicon Shortage',
//                         'description' => 'How supply chain issues are impacting next-gen hardware.',
//                         'href' => '/articles/silicon-shortage',
//                     ],
//                 ],
//             ],

//             'readNext' => [
//                 [
//                     'id' => 1,
//                     'category' => 'Business',
//                     'title' => 'The Data Center Boom',
//                     'excerpt' => 'Investors are pouring billions into infrastructure to support the AI revolution.',
//                     'image' => null,
//                     'href' => '/articles/data-center-boom',
//                 ],
//                 [
//                     'id' => 2,
//                     'category' => 'Science',
//                     'title' => 'Materials of the Future',
//                     'excerpt' => 'Discovering the elements that will power the next generation of computing.',
//                     'image' => null,
//                     'href' => '/articles/materials-of-the-future',
//                 ],
//                 [
//                     'id' => 3,
//                     'category' => 'Politics',
//                     'title' => 'Tech Giants Under Scrutiny',
//                     'excerpt' => 'Congressional hearings focus on the monopoly power of leading tech firms.',
//                     'image' => null,
//                     'href' => '/articles/tech-giants-scrutiny',
//                 ],
//             ],
//         ]);
//     }
// }

namespace App\Http\Controllers;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Presenters\ArticlePresenter;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
    public function __construct(protected ArticleRepositoryInterface $articles) {}

    public function show(string $slug): Response|RedirectResponse
    {
        $article = $this->articles->findBySlug($slug);

        if (! $article || ! $article->isPublished()) {
            throw new NotFoundHttpException();
        }

        $article->incrementViews();

        return Inertia::render('articles/show', [
            ...ArticlePresenter::toShowPage($article),
            'trending' => [
                'heading' => 'Trending in '.($article->category?->name ?? 'the Newsroom'),
                'links' => $this->trendingLinks($article),
            ],
            'readNext' => $this->readNext($article),
        ]);
    }

    /**
     * @return array<int, array{title: string, description: string, href: string}>
     */
    protected function trendingLinks(Article $article): array
    {
        return $this->articles
            ->paginateByType($article->type, [
                'category_id' => $article->category_id,
                // paginateByType() is also used for admin listings (where
                // drafts should be visible), so the public-facing "related
                // links" call has to opt into published-only explicitly.
                'status' => ArticleStatus::Published,
            ])
            ->getCollection()
            ->reject(fn ($related) => $related->id === $article->id)
            ->take(2)
            ->map(fn ($related) => [
                'title' => $related->title,
                'description' => $related->excerpt ?? '',
                'href' => "/articles/{$related->slug}",
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function readNext(Article $article): array
    {
        return $this->articles->published($article->type, 6)
            ->reject(fn ($related) => $related->id === $article->id)
            ->take(3)
            ->map(fn ($related) => ArticlePresenter::toRelated($related))
            ->values()
            ->all();
    }
}
