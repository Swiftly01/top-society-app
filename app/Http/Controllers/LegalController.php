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
            'advertising-policy' => fn () => $this->advertisingPolicy(),
            'editorial-guidelines' => fn () => $this->editorialGuidelines(),
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
            'eyebrow' => 'Policy',
            'title' => 'Privacy Policy',
            'description' => 'How Top Society Magazine collects, uses, and shares information from visitors and users of topsocietynig.com.',
            'meta' => [
                ['label' => 'Publisher', 'value' => 'Top Society Magazine'],
                ['label' => 'Contact', 'value' => 'editor@topsocietynig.com'],
            ],
            'tocHeading' => 'Page Navigation',
            'toc' => [
                ['id' => 'overview', 'label' => 'Information We Collect & Share'],
                ['id' => 'comments-and-posts', 'label' => 'Comments and Posts'],
                ['id' => 'how-we-use-your-information', 'label' => 'How We Use Your Information'],
                ['id' => 'log-files', 'label' => 'Log Files'],
                ['id' => 'links', 'label' => 'Links'],
                ['id' => 'business-transitions', 'label' => 'Business Transitions'],
                ['id' => 'retention', 'label' => 'How Long We Keep Your Personal Data'],
                ['id' => 'legal-disclaimer', 'label' => 'Legal Disclaimer'],
                ['id' => 'your-rights', 'label' => 'What Rights You Have Over Your Data'],
                ['id' => 'cookies', 'label' => 'Cookies and Similar Technologies'],
                ['id' => 'notification-of-changes', 'label' => 'Notification of Changes'],
            ],
            'sidebarNotice' => [
                'title' => 'Managing Your Preferences',
                'body' => 'You can manage newsletter subscriptions and marketing preferences at any time from the "Emails and marketing" tab of your account, or by unsubscribing directly from any email we send you.',
            ],
            'printLabel' => null,
            'downloadHref' => null,

            'sections' => [
                [
                    'id' => 'overview',
                    'heading' => 'Information We Collect & Share',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society may collect information from our users at several different points on the site. Top Society Magazine is the sole owner of the information collected on topsocietynig.com.'],
                        ['type' => 'paragraph', 'html' => 'We may use and share your information as follows:'],
                        ['type' => 'list', 'items' => [
                            'Information you’ve provided to us, including on our websites.',
                            'Information provided by other companies who’ve obtained your permission to share information about you.',
                            'Information we collect using cookies stored on your device about your use of topsocietynig.com and third-party websites.',
                            'Your IP address.',
                        ]],
                    ],
                ],
                [
                    'id' => 'comments-and-posts',
                    'heading' => 'Comments and Posts',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society users can choose to write comments or posts on the site. To leave a comment, users must submit information including a valid email address. Top Society uses this information to screen out users who leave comments prohibited by our terms and conditions of use, and will not pass this information to any other organisation.'],
                    ],
                ],
                [
                    'id' => 'how-we-use-your-information',
                    'heading' => 'How We Use Your Information',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'In addition to using your information to provide you with requested products or services and general account management and the management of traffic across our network, we may also use your information in the following ways:'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Market Research', 'body' => 'Sometimes we may contact you for market research purposes, for example about a survey. You can opt out from being contacted in this way by signing into your Top Society account and going to the tab "Emails and marketing". This is to monitor and improve our products, services and websites.'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Responding to Your Queries or Complaints', 'body' => 'If you have raised a query or a complaint with us, we may contact you to answer the query or to resolve the issue you have.'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Direct Marketing', 'body' => 'This may include communications by post, telephone or email and by SMS depending on your marketing preferences. This means that we have your agreement to store information about you on the devices you use, and send materials we think may interest you, such as Top Society\'s new offers and updates, as well as tailored advertising.'],
                        ['type' => 'paragraph', 'html' => 'We offer a range of editorial newsletters. You can manage your subscription to these emails through your profile page when you are signed in to your Top Society account. You can decide not to receive these emails at any time and will be able to "unsubscribe" directly by clicking a link in the email or through your email preferences in the tab "Emails and marketing" when you are signed in to your Top Society account.'],
                    ],
                ],
                [
                    'id' => 'log-files',
                    'heading' => 'Log Files',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Like most standard web site servers, we use log files. These include internet protocol (IP) addresses, browser type, internet service provider (ISP), referring/exit pages, platform type, date/time stamp, and number of clicks. We use this information to analyze trends, administer the site, track user movement in the aggregate, and gather broad demographic information for aggregate use. IP addresses, etc. are not linked to personally identifiable information. We may use a tracking utility that uses log files to analyze user movement.'],
                    ],
                ],
                [
                    'id' => 'links',
                    'heading' => 'Links',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society contains links to other sites. Please be aware that we are not responsible for the privacy practices or content of such other sites. We encourage our users to be aware when they leave our site and to read the privacy statements of each and every web site that collects personally identifiable information. This privacy statement applies solely to information collected by Top Society.'],
                    ],
                ],
                [
                    'id' => 'business-transitions',
                    'heading' => 'Business Transitions',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'In the event Top Society goes through a business transition, such as a merger, acquisition by another company or sale of a portion of its assets, users\' personal information will, in most instances, be part of the assets transferred. We may disclose your information to any successors of our business for them to use for the purposes set out in this privacy notice — unless you\'ve asked us not to make it known.'],
                    ],
                ],
                [
                    'id' => 'retention',
                    'heading' => 'How Long We Keep Your Personal Data',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'We keep your personal data for only as long as we need to. How long we need your personal data depends on what we are using it for, as set out in this privacy policy. For example, we may need to use it to answer your queries about a product or service and as a result may keep personal data while you are still using our product or services. We may also need to keep your personal data for accounting purposes, for example, where you have bought a subscription.'],
                        ['type' => 'paragraph', 'html' => 'If we no longer need your data, we will delete it or make it anonymous by removing all details that identify you. If we have asked for your permission to process your personal data and we have no other lawful grounds to continue with that processing, and you withdraw your permission, we will delete your personal data. However, when you unsubscribe from marketing communications, we will keep your email address to ensure that we do not send you any marketing in future.'],
                    ],
                ],
                [
                    'id' => 'legal-disclaimer',
                    'heading' => 'Legal Disclaimer',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society may need to disclose personal information when required by law wherein we have a good-faith belief that such action is necessary to comply with a current judicial proceeding, a court order or legal process; to enable us to comply with any legal or regulatory requirements; to protect or enforce our rights or the rights of any third party; in the detection and prevention of fraud and other crimes; and for the purpose of safeguarding national security.'],
                        ['type' => 'paragraph', 'html' => 'We may share information with credit reference and fraud prevention agencies for use in credit decisions, and for fraud detection and prevention purposes. If false or inaccurate information is provided and fraud is identified, the details will be passed to fraud prevention agencies. Law enforcement agencies may access and use this information. We and other organisations may also access and use this information to prevent fraud and money laundering, including information recorded by fraud prevention agencies in other countries.'],
                    ],
                ],
                [
                    'id' => 'your-rights',
                    'heading' => 'What Rights You Have Over Your Data',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you.'],
                        ['type' => 'notice', 'tone' => 'default', 'body' => 'This does not include any data we are obliged to keep for administrative, legal, or security purposes. We might also be unable to help if the requested information forms part of our journalistic output.'],
                    ],
                ],
                [
                    'id' => 'cookies',
                    'heading' => 'Cookies and Similar Technologies',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'When you create or log in to an online account you agree to our privacy and cookies notice. Otherwise, by continuing to use our websites or mobile services, we may collect personal data from you automatically using cookies or similar technology.'],
                        ['type' => 'notice', 'tone' => 'default', 'columns' => [
                            ['title' => 'What Are Cookies?', 'body' => 'Small bits of text downloaded to your computer or mobile device when you visit a website. Your browser sends them back to the site on every visit, so it can recognise you and tailor what you see.'],
                            ['title' => 'What Do We Use Them For?', 'body' => 'Cookies make using websites much smoother and power lots of useful features. They fall into a few main groups, including the ones needed to provide the service you\'ve asked for.'],
                            ['title' => 'Disabling Cookies', 'body' => 'You can prevent cookies from being set by adjusting your browser settings, though this will affect the functionality of this and several other websites you visit. We recommend leaving cookies enabled.'],
                        ]],
                    ],
                ],
                [
                    'id' => 'notification-of-changes',
                    'heading' => 'Notification of Changes',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Whenever Top Society changes its privacy policy, we will post those changes to this privacy statement, and other places we deem appropriate.'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function advertisingPolicy(): array
    {
        return [
            'breadcrumb' => [
                ['label' => 'Home', 'href' => '/'],
                ['label' => 'Legal & Compliance', 'href' => '/legal/advertising-policy'],
                ['label' => 'Advertising Policy', 'href' => '/legal/advertising-policy'],
            ],
            'eyebrow' => 'Policy',
            'title' => 'Advertising Policy',
            'description' => 'How advertising works on Top Society, what we require from advertisers, and how advertising cookies are used.',
            'meta' => [
                ['label' => 'Publisher', 'value' => 'Top Society Magazine'],
                ['label' => 'Advert Enquiries', 'value' => 'marketing@topsocietynig.com'],
            ],
            'tocHeading' => 'Page Navigation',
            'toc' => [
                ['id' => 'overview', 'label' => 'Overview'],
                ['id' => 'artwork-requirements', 'label' => 'Artwork Requirements & Submission'],
                ['id' => 'rates-and-vetting', 'label' => 'Rates, Vetting & Our Rights'],
                ['id' => 'advertising-cookies', 'label' => 'Advertising Based on Cookies & Similar Technology'],
            ],
            'sidebarNotice' => [
                'title' => 'Advert Enquiries',
                'body' => 'For more information, contact the marketing team via marketing@topsocietynig.com.',
            ],
            'printLabel' => null,
            'downloadHref' => null,

            'sections' => [
                [
                    'id' => 'overview',
                    'heading' => 'Overview',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Since the publication of the very first edition of Top Society, our journalism has been funded in part by advertising.'],
                        ['type' => 'paragraph', 'html' => 'Top Society has been helping clients and partners achieve their advertising objectives, offering a wide variety of rich media formats that help boost brand equity and ultimately yield a high ROI. Whether it\'s a standard advert placement or an innovative custom solution, topsocietynig.com is the place to help you create a compelling and rich ad experience for consumers.'],
                    ],
                ],
                [
                    'id' => 'artwork-requirements',
                    'heading' => 'Artwork Requirements & Submission',
                    'blocks' => [
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Submission Deadline', 'body' => 'All artworks should be submitted three days before publication.'],
                        ['type' => 'notice', 'tone' => 'default', 'columns' => [
                            ['title' => 'Required Image Format', 'body' => 'JPEG, GIF, PNG, or Animated GIF.'],
                            ['title' => 'Rich Media / Video Format', 'body' => 'AVI, MP4, SWF, or FLA.'],
                        ]],
                    ],
                ],
                [
                    'id' => 'rates-and-vetting',
                    'heading' => 'Rates, Vetting & Our Rights',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Advert rates are subject to change without notice but adverts currently running are protected from increase until the duration expires. An advert run starts the day the advert is published on the website and runs for the period paid for.'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Legal Vetting', 'body' => 'All adverts are subject to legal vetting, prior to publication. We are not responsible for the content of other sites linked from our site.'],
                        ['type' => 'paragraph', 'html' => 'We reserve the right to accept, reject and/or cancel any advert at our sole discretion. Adverts are accepted on the condition that the advertiser agrees to indemnify Top Society, its officers and its business partners against any expense or loss by reason of any claims arising from publishing advert(s) by the advertiser online.'],
                        ['type' => 'paragraph', 'html' => 'For more information, contact the marketing team via marketing@topsocietynig.com.'],
                    ],
                ],
                [
                    'id' => 'advertising-cookies',
                    'heading' => 'Advertising Based on Cookies & Similar Technology',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'We also use third-party advertisements on Top Society to support our site. Some of these advertisers may use technology such as cookies and web beacons when they advertise on our site, which will also send these advertisers (such as Google through the Google AdSense program) information including your IP address, your ISP, the browser you used to visit our site, and in some cases, whether you have Flash installed. This is generally used for geo-targeting purposes (showing Lagos real estate ads to someone in Lagos, for example) or showing certain ads based on specific sites visited (such as showing cooking ads to someone who frequents cooking sites).'],
                        ['type' => 'paragraph', 'html' => 'We use personalised online advertising on our sites. This allows us to deliver more relevant advertising to people who visit topsocietynig.com. It works by showing you adverts based on your browsing patterns and the way you have interacted with our sites and apps.'],
                        ['type' => 'notice', 'tone' => 'default', 'body' => 'We do not collect or use information such as your name, email address, postal address or phone number for personalised online advertising.'],
                        ['type' => 'paragraph', 'html' => 'We may also share online data collected through cookies and similar technology with our advertising partners. This means that when you are on another website, you may be shown advertising based on your browsing patterns on topsocietynig.com, and vice versa. Online retargeting allows us and some of our advertising partners to show you advertising based on your browsing patterns and interactions with a site away from our sites — for example, if you visited an online clothes shop, you may start seeing adverts from that same shop displaying special offers or the products you were browsing.'],
                        ['type' => 'paragraph', 'html' => 'We also use personalised online advertising to promote our own products and services on our sites and other platforms, including third-party websites and social media platforms.'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function editorialGuidelines(): array
    {
        return [
            'breadcrumb' => [
                ['label' => 'Home', 'href' => '/'],
                ['label' => 'Legal & Compliance', 'href' => '/legal/editorial-guidelines'],
                ['label' => 'Editorial Guidelines', 'href' => '/legal/editorial-guidelines'],
            ],
            'eyebrow' => 'Publishing / Editorial Guidelines',
            'title' => 'Editorial Guidelines & Ethics',
            'description' => 'The diversity policy, constitutional rights, editorial standards, and publishing conduct that govern everything Top Society publishes.',
            'meta' => [
                ['label' => 'Publisher', 'value' => 'Top Society Magazine'],
                ['label' => 'Feedback', 'value' => 'editor@topsocietynig.com'],
            ],
            'tocHeading' => 'Page Navigation',
            'toc' => [
                ['id' => 'diversity-policy', 'label' => 'Diversity Policy'],
                ['id' => 'constitutional-rights', 'label' => 'Constitutional Rights'],
                ['id' => 'editorial-standards', 'label' => 'Our Editorial Standards & Ethics'],
                ['id' => 'hate-speech-and-bullying', 'label' => 'Hate Speech and Bullying'],
                ['id' => 'safety-and-inappropriate-content', 'label' => 'Safety and Inappropriate Content'],
                ['id' => 'verification-policy', 'label' => 'Verification / Fact Checking Policy'],
                ['id' => 'unnamed-sources', 'label' => 'Unnamed Sources Policy'],
                ['id' => 'minors', 'label' => 'Minors'],
                ['id' => 'victims', 'label' => 'Rape, Sexual Assault and Harassment Victims'],
                ['id' => 'fact-vs-opinion', 'label' => 'Fact vs Opinion, and Satire'],
                ['id' => 'privacy-vs-public-interest', 'label' => 'Privacy vs Public Interest'],
                ['id' => 'viewpoint', 'label' => 'Viewpoint'],
                ['id' => 'plagiarism', 'label' => 'Plagiarism'],
                ['id' => 'corrections-policy', 'label' => 'Corrections Policy'],
                ['id' => 'copyright', 'label' => 'Copyright'],
                ['id' => 'feedback', 'label' => 'Actionable Feedback Policy'],
            ],
            'sidebarNotice' => [
                'title' => 'Have Feedback?',
                'body' => 'If you have a suggestion, criticism, complaint or compliment, contact us at editor@topsocietynig.com and we will get back to you as soon as possible.',
            ],
            'printLabel' => null,
            'downloadHref' => null,

            'sections' => [
                [
                    'id' => 'diversity-policy',
                    'heading' => 'Diversity Policy',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society is a magazine and a news platform dedicated to the objective coverage of the latest headlines, video, audio, current affairs, and developments on topical politics, science, business, health, style, entertainment, culture, and technology, to historical coverage of important (and sometimes forgotten) people, places and events. This places on us the responsibility to cover all sides and shades of opinion all the time.'],
                        ['type' => 'paragraph', 'html' => 'We are an independent news organization focused on serving the Nigerian audience and the world at large with verifiable, timely, well-written, original and unbiased news and features that reflect every segment of society.'],
                    ],
                ],
                [
                    'id' => 'constitutional-rights',
                    'heading' => 'Constitutional Rights',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'The Nigerian Constitution accords all citizens the right to form and express opinion. This fundamental assurance allows citizens to generate, adopt and impart opinions.'],
                        ['type' => 'notice', 'tone' => 'dark', 'label' => 'Section 22 of the Constitution', 'body' => 'The press, radio, television and other agencies of the mass media will at all times be free to uphold the fundamental objectives contained in this Chapter and uphold the responsibility and accountability of the Government to the people.'],
                        ['type' => 'paragraph', 'html' => 'Against this backdrop, the press will have the right to research and propagate information freely to the citizens, while being protected in the course of this duty. At Top Society, stakeholders, editors and reporters will exercise these constitutional rights within the boundary of publishing only factual and accurate information that will help the citizens reach an informed and responsible judgment on democratic process and other public matters.'],
                    ],
                ],
                [
                    'id' => 'editorial-standards',
                    'heading' => 'Our Editorial Standards & Ethics',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society\'s ethical code is based on the willingness of it being accurate, fair and complete, and for its contributors to act with honesty, transparency, and independence, including independence from conflicts of interest. We strictly adhere to the fine principles of journalistic integrity.'],
                        ['type' => 'notice', 'tone' => 'default', 'columns' => [
                            ['title' => 'Truth and Accuracy', 'body' => 'Getting facts from verifiable sources is the cardinal principle of journalism. We always strive for accuracy, incorporating all relevant facts available, and ensure that what goes live is fact-checked.'],
                            ['title' => 'Independence', 'body' => 'Our reporters must be independent voices. Top Society does not act, formally or informally, on behalf of special interests, whether political, corporate, or cultural, and will not be persuaded by religious, ethnic, tribal, political or economic sentiments.'],
                            ['title' => 'Fairness and Objectivity', 'body' => 'Most stories have at least two sides. We ensure our stories are balanced and contextual. Facts will not be embellished, distorted, misrepresented, exaggerated or sensationalized.'],
                        ]],
                        ['type' => 'notice', 'tone' => 'default', 'columns' => [
                            ['title' => 'Humanity & Advocacy', 'body' => 'Our reporters do not harm. Private persons have privacy rights that must be balanced against the public interest in reporting information about them. Top Society will from time to time participate in matters of societal interest, especially against unbridled corruption, police and military brutality, terrorism, and sexual and gender-based violence.'],
                            ['title' => 'Accountability', 'body' => 'Top Society journalists are prohibited from gathering news by way of intimidation or duress. News on matters involving suicide, death, nudity, lunacy or obscenity will be approached with sufficient sensitivity and candour.'],
                        ]],
                    ],
                ],
                [
                    'id' => 'hate-speech-and-bullying',
                    'heading' => 'Hate Speech and Bullying',
                    'blocks' => [
                        ['type' => 'list', 'items' => [
                            'Top Society content must not incite hatred and/or discriminate against people on the basis of their race, ethnic origin, religion, disability, age, nationality, veteran status, sexual orientation, gender identity, etc.',
                            'Our content must not harass, intimidate or bully a person. We will reject any content that attempts to slur or disparage any segment or group in society.',
                        ]],
                    ],
                ],
                [
                    'id' => 'safety-and-inappropriate-content',
                    'heading' => 'Safety and Inappropriate Content',
                    'blocks' => [
                        ['type' => 'list', 'items' => [
                            'Top Society will not post articles that threaten or advocate for harm to oneself or others.',
                            'Top Society will not post content that contains graphic sexual text, image, audio, videos, or games.',
                            'We will not publish articles that contain non-consensual sexual themes or promote a sexual act in exchange for compensation.',
                            'We will not publish any content containing child sexual abuse.',
                            'Top Society commits to not posting any adult themes within family content.',
                            'We will not post any articles containing malicious or unwanted software.',
                            'Top Society will not post any content that promotes illegal activity, or infringes on the legal rights of others.',
                        ]],
                    ],
                ],
                [
                    'id' => 'verification-policy',
                    'heading' => 'Verification / Fact Checking Policy',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Under Top Society\'s policies and guidelines, material from anonymous sources may be used only if:'],
                        ['type' => 'list', 'items' => [
                            'The material is information, and not opinion or speculation, and is vital to the news report.',
                            'The information is not available except under the conditions of anonymity imposed by the source.',
                            'The source is reliable, and in a position to have accurate information.',
                        ]],
                        ['type' => 'paragraph', 'html' => 'We commit to adhering to the two statements contained in The SPJ Code of Ethics on anonymous sources:'],
                        ['type' => 'list', 'items' => [
                            'Identify sources whenever feasible. The public is entitled to as much information as possible on sources\' reliability.',
                            'Always question sources\' motives before promising anonymity. Clarify conditions attached to any promise made in exchange for information. Keep promises.',
                        ]],
                        ['type' => 'paragraph', 'html' => 'Our reporters cannot take information from anonymous sources without the approval of Top Society\'s editorial team. We use information from anonymous sources to tell important stories that would have otherwise gone unreported.'],
                    ],
                ],
                [
                    'id' => 'unnamed-sources',
                    'heading' => 'Unnamed Sources Policy',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Our journalists will at all times seek to openly identify sources for a news story because doing so often bolsters the credibility and sturdiness of a news article. However, Top Society recognises that there are often cases in which the only way to get the facts of a matter is from individuals who would not want to be publicly identified, either for fear of backlash or just out of sheer intent on living quietly away from the spotlight. Editors will exercise case-by-case discretion in such situations.'],
                        ['type' => 'list', 'items' => [
                            'Where possible, Top Society will always include the name of sources for the information we publish.',
                            'Top Society will provide attribution through names, links, and other means to inform the reader about the source of information used in an article.',
                            'While topsocietynig.com prefers not to use confidential sources, we may still use them where the information is deemed credible, important to the readers and where it may negatively impact the livelihood of the source.',
                            'Editors will protect the identity of confidential sources, and adhere to the law related to the legal rights of the reporter and the confidential source.',
                            'Where exclusive news has been aggregated, a link to the primary source of information will be placed in the article.',
                        ]],
                    ],
                ],
                [
                    'id' => 'minors',
                    'heading' => 'Minors',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society journalists will not ordinarily interview or film children under the age of 18 without the consent of a parent or guardian. Except where overriding public interest is readily apparent, children will not be interviewed or photographed at school, parks or other public facilities without the awareness and permission of authorities in charge.'],
                        ['type' => 'notice', 'tone' => 'default', 'body' => 'Top Society will not publish the names, addresses or other identifying details of a child involved in matters of sexual exploitation, trafficking and abuse unless otherwise ruled by judicial authorities or for the purpose of seeking justice.'],
                    ],
                ],
                [
                    'id' => 'victims',
                    'heading' => 'Rape, Sexual Assault and Harassment Victims',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society will refrain from publishing the identity and image of victims of rape and other forms of sexual or gender-based violence, except where consent is given. But the identity of convicted rapists and perpetrators of sexual or gender-based violence will receive no protection from Top Society.'],
                        ['type' => 'paragraph', 'html' => 'All editorial staff members will be regularly trained on gender issues, gender sensitivity and analysis for public good. We are committed to building gender awareness in story writing and ensuring criteria in developing partnership strategies and relationships.'],
                    ],
                ],
                [
                    'id' => 'fact-vs-opinion',
                    'heading' => 'Fact vs Opinion, and Satire',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society will at all times clearly distinguish between fact and opinion articles. Procedurally, a factual article will be apparently so and should be capable of withstanding thorough scrutiny. An opinion article will equally contain some factual elements and not be based on pure fabrication or any figment of a writer\'s imagination.'],
                        ['type' => 'notice', 'tone' => 'default', 'label' => 'Satire', 'body' => 'Satirical content will always be clearly labelled as such. Beyond satire, Top Society journalists will at all times ensure that their news articles are devoid of any form of opinion or bias — any news analysis will contain more facts than viewpoints.'],
                    ],
                ],
                [
                    'id' => 'privacy-vs-public-interest',
                    'heading' => 'Privacy vs Public Interest',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society will at all times exhibit considerable diligence and decency on issues around the privacy of all citizens, except where otherwise dictated by strident public interest. Top Society considers a publication to be in public interest where such a story leads to exposure of violent crime or corrupt practices, especially in public service.'],
                        ['type' => 'paragraph', 'html' => 'Issues bordering on public health and well-being are also considered to be in public interest and will take precedence over the privacy of an individual who poses a threat against them.'],
                    ],
                ],
                [
                    'id' => 'viewpoint',
                    'heading' => 'Viewpoint',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society is committed to publishing opinions, analyses and features that are as diverse and inclusive as possible. However, we encourage our contributors to refrain from submitting articles that explicitly express discrimination or antagonism on the basis of sex, ethnicity, religion, race, sexual orientation, disability, social status or economic status.'],
                        ['type' => 'paragraph', 'html' => 'Readers should be aware that commentaries are solely the responsibility of their respective authors.'],
                    ],
                ],
                [
                    'id' => 'plagiarism',
                    'heading' => 'Plagiarism',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Whether intentional or unintentional, Top Society is strictly against plagiarism. Plagiarism occurs when large portions of a manuscript have been copied from existing previously published resources. Hence, all submissions to Top Society will be cross-checked for plagiarism using applicable software.'],
                        ['type' => 'paragraph', 'html' => 'Submissions found to be plagiarized during initial stages of review will be out-rightly rejected and not considered for publication. If a submission is found to be plagiarized after publication, the Editor-in-Chief will conduct a preliminary investigation, with the help of a suitable committee constituted for the purpose.'],
                    ],
                ],
                [
                    'id' => 'corrections-policy',
                    'heading' => 'Corrections Policy',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society is dedicated to informing its readers when it has made a mistake (however big or small), conveyed the error\'s severity, and provided the correct information as soon as the mistake is brought to attention. Top Society will render retraction for publishing a news story, opinion or advertorial that is found to be dangerous or false. An explanation or apology will be published in a prominent part of our website once an error has been admitted. A considerable right of reply to erroneous or unfairly harmful articles will be given to the subject(s) of such articles, as may be decided by the Editor.'],
                        ['type' => 'paragraph', 'html' => 'When an error is detected within an article, Top Society immediately works to find the correct information and clearly display the corrections wherever possible within the article. The corrections will include:'],
                        ['type' => 'list', 'items' => [
                            'The correct information.',
                            'What was originally published that was found to be incorrect.',
                            'The date (and time, if available) when the change took place.',
                        ]],
                        ['type' => 'paragraph', 'html' => 'The process to report errors from within articles is made easy to understand, by providing an email address and form to contact us at the beginning of each article.'],
                    ],
                ],
                [
                    'id' => 'copyright',
                    'heading' => 'Copyright',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Agii Multimedia Concept will permanently own all intellectual property rights to content submitted by its journalists or commissioned contributors and published in whatever format.'],
                    ],
                ],
                [
                    'id' => 'feedback',
                    'heading' => 'Actionable Feedback Policy',
                    'blocks' => [
                        ['type' => 'paragraph', 'html' => 'Top Society is committed to engaging with our readers and taking action based on their suggestions, complaints, and other feedback. If you have a suggestion, criticism, complaint or compliment, you can contact Top Society at editor@topsocietynig.com and we will get back to you as soon as possible.'],
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
