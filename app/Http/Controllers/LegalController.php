<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LegalController extends Controller
{
    /**
     * Every legal/policy document (Privacy Policy, Terms of Service, and
     * anything added later — Cookie Policy, Ethics Standards, etc.) is
     * rendered by this one controller and one page component
     * (`legal/show.tsx`). Adding a new document is just:
     *
     *   1. Add a `'your-slug' => $this->yourDocument()` line to `$documents`.
     *   2. Add the `protected function yourDocument(): array` method below,
     *      returning the same shape (title/meta/toc/sections).
     *
     * No new route, controller, or React page needed. When this becomes
     * CMS-editable, `$documents[$slug]` is the one place to swap for a
     * `LegalDocument::where('slug', $slug)->firstOrFail()` query — the
     * page keeps working unchanged as long as the stored JSON matches
     * this shape.
     */
    public function show(Request $request, string $slug): Response
    {
        $documents = [
            'privacy-policy' => fn () => $this->privacyPolicy(),
            'terms-of-service' => fn () => $this->termsOfService(),
        ];

        if (! isset($documents[$slug])) {
            throw new NotFoundHttpException();
        }

        return Inertia::render('legal/show', $documents[$slug]());
    }

    /**
     * @return array<string, mixed>
     */
    protected function privacyPolicy(): array
    {
        return [
            'breadcrumb' => [
                ['label' => 'Home', 'href' => '/'],
                ['label' => 'Legal & Compliance', 'href' => '/legal/privacy-policy'],
                ['label' => 'Privacy Policy', 'href' => '/legal/privacy-policy'],
            ],
            'eyebrow' => 'Standard Enacted · Rev. 4.2',
            'title' => 'Privacy & Data Protection Policy',
            'description' => 'How TOP SOCIETY collects, protects, governs and processes subscriber and reader data across our print, editorial, digital, and privacy intelligence operations.',
            'meta' => [
                ['label' => 'Effective', 'value' => 'November 24, 2024'],
                ['label' => 'Version', 'value' => '2.1'],
                ['label' => 'Jurisdiction', 'value' => 'Nigeria · UK · EU'],
            ],
            'tocHeading' => 'Page Navigation',
            'toc' => [
                ['id' => 'overview', 'label' => 'Overview of the Sovereign Data Philosophy'],
                ['id' => 'information-we-collect', 'label' => 'Information We Collect'],
                ['id' => 'source-protection', 'label' => 'Journalistic Source Protection & Shield Protocol'],
                ['id' => 'how-we-utilize', 'label' => 'How We Utilize Your Data'],
                ['id' => 'cookies', 'label' => 'Cookies, Tracking & Analytical Technologies'],
                ['id' => 'third-party', 'label' => 'Third-Party Disclosures & Syndicate Sharing'],
                ['id' => 'cross-border', 'label' => 'Cross-Border Transfers & International Desks'],
                ['id' => 'your-rights', 'label' => 'Your Data Rights & Access Requests'],
                ['id' => 'retention', 'label' => 'Retention Schedules & Security Encryption'],
                ['id' => 'supervisory', 'label' => 'Supervisory Authority & Data Protection Officer Contact'],
            ],
            'sidebarNotice' => [
                'title' => 'Sovereign Data Commitment',
                'body' => 'We never sell reader data to third-party advertising exchanges, under any circumstance.',
            ],
            'printLabel' => null,
            'downloadHref' => null,

            'sections' => [
                [
                    'id' => 'overview',
                    'heading' => 'Overview of the Sovereign Data Philosophy',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'TOP SOCIETY operates under a sovereign data protection philosophy: our subscriber and readership data belongs to a distinct, jurisdictionally-conscious commitment to editorial independence. Whether you subscribe from Lagos, London, or elsewhere, our understanding of reader consent and lawful, enterprise-grade data protection governs your specific data protection posture, regardless of where you access our journalism from.'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Global DPO', 'title' => 'Sovereign Data Commitment', 'body' => 'We are held privacy-conscious across our newsroom, engineering, and commercial teams — with no unconsented single-source data sharing across print, digital, or intelligence products.'],
                    ],
                ],
                [
                    'id' => 'information-we-collect',
                    'heading' => 'Information We Collect',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Depending on how you interface with our editorial ecosystem, we identify collected extremes into discrete categories:'],
                        ['type' => 'list', 'items' => [
                            'Account & Subscription Data — name, billing region, and masthead subscription tier.',
                            'Editorial & Salon Engagement — event RSVPs, salon commentary, and reader forum submissions.',
                            'Device & Session Fingerprints — approximate location, browser, and reading-session telemetry.',
                            'Automated Analytical Signals — scroll depth, section affinity, and cross-masthead reading patterns.',
                        ]],
                    ],
                ],
                [
                    'id' => 'source-protection',
                    'heading' => 'Journalistic Source Protection & Shield Protocol',
                    'blocks' => [
                        [
                            'type' => 'notice',
                            'tone' => 'dark',
                            'label' => 'Transparency Shield',
                            'title' => 'Journalistic Source Protection & Shield Protocol',
                            'body' => 'TOP SOCIETY exercises constitutional confidentiality protections under Section 39 of the Constitution of the Federal Republic of Nigeria (as amended), the European Convention on Human Rights (Article 10), and the First Amendment jurisprudence of global publications.',
                            'columns' => [
                                ['title' => 'Zero-Log Policy', 'body' => 'We only print confidential source communications when editorially necessary or legally compelled by valid court order.'],
                                ['title' => 'Encrypted Channels', 'body' => 'Source drops are encrypted end-to-end and reviewed only by cleared verification editors under embargo.'],
                                ['title' => 'Legal Escalation', 'body' => 'Any subpoena directed at source identity is routed first through our legal directorate before any response.'],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'how-we-utilize',
                    'heading' => 'How We Utilize Your Data',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'We process your information strictly within documented legal bases recognized across our sovereign data jurisdictions, including delivering subscription entitlements, editorial personalization, and legitimate fraud-prevention monitoring.'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Subscription Delivery', 'body' => 'Fulfilling access, alert notifications, and billing continuity across print and digital subscriptions.'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Editorial Refinement', 'body' => 'Improving story recommendations, section relevance, and bureau desk resourcing.'],
                    ],
                ],
                [
                    'id' => 'cookies',
                    'heading' => 'Cookies, Tracking & Analytical Technologies',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'We maintain an ultra-lean telemetry footprint. Unlike mass-market tabloids, our editorial properties do not execute intrusive cross-fingerprinting scripts or persistent surveillance pixels.'],
                        ['type' => 'notice', 'tone' => 'default', 'columns' => [
                            ['title' => 'Strictly Necessary Cookies', 'body' => 'Session authentication and subscription-tier gating only.'],
                            ['title' => 'Consent-Gated Tracking', 'body' => 'Analytics are opt-in and can be withdrawn at any time.'],
                        ]],
                    ],
                ],
                [
                    'id' => 'third-party',
                    'heading' => 'Third-Party Disclosures & Syndicate Sharing',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'TOP SOCIETY works with a limited set of infrastructure partners under executed Data Processing Agreements (DPAs): payment processors, content delivery networks, and syndication partners bound by contractual confidentiality obligations.'],
                    ],
                ],
                [
                    'id' => 'cross-border',
                    'heading' => 'Cross-Border Transfers & International Desks',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'As a publication with editorial desks across Lagos, Abuja, London, and New York, all international transfers routinely involve cross-border data flows. All international transfers rely on Standard Contractual Clauses (SCCs) certified by the European Commission, ensuring data originating from Nigerian citizens receives commensurate protections regardless of destination.'],
                    ],
                ],
                [
                    'id' => 'your-rights',
                    'heading' => 'Your Data Rights & Access Requests',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Under global privacy legislation, you hold sovereign control over your personal data. You may exercise any of the following entitlements at any time:'],
                        ['type' => 'notice', 'tone' => 'default', 'columns' => [
                            ['title' => 'Right of Access', 'body' => 'Request an itemized export of your held personal data.'],
                            ['title' => 'Right to Correction', 'body' => 'Correct any inaccurate or outdated personal identifiers.'],
                            ['title' => 'Right to Deletion', 'body' => 'Request erasure, subject to statutory retention obligations.'],
                        ]],
                    ],
                ],
                [
                    'id' => 'retention',
                    'heading' => 'Retention Schedules & Security Encryption',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'We enforce strict data minimization. Subscriber metadata is held strictly for the duration of active subscription plus statutory tax and financial record retention windows required across Nigerian and United Kingdom jurisdictions. All data at rest is encrypted using AES-256 encryption; all data in flight uses TLS 1.3 cryptographic suites with strict Perfect Forward Secrecy.'],
                    ],
                ],
                [
                    'id' => 'supervisory',
                    'heading' => 'Supervisory Authority & Data Protection Officer Contact',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'TOP SOCIETY has appointed a dedicated Data Protection Officer to oversee ongoing compliance and handle reader petitions.'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Contact', 'body' => 'dpo@topsocietynig.com · Lagos Registry Office, Victoria Island'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function termsOfService(): array
    {
        return [
            'breadcrumb' => [
                ['label' => 'Home', 'href' => '/'],
                ['label' => 'Legal & Compliance', 'href' => '/legal/terms-of-service'],
                ['label' => 'Terms of Service', 'href' => '/legal/terms-of-service'],
            ],
            'eyebrow' => 'Codex Vol. XXXIII',
            'title' => 'Terms of Service & Syndicate Protocol',
            'description' => 'The legal covenants, enterprise subscription compacts, intellectual property protections, and syndication stipulations governing institutional and retail engagement with TOP SOCIETY across print, wire, and digital terminals.',
            'meta' => [
                ['label' => 'Effective', 'value' => 'October 24, 2024'],
                ['label' => 'Reading Estimate', 'value' => '34 min'],
                ['label' => 'Jurisdictions', 'value' => 'Lagos · London · New York'],
            ],
            'tocHeading' => 'Table of Contents',
            'toc' => [
                ['id' => 'acceptance', 'label' => '01 · Acceptance of Terms & Editorial Independence'],
                ['id' => 'subscriptions', 'label' => '02 · Subscriptions, Digital Access & Billing Terms'],
                ['id' => 'intellectual-property', 'label' => '03 · Intellectual Property, Copyright & Fair Use'],
                ['id' => 'syndication', 'label' => '04 · Press Syndication, Wire Citations & Republication'],
                ['id' => 'reader-conduct', 'label' => '05 · Reader Conduct, Salon Discussions & Commentary'],
                ['id' => 'whistleblower', 'label' => '06 · Whistleblower & Investigative Dossier Submissions'],
                ['id' => 'disclaimer', 'label' => '07 · Disclaimer of Warranties & Financial Analysis Caveats'],
                ['id' => 'limitation', 'label' => '08 · Limitation of Global Liability'],
                ['id' => 'governing-law', 'label' => '09 · Governing Law, Jurisdiction & Dual Arbitration'],
                ['id' => 'amendments', 'label' => '10 · Amendments & Contacting the Legal Directorate'],
            ],
            'sidebarNotice' => [
                'title' => 'Legal Summary',
                'body' => 'TOP SOCIETY is registered under the Federal Republic of Nigeria and additionally publishes through TOP Society Media Group (UK) Limited.',
            ],
            'printLabel' => 'Print Covenant',
            'downloadHref' => '/legal/terms-of-service/download',
            'downloadLabel' => 'Download PDF Codex',

            'sections' => [
                [
                    'id' => 'acceptance',
                    'number' => '01',
                    'heading' => 'Acceptance of Terms & Editorial Independence',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'By accessing, reading, subscribing to, or syndicating content from TOP SOCIETY (accessible via web, mobile applications, digital editions, and executive terminal relays), you unconditionally accept these legally binding covenants governed under our stringent editorial independence and firewall stipulations.'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Editorial Firewall Notice', 'body' => 'TOP SOCIETY maintains an inviolable firewall separating newsroom operations from commercial partnerships, government relations, and private client behavior. No commercial patron, government agency, or private benefactor holds editorial approval authority.'],
                    ],
                ],
                [
                    'id' => 'subscriptions',
                    'number' => '02',
                    'heading' => 'Subscriptions, Digital Access & Billing Terms',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Subscribers to TOP SOCIETY Premier, Institutional, and executive tiers are billed on a recurring, prorated basis. Credentials are personal, non-transferable, and monitored for concurrent-session abuse; account sharing beyond a licensed household or seat allocation may result in suspension.'],
                        ['type' => 'notice', 'tone' => 'default', 'columns' => [
                            ['title' => 'Billing Cycles & Adjustments', 'body' => 'Automatic billing is advanced in accordance with your chosen tier (monthly, British Pound Sterling, or Dollar Series billing). Tax obligations are calculated under applicable Nigerian VAT, British VAT, or US sales tax jurisdiction.'],
                            ['title' => 'Cancellations & Adjustments', 'body' => 'Subscriptions may be paused, resumed, or terminated at any point, provided access remains active through the currently invoiced period. No partial-period refunds are issued except at TOP SOCIETY\'s sole discretion.'],
                        ]],
                    ],
                ],
                [
                    'id' => 'intellectual-property',
                    'number' => '03',
                    'heading' => 'Intellectual Property, Copyright & Fair Use',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'All journalism, photography, bespoke illustration, and editorial packaging published across TOP SOCIETY properties is protected under the Nigerian Copyright Act (2022), the Berne Convention for the Protection of Literary and Artistic Works, and applicable international intellectual property treaties. Unlicensed extraction, programmatic harvesting, large-language-model training ingestion, and unauthorized commercial republication are strictly prohibited unless formalized through a licensed Syndicate Charter.'],
                    ],
                ],
                [
                    'id' => 'syndication',
                    'number' => '04',
                    'heading' => 'Press Syndication, Wire Citations & Republication',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Peer newsrooms, academic desks, and foreign press referencing TOP SOCIETY investigative reportage must follow syndicate covenants distributed through our syndication desk.'],
                        [
                            'type' => 'notice',
                            'tone' => 'dark',
                            'label' => 'Protocol to-2024-004',
                            'columns' => [
                                ['title' => 'Signed Republication', 'body' => 'Digital publications must carry an executed syndicate charter referencing our investigative masthead before republication in short-form snippets linking back with the canonical URL to secure secondary rights.'],
                                ['title' => 'Editorial Attribution', 'body' => 'Republications must attribute investigative reporting in-line, without altering the original narrative flow or curated framing.'],
                                ['title' => 'Full Syndication', 'body' => 'For full-text syndication through our Syndicate Desk in Lagos, submit a formal request through our Syndication contact channel.'],
                            ],
                        ],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Request Wire Syndication', 'body' => 'Failure to observe these protocols disqualifies future syndication access and may leverage through our Syndicate Desk in Lagos, submit a formal request through our syndication contact.'],
                    ],
                ],
                [
                    'id' => 'reader-conduct',
                    'number' => '05',
                    'heading' => 'Reader Conduct, Salon Discussions & Commentary',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'TOP SOCIETY hosts high-caliber discretionary access across salon commentary sections, digital and editorial forums. Participants are expected to engage with rigorous civility. Hate speech, coordinated disinformation, illegal solicitation, or unauthorized dossier disclosures will result in immediate removal without notice.'],
                    ],
                ],
                [
                    'id' => 'whistleblower',
                    'number' => '06',
                    'heading' => 'Whistleblower & Investigative Dossier Submissions',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Submissions delivered through the TOP SOCIETY Investigations Desk via our cryptographic dropbox or a secure air-gapped device carry conditional confidentiality protection under applicable shield laws.'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Confidential Source Covenant', 'body' => 'Submitting parties that fail to note an outside review while consenting internal review acknowledging whistle-blower internal protection under applicable jurisdiction shield laws.'],
                    ],
                ],
                [
                    'id' => 'disclaimer',
                    'number' => '07',
                    'heading' => 'Disclaimer of Warranties & Financial Analysis Caveats',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Our market coverage, sovereign debt analysis, corporate briefings, and commercial perspective are compiled for journalistic and informational purposes only and do not constitute financial advice.'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Statutory Financial Disclaimer', 'body' => "TOP SOCIETY is not a registered investment advisor, securities broker, or certified financial planner. Neither the publication, its editorial contributors, nor its affiliates accept liability for investment losses, foregone gains, foreign currency exposure, or reliance on materials published."],
                    ],
                ],
                [
                    'id' => 'limitation',
                    'number' => '08',
                    'heading' => 'Limitation of Global Liability',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'To the maximum degree sanctioned under relevant law, TOP SOCIETY, its editors, foreign correspondents, board members, or consequential damages resulting from indirect, incidental, punitive, or consequential damages resulting from access, decisions, market variance, or decisions made in reliance on materials published.'],
                    ],
                ],
                [
                    'id' => 'governing-law',
                    'number' => '09',
                    'heading' => 'Governing Law, Jurisdiction & Dual Arbitration',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'These covenants are structured and governed under the concurrent legal doctrines of the Federal Republic of Nigeria and England and Wales, with mutual regard to conflicts of law statutes.'],
                        ['type' => 'notice', 'tone' => 'default', 'columns' => [
                            ['title' => 'Primary Forum', 'body' => 'For disputes arising above threshold values relating to African-domiciled proceedings, jurisdiction defaults to Lagos High Court arbitration.'],
                            ['title' => 'International Forum', 'body' => 'For cross-border disputes, jurisdiction, arbitration defaults to London Court of International Arbitration (LCIA).'],
                        ]],
                    ],
                ],
                [
                    'id' => 'amendments',
                    'number' => '10',
                    'heading' => 'Amendments & Contacting the Legal Directorate',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'TOP SOCIETY reserves plenary prerogative to update this protocol for ongoing regulatory changes and identified legal requirements. Modifications take precedence immediately upon publication.'],
                        ['type' => 'notice', 'tone' => 'default', 'columns' => [
                            ['title' => 'Lagos Legal Bureau', 'body' => '48 Marina Boulevard, Lagos State, Nigeria. legal@topsocietynig.com'],
                            ['title' => 'London International Bureau', 'body' => '7 Poultry, Bank Interchange, London EC2R 8EJ, United Kingdom. legal.intl@topsocietynig.com'],
                        ]],
                    ],
                ],
            ],

            'acknowledgement' => [
                'label' => 'By clicking below, you confirm agreement with these covenants.',
                'ctaLabel' => 'I Acknowledge These Terms',
            ],
        ];
    }
}
