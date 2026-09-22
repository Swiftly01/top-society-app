<?php

// namespace App\Http\Controllers;

// use Inertia\Inertia;
// use Inertia\Response;

// class CategoryController extends Controller
// {
//     /**
//      * Show a category/section front page (e.g. "Technology").
//      *
//      * `$slug` selects which category to render. This mock only implements
//      * "technology", but the shape is identical for every category — a real
//      * version would do `Category::where('slug', $slug)->firstOrFail()` and
//      * `Article::forCategory($category)->latest()->paginate(...)`.
//      */
//     public function show(string $slug): Response
//     {
//         $name = str($slug)->headline();

//         // Maps a category slug to the label used in the compact section nav
//         // (see `App\Support\SiteNavigation::sectionNavItems()`), since the
//         // nav uses short labels ("Tech") while the page heading uses the
//         // full name ("Technology").
//         $activeNav = match ($slug) {
//             'technology' => 'Tech',
//             default => $name,
//         };

//         return Inertia::render('categories/show', [
//             'name' => $name,
//             'slug' => $slug,
//             'activeNav' => $activeNav,
//             'breadcrumb' => [
//                 ['label' => 'Home', 'href' => '/'],
//                 ['label' => $name, 'href' => "/categories/{$slug}"],
//             ],

//             'featuredArticle' => [
//                 'id' => 1,
//                 'category' => 'Quantum Computing',
//                 'title' => 'The Dawn of Quantum Supremacy: What Happens Next?',
//                 'excerpt' => 'Researchers at leading labs claim a major breakthrough in error correction, bringing stable quantum systems closer to reality. This development could shatter current encryption standards and revolutionize complex problem-solving.',
//                 'author' => 'Dr. Elena Rostova',
//                 'readTime' => '12 min read',
//                 'image' => null,
//                 'href' => '/articles/quantum-supremacy-what-happens-next',
//             ],

//             'secondaryArticles' => [
//                 [
//                     'id' => 2,
//                     'category' => 'Silicon',
//                     'title' => 'The Global Race for 2nm Chips Heats Up',
//                     'excerpt' => 'Major foundries announce aggressive timelines for next-generation manufacturing, sparking geopolitical tension.',
//                     'readTime' => '5 min read',
//                     'image' => null,
//                     'href' => '/articles/2nm-chips-race',
//                 ],
//                 [
//                     'id' => 3,
//                     'category' => 'Infrastructure',
//                     'title' => 'Cloud Giants Expand Arctic Data Centers',
//                     'excerpt' => 'Cooling demands drive new construction near the polar circle.',
//                     'image' => null,
//                     'href' => '/articles/arctic-data-centers',
//                 ],
//                 [
//                     'id' => 4,
//                     'category' => 'AI Ethics',
//                     'title' => 'Regulators Struggle with Generative Models',
//                     'excerpt' => 'New frameworks proposed in Europe aim to establish boundaries for training data, but critics argue they stifle innovation.',
//                     'readTime' => '8 min read',
//                     'image' => null,
//                     'href' => '/articles/regulators-generative-models',
//                 ],
//             ],

//             'briefing' => [
//                 'heading' => 'Tech Briefing',
//                 'description' => 'Get the definitive guide to the week in technology, delivered to your inbox every Thursday.',
//                 'ctaLabel' => 'Sign Up',
//             ],

//             'topics' => [
//                 'heading' => 'Top Topics in Tech',
//                 'links' => [
//                     ['label' => 'Artificial Intelligence', 'href' => '/topics/artificial-intelligence'],
//                     ['label' => 'Silicon & Semiconductors', 'href' => '/topics/silicon-semiconductors'],
//                     ['label' => 'Cybersecurity', 'href' => '/topics/cybersecurity'],
//                     ['label' => 'Policy & Regulation', 'href' => '/topics/policy-regulation'],
//                 ],
//             ],
//         ]);
//     }
// }



namespace App\Http\Controllers;

use App\Enums\ArticleStatus;
use App\Enums\ContentType;
use App\Presenters\ArticlePresenter;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepositoryInterface $categories,
        protected ArticleRepositoryInterface $articles,
    ) {}

    public function show(string $slug): Response
    {
        $category = $this->categories->findBySlug($slug);

        if (! $category) {
            throw new NotFoundHttpException();
        }

        $paginated = $this->articles->paginateByType(ContentType::NewsArticle, [
            'category_id' => $category->id,
            'status' => ArticleStatus::Published,
        ], perPage: 5);

        $articles = $paginated->getCollection();
        $featuredArticle = $articles->shift();

        // Maps the compact section-nav label ("Tech") to this category's
        // display name ("Technology") — see App\Support\SiteNavigation for
        // the nav list this has to agree with. Falls back to the display
        // name itself for any category not (yet) in the compact nav.
        $activeNav = match ($category->slug) {
            'technology' => 'Tech',
            default => $category->name,
        };

        return Inertia::render('categories/show', [
            'name' => $category->name,
            'slug' => $category->slug,
            'activeNav' => $activeNav,
            'breadcrumb' => [
                ['label' => 'Home', 'href' => '/'],
                ['label' => $category->name, 'href' => "/categories/{$category->slug}"],
            ],

            'featuredArticle' => $featuredArticle
                ? ArticlePresenter::toCard($featuredArticle)
                : null,

            'secondaryArticles' => $articles
                ->map(fn ($article) => ArticlePresenter::toCard($article))
                ->values()
                ->all(),

            'briefing' => [
                'heading' => strtoupper($category->name).' Briefing',
                'description' => "Get the definitive guide to the week in {$category->name}, delivered to your inbox every Thursday.",
                'ctaLabel' => 'Sign Up',
            ],

            'topics' => [
                'heading' => "Top Topics in {$category->name}",
                'links' => $category->children
                    ->map(fn ($child) => ['label' => $child->name, 'href' => "/categories/{$child->slug}"])
                    ->all(),
            ],
        ]);
    }
}
