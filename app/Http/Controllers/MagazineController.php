<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\MagazineRepositoryInterface;
use App\Services\MagazineService;
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
     * Streams the issue's PDF through the app (rather than redirecting to
     * the storage URL) so the download is forced regardless of the
     * browser/disk, and so every download can be counted. Works the same
     * whether the file lives on the local disk or on the S3-compatible
     * "supabase" disk — Storage::download() reads via a stream either way.
     */
    public function download(string $slug): StreamedResponse
    {
        $magazine = $this->magazines->findBySlug($slug);

        if (! $magazine || ! $magazine->isLive() || ! $magazine->pdf) {
            throw new NotFoundHttpException();
        }

        $this->magazineService->recordDownload($magazine);

        $pdf = $magazine->pdf;
        $downloadName = Str::slug($magazine->title).'.pdf';

        return Storage::disk($pdf->disk)->download($pdf->path, $downloadName);
    }
}
