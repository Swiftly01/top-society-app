<?php

namespace Database\Seeders;

use App\Enums\NewsletterEditionStatus;
use App\Models\Newsletter;
use App\Models\NewsletterEdition;
use Illuminate\Database\Seeder;

class NewsletterSeeder extends Seeder
{
    public function run(): void
    {
        $daily = Newsletter::query()->updateOrCreate(['slug' => 'daily-briefing'], [
            'name' => 'Daily Briefing',
            'badge' => 'Daily',
            'description' => 'The essential stories to start your day. Politics, global affairs, and market movements synthesized for clarity.',
            'notify_on_article_publish' => true,
            'is_default' => true,
            'is_active' => true,
            'display_order' => 1,
        ]);

        $tech = Newsletter::query()->updateOrCreate(['slug' => 'tech-weekly'], [
            'name' => 'Tech Weekly',
            'badge' => 'Weekly',
            'description' => 'Cutting-edge analysis on innovation, artificial intelligence, and the business of silicon. Delivered every Thursday.',
            'notify_on_article_publish' => false,
            'is_default' => false,
            'is_active' => true,
            'display_order' => 2,
        ]);

        $weekend = Newsletter::query()->updateOrCreate(['slug' => 'weekend-review'], [
            'name' => 'The Weekend Review',
            'badge' => 'Weekend',
            'description' => 'Long-form journalism, cultural essays, and deep reflections for your Sunday morning coffee reading.',
            'notify_on_article_publish' => false,
            'is_default' => false,
            'is_active' => true,
            'display_order' => 3,
        ]);

        if (NewsletterEdition::query()->exists()) {
            return;
        }

        NewsletterEdition::query()->create([
            'newsletter_id' => $daily->id,
            'title' => 'The Economic Pivot and Global Markets',
            'slug' => 'economic-pivot-global-markets',
            'excerpt' => 'A look at this week\'s shifting market signals.',
            'body' => '<p>Full edition content goes here.</p>',
            'status' => NewsletterEditionStatus::Sent->value,
            'sent_at' => now()->subDays(3),
        ]);

        NewsletterEdition::query()->create([
            'newsletter_id' => $weekend->id,
            'title' => 'Architecture in the Age of Climate Change',
            'slug' => 'architecture-climate-change',
            'excerpt' => 'How designers are rethinking permanence.',
            'body' => '<p>Full edition content goes here.</p>',
            'status' => NewsletterEditionStatus::Sent->value,
            'sent_at' => now()->subDays(6),
        ]);

        NewsletterEdition::query()->create([
            'newsletter_id' => $tech->id,
            'title' => 'Regulating the Algorithm: New EU Directives',
            'slug' => 'regulating-algorithm-eu',
            'excerpt' => 'What the new framework actually changes.',
            'body' => '<p>Full edition content goes here.</p>',
            'status' => NewsletterEditionStatus::Sent->value,
            'sent_at' => now()->subDays(8),
        ]);
    }
}