<?php

namespace App\Http\Controllers;

use App\Enums\ContentType;
use App\Presenters\ArticlePresenter;
use App\Presenters\SponsoredFeaturePresenter;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\SponsoredFeatureRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __construct(
        protected ArticleRepositoryInterface $articles,
        protected CategoryRepositoryInterface $categories,
        protected SponsoredFeatureRepositoryInterface $sponsoredFeatures,
    ) {}

    public function index(Request $request): Response
    {
        $activeCategory = $request->string('category', 'all')->toString();

        return Inertia::render('home', [
            'activeNav' => 'Home',

            'featuredArticles' => $this->featuredArticles(),
            'secondaryHeadlines' => $this->secondaryHeadlines(),

            
            'partnership' => $this->partnershipSection(),

            'categoryFilters' => $this->categoryFilters(),
            'activeCategory' => $activeCategory,
            
            'latestArticles' => $this->latestArticles($activeCategory),

            'mostRead' => $this->mostRead(),
            'mostReadPromo' => $this->mostReadPromo(),

            'newsletter' => [
                'title' => 'The Daily Briefing',
                'description' => 'Essential journalism delivered to your inbox every morning. No clutter, just clarity.',
                'ctaLabel' => 'Subscribe',
            ],
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function featuredArticles(): array
    {
        return $this->articles->featured(ContentType::NewsArticle, 3)
            ->map(fn ($article) => ArticlePresenter::toFeatured($article))
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function secondaryHeadlines(): array
    {
        return $this->articles->published(ContentType::NewsArticle, 10)
            ->reject(fn ($article) => $article->is_featured)
            ->take(3)
            ->map(fn ($article) => ArticlePresenter::toCard($article))
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    protected function categoryFilters(): array
    {
        return [
            ['label' => 'All', 'value' => 'all'],
            ...$this->categories->all()
                ->map(fn ($category) => ['label' => $category->name, 'value' => $category->slug])
                ->all(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function latestArticles(string $activeCategory): array
    {
        return $this->articles->latestForHomeGrid(ContentType::NewsArticle, $activeCategory, 4)
            ->map(fn ($article) => ArticlePresenter::toCard($article))
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function mostRead(): array
    {
        return $this->articles->mostRead(ContentType::NewsArticle, 5)
            ->values()
            ->map(fn ($article, int $index) => ArticlePresenter::toMostRead($article, $index + 1))
            ->all();
    }


      protected function partnershipSection(): ?array
    {
        $featured = $this->sponsoredFeatures->activeFeatured();

   //     dd($featured);

        if (! $featured) {
            return null;
        }

        return [
            'tag' => 'Partnership Dossier | Sponsored',
            'title' => 'The Contemporary Vanguard: Curated Brands & Innovations',
            'description' => 'Selected long-form features produced in collaboration with our editorial studio.',
            'disclosureLabel' => 'Disclosed Partnership Features',
            'featured' => SponsoredFeaturePresenter::toFeatured($featured),
            'features' => $this->sponsoredFeatures->activeSecondary(2)
                ->map(fn ($feature) => SponsoredFeaturePresenter::toCard($feature))
                ->all(),
        ];
    }


    /**
     * Still static — this is the small promo card in the "Most Read"
     * sidebar, a separate slot from the Partnership Dossier above. Once
     * this needs to be sponsor-backed too, point it at
     * $this->sponsoredFeatures->activeSecondary() the same way
     * partnershipSection() does. Until then, keep this href pointed at a
     * real published article/feature — an unmatched slug here 404s.
     */
    protected function mostReadPromo(): array
    {
        return [
            'label' => 'Sponsored',
            'title' => 'The Future of Urban Mobility',
            'description' => 'Discover how smart grids are reshaping city transit.',
            'ctaLabel' => 'Read More',
            'href' => '/features/future-of-urban-mobility',
        ];
    }
}
