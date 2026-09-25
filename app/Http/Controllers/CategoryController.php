<?php



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


        $activeNav = $category->name;

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
