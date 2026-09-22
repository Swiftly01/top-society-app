// import type { ArticleBlock } from '@/types/article';

// interface ArticleBodyProps {
//     blocks: ArticleBlock[];
// }

// /**
//  * Renders the block-based article body. `paragraph` blocks carry
//  * pre-sanitized HTML from the backend (run it through a markdown/HTML
//  * sanitizer such as HTMLPurifier before it ever reaches this prop —
//  * this component trusts whatever it's given).
//  */
// export function ArticleBody({ blocks }: ArticleBodyProps) {
//     return (
//         <div className="flex flex-col gap-5">
//             {blocks.map((block, index) => {
//                 if (block.type === 'heading') {
//                     return (
//                         <h2 key={block.id} id={block.id} className="mt-4 font-serif text-2xl font-bold text-foreground">
//                             {block.text}
//                         </h2>
//                     );
//                 }

//                 if (block.type === 'quote') {
//                     return (
//                         <blockquote
//                             key={index}
//                             className="border-l-4 border-red-600 py-1 pl-5 font-serif text-xl leading-snug font-semibold italic text-foreground"
//                         >
//                             {block.text}
//                         </blockquote>
//                     );
//                 }

//                 return (
//                     <p
//                         key={index}
//                         className={
//                             block.dropCap
//                                 ? "text-base leading-relaxed text-foreground/90 first-letter:float-left first-letter:mr-2 first-letter:font-serif first-letter:text-6xl first-letter:leading-[0.85] first-letter:font-bold"
//                                 : 'text-base leading-relaxed text-foreground/90'
//                         }
//                         // eslint-disable-next-line react/no-danger
//                         dangerouslySetInnerHTML={{ __html: block.html }}
//                     />
//                 );
//             })}
//         </div>
//     );
// }

interface ArticleBodyProps {
    /** HTML from the CMS (Filament's RichEditor). Must be sanitized server-side before reaching this prop — see the TODO on ArticlePresenter::toBodyHtml(). */
    html: string;
}

/**
 * Renders the article body as one HTML blob rather than a typed block
 * array — this is what a real rich-text editor actually produces, so
 * fighting that with a block model server-side just adds a fragile
 * HTML-to-blocks parser for no benefit. Tag-based styling (below) gives
 * headings, quotes, and the opening drop-cap their look without needing
 * the editor to tag anything specially.
 */
export function ArticleBody({ html }: ArticleBodyProps) {
    return (
        <div
            className="flex flex-col gap-5 text-base leading-relaxed text-foreground/90 [&_blockquote]:border-l-4 [&_blockquote]:border-red-600 [&_blockquote]:py-1 [&_blockquote]:pl-5 [&_blockquote]:font-serif [&_blockquote]:text-xl [&_blockquote]:leading-snug [&_blockquote]:font-semibold [&_blockquote]:text-foreground [&_blockquote]:italic [&_h2]:mt-4 [&_h2]:font-serif [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-foreground [&_h3]:mt-2 [&_h3]:font-serif [&_h3]:text-xl [&_h3]:font-bold [&_h3]:text-foreground [&_a]:text-red-600 [&_a]:underline [&_a]:underline-offset-2 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&>p:first-of-type]:first-letter:float-left [&>p:first-of-type]:first-letter:mr-2 [&>p:first-of-type]:first-letter:font-serif [&>p:first-of-type]:first-letter:text-6xl [&>p:first-of-type]:first-letter:leading-[0.85] [&>p:first-of-type]:first-letter:font-bold"
            // eslint-disable-next-line react/no-danger
            dangerouslySetInnerHTML={{ __html: html }}
        />
    );
}
