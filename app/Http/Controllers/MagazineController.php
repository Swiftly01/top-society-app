<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\MagazineRepositoryInterface;
use App\Services\MagazineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MagazineController extends Controller
{
    public function __construct(
        protected MagazineRepositoryInterface $magazines,
        protected MagazineService $magazineService,
    ) {}

    /**
     * One link for both cases the admin can configure an issue with:
     *  - PDF uploaded: streamed through the app (rather than redirecting
     *    to the storage URL) so the download is forced regardless of the
     *    browser/disk, and works the same whether the file lives on the
     *    local disk or the S3-compatible "supabase" disk — Storage::
     *    download() reads via a stream either way.
     *  - No PDF, only an external link: redirected straight to it. The
     *    PDF wins when an issue somehow has both (see
     *    Magazine::hasReadableResource() / the presenter that builds this
     *    URL in the first place).
     * Either way the click is counted and the frontend never needs to
     * know which case it is — see MagazinePresenter::toCard().
     */
    public function download(string $slug): StreamedResponse|RedirectResponse
    {
        $magazine = $this->magazines->findBySlug($slug);

        if (! $magazine || ! $magazine->isLive() || ! $magazine->hasReadableResource()) {
            throw new NotFoundHttpException();
        }

        $this->magazineService->recordDownload($magazine);

        if ($magazine->hasPdf()) {
            $pdf = $magazine->pdf;
            $downloadName = Str::slug($magazine->title).'.pdf';

            return Storage::disk($pdf->disk)->download($pdf->path, $downloadName);
        }

        return redirect()->away($magazine->external_url);
    }
}
