<?php

// namespace App\Http\Controllers;

// use Inertia\Inertia;
// use Inertia\Response;

// class EditorsPicksController extends Controller
// {
//     public function index(): Response
//     {
//         return Inertia::render('editors-picks/index', [
//             'activeNav' => 'Culture',
//             'title' => "Editor's Picks",
//             'description' => 'A curated selection of our most essential reporting, deep dives, and cultural commentary from this week. Handpicked by the editorial board to offer perspective beyond the daily news cycle.',

//             'curator' => [
//                 'name' => 'Eleanor Vance',
//                 'title' => 'Editor-in-Chief',
//                 'avatar' => null,
//             ],

//             'featured' => [
//                 'id' => 1,
//                 'badge' => 'Architecture',
//                 'title' => 'The Brutalist Revival: Why Concrete is Cool Again',
//                 'excerpt' => 'After decades of being reviled as cold and dystopian, brutalist architecture is experiencing a cultural renaissance among a new generation of designers and city planners.',
//                 'author' => 'Marcus Sterling',
//                 'image' => null,
//                 'href' => '/articles/brutalist-revival',
//             ],

//             'secondary' => [
//                 [
//                     'id' => 2,
//                     'badge' => 'Technology',
//                     'title' => 'The Silent Crisis of Legacy Code',
//                     'excerpt' => 'Hidden beneath the shiny interfaces of modern banking apps lies a tangled web of 40-year-old software holding the entire financial system together.',
//                     'author' => 'Dr. Aris Thorne',
//                     'image' => null,
//                     'href' => '/articles/silent-crisis-legacy-code',
//                 ],
//                 [
//                     'id' => 3,
//                     'badge' => 'Investigation',
//                     'title' => 'Shadow Markets: The Trade in Stolen Data',
//                     'excerpt' => 'A six-month investigation into the forums where digital identities are bought and sold for fractions of a cent.',
//                     'author' => 'The Investigative Desk',
//                     'image' => null,
//                     'href' => '/articles/shadow-markets-stolen-data',
//                 ],
//             ],
//         ]);
//     }
// }



namespace App\Http\Controllers;

use App\Enums\ContentType;
use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Inertia\Inertia;
use Inertia\Response;

class EditorsPicksController extends Controller
{
    public function __construct(protected ArticleRepositoryInterface $articles) {}

    public function index(): Response
    {
        // This page assumes at least one article is marked Featured in
        // Filament — reasonable for an "Editor's Picks" page, but note
        // that an empty featured set means `$featured` falls back to
        // null and the page will need a real empty-state if that's ever
        // a realistic possibility for this newsroom.
        $picks = $this->articles->featured(ContentType::NewsArticle, 3);
        $featured = $picks->shift();

        return Inertia::render('editors-picks/index', [
            'activeNav' => 'Culture',
            'title' => "Editor's Picks",
            'description' => 'A curated selection of our most essential reporting, deep dives, and cultural commentary from this week. Handpicked by the editorial board to offer perspective beyond the daily news cycle.',

            // No "curator" concept exists in the schema (it's editorial
            // framing, not a distinct role) — surfacing the most recent
            // Editor/Administrator to have published something is a
            // reasonable stand-in until/unless this needs to be a
            // specific, manually-chosen byline.
            'curator' => [
                'name' => $featured?->author?->name ?? 'The Editorial Board',
                'title' => 'Editor-in-Chief',
                'avatar' => null,
            ],

            'featured' => $featured ? $this->toPick($featured) : null,

            'secondary' => $picks->map(fn ($article) => $this->toPick($article))->values()->all(),
        ]);
    }

    protected function toPick(Article $article): array
    {
        return [
            'id' => $article->id,
            'badge' => $article->category?->name ?? 'Featured',
            'title' => $article->title,
            'excerpt' => $article->excerpt ?? '',
            'author' => $article->author?->name ?? 'TOP SOCIETY Staff',
            'image' => $article->featuredImage?->url,
            'href' => "/articles/{$article->slug}",
        ];
    }
}
