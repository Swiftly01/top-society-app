<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('contact/index', [
            'eyebrow' => 'Transparency · Accreditation · Dispatches',
            'heading' => 'Connect with the Newsroom',
            'description' => 'Whether transmitting confidential investigations, pursuing strategic commercial sponsorships, or verifying syndicated rights, your correspondence reaches our senior bureau desk without intermediary distortion.',

            'security' => [
                'heading' => 'Direct Contact',
                'description' => 'Kindly direct your enquiries to the appropriate email address below for a faster response.',
                'hotlineLabel' => 'Phone',
                'hotlineValue' => '0816 669 1570',
                'emailLabel' => 'Contact the Editor',
                'emailValue' => 'editor@topsocietynig.com',
                'footnotes' => [
                    'Also reachable at 0818 005 1644',
                ],
            ],

            'desks' => [
                'eyebrow' => 'Dispatch Infrastructure',
                'heading' => 'Specialized Bureau Desks',
                'description' => 'Direct inquiries to the appropriate email address for a faster response.',
                'items' => [
                    [
                        'id' => 'Editor',
                        'title' => 'Contact the Editor',
                        'description' => 'General editorial enquiries, story tips, and correspondence for the newsroom.',
                        'contactLines' => ['editor@topsocietynig.com'],
                        'ctaLabel' => 'Send Email',
                        'ctaHref' => 'mailto:editor@topsocietynig.com',
                        'featured' => true,
                    ],
                    [
                        'id' => 'Business',
                        'title' => 'Business News',
                        'description' => 'Business, markets, and economy coverage for the business desk.',
                        'contactLines' => ['business.desk@topsocietynig.com'],
                        'ctaLabel' => 'Send Email',
                        'ctaHref' => 'mailto:business.desk@topsocietynig.com',
                    ],
                    [
                        'id' => 'Political',
                        'title' => 'Political News',
                        'description' => 'Politics, governance, and policy tips for the political desk.',
                        'contactLines' => ['political.editor@topsocietynig.com'],
                        'ctaLabel' => 'Send Email',
                        'ctaHref' => 'mailto:political.editor@topsocietynig.com',
                    ],
                    [
                        'id' => 'Advertising',
                        'title' => 'Advert Enquiries',
                        'description' => 'Advertising placements, rates, and artwork submissions.',
                        'contactLines' => ['marketing@topsocietynig.com'],
                        'ctaLabel' => 'Send Email',
                        'ctaHref' => 'mailto:marketing@topsocietynig.com',
                    ],
                    [
                        'id' => 'Entertainment',
                        'title' => 'Submit Entertainment Story',
                        'description' => 'Entertainment and showbiz story submissions for the entertainment desk.',
                        'contactLines' => ['entertainment.desk@topsocietynig.com'],
                        'ctaLabel' => 'Send Email',
                        'ctaHref' => 'mailto:entertainment.desk@topsocietynig.com',
                    ],
                    [
                        'id' => 'Social Media',
                        'title' => 'Social Media Enquiries',
                        'description' => 'Enquiries about our social media presence and partnerships.',
                        'contactLines' => ['socialmediamanager@topsocietynig.com'],
                        'ctaLabel' => 'Send Email',
                        'ctaHref' => 'mailto:socialmediamanager@topsocietynig.com',
                    ],
                    [
                        'id' => 'Publisher',
                        'title' => 'Contact the Publisher',
                        'description' => 'Correspondence directed to the Publisher\'s office.',
                        'contactLines' => ['publisher@topsocietynig.com'],
                        'ctaLabel' => 'Send Email',
                        'ctaHref' => 'mailto:publisher@topsocietynig.com',
                    ],
                ],
            ],

            'dispatch' => [
                'eyebrow' => 'Transmission Channel',
                'heading' => 'Direct Dispatch Terminal',
                'description' => 'Every message directed to TOP SOCIETY is processed under strict editorial integrity codes. For source anonymity, submissions can omit organizational affiliations and use transient identity handles.',
                'stats' => [
                    ['label' => 'Investigative Tip Triage Rate', 'value' => '98.4%'],
                    ['label' => 'Commercial Inquiries Cleared (<24h)', 'value' => '94.1%'],
                    ['label' => '7-Day Weekly Flow', 'value' => '1,420 Dispatches', 'live' => true],
                ],
                'routingOptions' => [
                    ['label' => 'General Editorial Newsroom', 'value' => 'editor'],
                    ['label' => 'Business News', 'value' => 'business'],
                    ['label' => 'Political News', 'value' => 'political'],
                    ['label' => 'Advert Enquiries', 'value' => 'advertising'],
                    ['label' => 'Entertainment', 'value' => 'entertainment'],
                    ['label' => 'Social Media', 'value' => 'social-media'],
                    ['label' => 'Publisher', 'value' => 'publisher'],
                ],
                'consentLabel' => 'I confirm that all provided statements are submitted in good faith and consent to review under editorial protocols.',
                'submitLabel' => 'Send Dispatch',
            ],

            // 'offices' => [
            //     'eyebrow' => 'Global Footprint',
            //     'heading' => 'Bureau Office Network',
            //     'sublabel' => 'Coordinated Timezones · WAT / GMT / EST',
            //     'items' => [
            //         [
            //             'id' => 'lagos',
            //             'tag' => 'Primary Headquarters',
            //             'timezoneLabel' => 'WAT',
            //             'city' => 'Lagos',
            //             'region' => 'West Africa Hub',
            //             'address' => 'Top Society Tower, 14 Victoria Island Promenade, Lagos State, Nigeria',
            //             'chiefName' => 'Tunde Babalola-Cole',
            //             'chiefContact' => '+234 (0) 1 295 0000',
            //             'featured' => true,
            //         ],
            //         [
            //             'id' => 'abuja',
            //             'tag' => 'Government Affairs',
            //             'timezoneLabel' => 'WAT',
            //             'city' => 'Abuja',
            //             'region' => 'National Policy Desk',
            //             'address' => 'Capital Crescent, 4th Floor, Maitama District, Federal Capital Territory, Nigeria',
            //             'chiefName' => 'Hadiza Danjuma-Kyari',
            //             'chiefContact' => '+234 (0) 9 873 1120',
            //         ],
            //         [
            //             'id' => 'london',
            //             'tag' => 'European Diaspora',
            //             'timezoneLabel' => 'GMT',
            //             'city' => 'London',
            //             'region' => 'Commerce & Culture Desk',
            //             'address' => '28 Berkeley Square, Mayfair, London W1J 6EN, United Kingdom',
            //             'chiefName' => 'Julian Sterling-Adeyemi',
            //             'chiefContact' => '+44 20 7946 0912',
            //         ],
            //         [
            //             'id' => 'new-york',
            //             'tag' => 'North America',
            //             'timezoneLabel' => 'EST',
            //             'city' => 'New York',
            //             'region' => 'Global Capital Desk',
            //             'address' => 'Rockefeller Plaza, Suite 1800, New York, NY 10112, United States',
            //             'chiefName' => 'Chidinma Vance-Okon',
            //             'chiefContact' => '+1 212 555 8199',
            //         ],
            //     ],
            // ],
        ]);
    }

    /**
     * Handle the Direct Dispatch Terminal submission.
     *
     * Swap the `Log::info` for a `ContactDispatch::create([...])` (plus a
     * migration) or a notification/email dispatch once you're ready to
     * route these somewhere durable.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'routing' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:46080'],
            'consent' => ['accepted'],
        ]);

        Log::info('Contact dispatch received', collect($validated)->except('attachment')->all());

        return back();
    }
}
