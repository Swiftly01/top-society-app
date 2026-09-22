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
                'heading' => 'Security Summary',
                'description' => 'Encrypted transmission protocols active for national security, corporate malfeasance, and society investigative documentation.',
                'hotlineLabel' => 'Signal Hotline',
                'hotlineValue' => '+234 810 TOP SEC',
                'emailLabel' => 'Direct Syndicate',
                'emailValue' => 'bureau@topsocietynig.com',
                'footnotes' => [
                    'Zero-knowledge whistleblower protocol',
                    'Average response: < 3 hours',
                ],
            ],

            'desks' => [
                'eyebrow' => 'Dispatch Infrastructure',
                'heading' => 'Specialized Bureau Desks',
                'description' => 'Direct inquiries to specialized verification editors to avoid routing delays.',
                'items' => [
                    [
                        'id' => 'Desk 01',
                        'title' => 'Whistleblower & News Tips',
                        'description' => 'Strictly confidential channel for sensitive corporate intelligence, political exposés, and high-impact cultural leads.',
                        'contactLines' => ['+234 (0) 902 449 0188', 'Public PGP Fingerprint'],
                        'ctaLabel' => 'Access Secure Drop',
                        'ctaHref' => '/contact/secure-drop',
                        'featured' => true,
                    ],
                    [
                        'id' => 'Desk 02',
                        'title' => 'Letters to the Editor',
                        'description' => 'Critiques, op-eds, reader rebuttals, and discourse intended for print and digital publication in the weekly society forum.',
                        'contactLines' => ['letters@topsocietynig.com', 'Max print length: 750 words'],
                        'ctaLabel' => 'Submit Letter',
                        'ctaHref' => '/contact/letters',
                    ],
                    [
                        'id' => 'Desk 03',
                        'title' => 'Brand Partnerships',
                        'description' => 'Bespoke native campaigns, luxury house alignments, print inserts, and private salon symposium sponsorships.',
                        'contactLines' => ['partnerships@topsocietynig.com', 'Media kit 2024 available'],
                        'ctaLabel' => 'Request Media Kit',
                        'ctaHref' => '/contact/partnerships',
                    ],
                    [
                        'id' => 'Desk 04',
                        'title' => 'Press & Syndication',
                        'description' => 'Global content reproduction permissions, high-society event photography credentials, and wire syndication requests.',
                        'contactLines' => ['syndication@topsocietynig.com', 'Clearance time: same day'],
                        'ctaLabel' => 'Inquire Rights',
                        'ctaHref' => '/contact/syndication',
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
                    ['label' => 'General Editorial Newsroom', 'value' => 'general'],
                    ['label' => 'Whistleblower & News Tips', 'value' => 'whistleblower'],
                    ['label' => 'Brand Partnerships', 'value' => 'partnerships'],
                    ['label' => 'Press & Syndication', 'value' => 'syndication'],
                ],
                'consentLabel' => 'I confirm that all provided statements are submitted in good faith and consent to review under editorial protocols.',
                'submitLabel' => 'Send Dispatch',
            ],

            'offices' => [
                'eyebrow' => 'Global Footprint',
                'heading' => 'Bureau Office Network',
                'sublabel' => 'Coordinated Timezones · WAT / GMT / EST',
                'items' => [
                    [
                        'id' => 'lagos',
                        'tag' => 'Primary Headquarters',
                        'timezoneLabel' => 'WAT',
                        'city' => 'Lagos',
                        'region' => 'West Africa Hub',
                        'address' => 'Top Society Tower, 14 Victoria Island Promenade, Lagos State, Nigeria',
                        'chiefName' => 'Tunde Babalola-Cole',
                        'chiefContact' => '+234 (0) 1 295 0000',
                        'featured' => true,
                    ],
                    [
                        'id' => 'abuja',
                        'tag' => 'Government Affairs',
                        'timezoneLabel' => 'WAT',
                        'city' => 'Abuja',
                        'region' => 'National Policy Desk',
                        'address' => 'Capital Crescent, 4th Floor, Maitama District, Federal Capital Territory, Nigeria',
                        'chiefName' => 'Hadiza Danjuma-Kyari',
                        'chiefContact' => '+234 (0) 9 873 1120',
                    ],
                    [
                        'id' => 'london',
                        'tag' => 'European Diaspora',
                        'timezoneLabel' => 'GMT',
                        'city' => 'London',
                        'region' => 'Commerce & Culture Desk',
                        'address' => '28 Berkeley Square, Mayfair, London W1J 6EN, United Kingdom',
                        'chiefName' => 'Julian Sterling-Adeyemi',
                        'chiefContact' => '+44 20 7946 0912',
                    ],
                    [
                        'id' => 'new-york',
                        'tag' => 'North America',
                        'timezoneLabel' => 'EST',
                        'city' => 'New York',
                        'region' => 'Global Capital Desk',
                        'address' => 'Rockefeller Plaza, Suite 1800, New York, NY 10112, United States',
                        'chiefName' => 'Chidinma Vance-Okon',
                        'chiefContact' => '+1 212 555 8199',
                    ],
                ],
            ],
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
