<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class NewsletterPageController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('newsletter/index', [
            'heading' => 'Curated Journalism, Delivered.',
            'subheading' => 'Select from our flagship newsletters for deep dives, daily briefings, and weekend reflections crafted by our expert editors.',

            'plans' => [
                [
                    'id' => 'daily-briefing',
                    'badge' => 'Daily',
                    'title' => 'Daily Briefing',
                    'description' => 'The essential stories to start your day. Politics, global affairs, and market movements synthesized for clarity.',
                    'previewHref' => '/newsletter/daily-briefing/preview',
                ],
                [
                    'id' => 'tech-weekly',
                    'badge' => 'Weekly',
                    'title' => 'Tech Weekly',
                    'description' => 'Cutting-edge analysis on innovation, artificial intelligence, and the business of silicon. Delivered every Thursday.',
                    'previewHref' => '/newsletter/tech-weekly/preview',
                ],
                [
                    'id' => 'weekend-review',
                    'badge' => 'Weekend',
                    'title' => 'The Weekend Review',
                    'description' => 'Long-form journalism, cultural essays, and deep reflections for your Sunday morning coffee reading.',
                    'previewHref' => '/newsletter/weekend-review/preview',
                ],
            ],

            'archive' => [
                [
                    'id' => 1,
                    'date' => 'Nov 12, 2024',
                    'newsletterName' => 'Daily Briefing',
                    'title' => 'The Economic Pivot and Global Markets',
                    'href' => '/newsletter/archive/economic-pivot-global-markets',
                ],
                [
                    'id' => 2,
                    'date' => 'Nov 09, 2024',
                    'newsletterName' => 'The Weekend Review',
                    'title' => 'Architecture in the Age of Climate Change',
                    'href' => '/newsletter/archive/architecture-climate-change',
                ],
                [
                    'id' => 3,
                    'date' => 'Nov 07, 2024',
                    'newsletterName' => 'Tech Weekly',
                    'title' => 'Regulating the Algorithm: New EU Directives',
                    'href' => '/newsletter/archive/regulating-algorithm-eu',
                ],
            ],

            'archiveHref' => '/newsletter/archive',
        ]);
    }
}
