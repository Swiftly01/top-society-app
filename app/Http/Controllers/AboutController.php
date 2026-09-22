<?php

namespace App\Http\Controllers;

use App\Presenters\TeamMemberPresenter;
use App\Repositories\Contracts\TeamMemberRepositoryInterface;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function __construct(protected TeamMemberRepositoryInterface $teamMembers) {}
    public function index(): Response
    {
        return Inertia::render('about/index', [
            'eyebrow' => 'Est. 2014 · Abuja · Lagos · London',
            'breadcrumbLabel' => 'About the Society',
            'headingLead' => 'Truth, Distinction, and',
            'headingEmphasis' => 'Uncompromising Journalism.',

            'quote' => [
                'text' => 'We exist not merely to report the passing hour, but to interrogate power, curate distinction, and hold a flawless mirror to African prominence on the global stage.',
                'attribution' => 'The Editorial Board, Foundation Charter',
            ],

            'manifesto' => [
                [
                    'heading' => 'The Mandate',
                    'body' => 'Operating with fierce independence, our newsroom produces peerless long-form inquiries, private market analysis, and definitive accounts of influential figures shaping the Sub-Saharan trajectory.',
                ],
                [
                    'heading' => 'The Standard',
                    'body' => 'Every dispatched sentence adheres to three-tier verification protocols, forensic sourcing mandates, and strict firewalls between our journalistic bureau and commercial patronage.',
                ],
            ],

            'dossier' => [
                'label' => 'Archive Code: 01 · Documented History',
                'title' => "Institutional Dossier",
                'body' => "TOP SOCIETY is Nigeria's authoritative chronicle of statecraft, high enterprise, and transformative contemporary culture.",
                'meta' => [
                    ['label' => 'Bureau Clearance', 'value' => 'A1'],
                    ['label' => 'Status', 'value' => 'Verified Asset'],
                ],
            ],

            'stats' => [
                'eyebrow' => 'Measurable Impact',
                'heading' => 'By The Numbers',
                'sublabel' => 'Audited Metrics · FY 2023-2024',
                'items' => [
                    ['value' => '14+', 'label' => 'Bureau Desks Active — Correspondent Hubs Spanning West Africa, London and Washington'],
                    ['value' => '2.8M', 'label' => 'Monthly Readership Among C-Suite Leaders, Diplomats, Cultural Custodians'],
                    ['value' => '42', 'label' => 'Journalism Awards Recognized Internationally for Financial Investigative Exposes'],
                    ['value' => '100%', 'label' => 'Fact-Checked Dossiers — Zero Uncorroborated Single-Source Stories Published'],
                ],
            ],

            'pillars' => [
                'eyebrow' => 'Our Pillars',
                'heading' => 'The Principles of TOP SOCIETY',
                'description' => 'Engineered for permanence. We reject transient clickbait in favor of forensic insight, literary depth, and historical relevance.',
                'items' => [
                    [
                        'number' => '01',
                        'tag' => 'Integrity',
                        'title' => 'Investigative Rigor',
                        'description' => 'Our special investigations unit dedicates months to cross-referencing public registries, corporate disclosures, and forensic documents before publishing. We protect whistleblowers, pierce corporate veils, and illuminate governance in Sub-Saharan commerce.',
                        'footerNote' => 'Methodology · Forensic & Documented',
                    ],
                    [
                        'number' => '02',
                        'tag' => 'Heritage',
                        'title' => 'Cultural Resonance',
                        'description' => 'African creative, philosophical, and societal intellect is neither emerging nor secondary — it is central. We elevate visual arts, architecture, cinema, and modern literature through critical commentary that respects its gravity and pedigree.',
                        'footerNote' => 'Perspective · Unapologetic High Luxury',
                    ],
                    [
                        'number' => '03',
                        'tag' => 'Sovereign',
                        'title' => 'Global Perspective',
                        'description' => 'From financial summits in Geneva to multilateral forums in Addis Ababa and diaspora cultural epicenters in London, our vantage point links sovereign African realities with macroeconomic movements across the globe.',
                        'footerNote' => 'Reach · Diasporic Authority',
                    ],
                ],
            ],

            'team' => $this->team(),

            // 'team' => [
            //     'eyebrow' => 'Leadership & Masthead',
            //     'heading' => 'Architects of the Record',
            //     'sublabel' => 'Directorial Appointments 2024',
            //     'members' => [
            //         [
            //             'id' => 1,
            //             'name' => 'Dr. Folashade Adeleke',
            //             'role' => 'Editor-in-Chief',
            //             'bio' => 'Formerly senior political correspondent for Reuters and lead fellow at Oxford\'s Reuters Institute.',
            //             'photo' => null,
            //             'href' => '/about/leadership/folashade-adeleke',
            //         ],
            //         [
            //             'id' => 2,
            //             'name' => 'Chukwudi Nwachukwu',
            //             'role' => 'Executive Editor, News',
            //             'bio' => 'Veteran economic analyst who directed statecraft and markets coverage across West Africa for over 15 years.',
            //             'photo' => null,
            //             'href' => '/about/leadership/chukwudi-nwachukwu',
            //         ],
            //         [
            //             'id' => 3,
            //             'name' => 'Amina Bello-Danladi',
            //             'role' => 'Managing Editor, Features',
            //             'bio' => 'Curator of pan-African culture, contemporary art, and long-form narrative profile series.',
            //             'photo' => null,
            //             'href' => '/about/leadership/amina-bello-danladi',
            //         ],
            //         [
            //             'id' => 4,
            //             'name' => 'Kayode Olatunbosun',
            //             'role' => 'Head of Special Inquiries',
            //             'bio' => 'ICIJ collaborator and forensic data investigator. Leads the six-person investigative unit uncovering financial flows.',
            //             'photo' => null,
            //             'href' => '/about/leadership/kayode-olatunbosun',
            //         ],
            //     ],
            // ],

            'chronicle' => [
                'eyebrow' => 'Chronicle',
                'heading' => 'Ten Years of Society Chronicle',
                'sublabel' => '2014 — Present',
                'entries' => [
                    ['year' => '2014', 'tag' => 'Foundation', 'title' => 'Launched as a luxury broadsheet in Victoria Island, Lagos', 'description' => 'Targeting top diplomatic missions and sovereign finance circles.'],
                    ['year' => '2018', 'tag' => 'Expansion', 'title' => 'Inaugurated dedicated federal bureau in Abuja', 'description' => 'Strategic partnerships with global financial press in the City of London.'],
                    ['year' => '2021', 'tag' => 'Special Inquiries', 'title' => 'Established the forensic investigations desk', 'description' => 'Releasing the landmark series on West African sovereign bond restructurings.'],
                    ['year' => '2024', 'tag' => 'Global Dispatch', 'title' => 'Deployment of proprietary real-time diaspora economic monitors', 'description' => 'Digital monograph editions across forty-plus nations.'],
                ],
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function team(): array
    {
        return [
            'eyebrow' => 'Leadership & Masthead',
            'heading' => 'Architects of the Record',
            'sublabel' => 'Directorial Appointments 2024',
            'members' => $this->teamMembers->activeOrdered()
                ->map(fn($member) => TeamMemberPresenter::toCard($member))
                ->all(),
        ];
    }
}
