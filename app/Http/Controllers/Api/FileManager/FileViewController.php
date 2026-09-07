<?php

namespace App\Http\Controllers\Api\FileManager;

use App\Core\Services\FileManager\FileAccessService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fayllarni yetkazib berishning yagona avtorizatsiya qilingan nuqtasi.
 *
 * Eski implementatsiya yo'lni birlashtirar va faqat User-Agent'da
 * "Mozilla" borligini tekshirar edi - bu avtorizatsiya emas edi.
 */
class FileViewController extends Controller
{
    public function __construct(
        protected FileAccessService $fileAccessService
    )
    {
    }

    /**
     * @param string $filename
     * @return BinaryFileResponse
     */
    public function __invoke(string $filename): BinaryFileResponse
    {
        $file = $this->fileAccessService->findByName($filename);

        if (empty($file)) {
            abort(Response::HTTP_NOT_FOUND, __('client.File is not found'));
        }

        if (!$this->fileAccessService->allows($file)) {
            // Mavjudlik faktini oshkor qilmaslik uchun ham 404 mumkin edi,
            // lekin mijoz uchun tushunarli bo'lishi muhimroq: fayl bor,
            // ammo huquq yo'q.
            abort(Response::HTTP_FORBIDDEN, __('client.Access to this file is denied'));
        }

        $disk = $this->fileAccessService->resolveDisk($file);

        if (empty($disk)) {
            abort(Response::HTTP_NOT_FOUND, __('client.File is not found'));
        }

        $isPublic = $this->fileAccessService->classify($file) === FileAccessService::ACCESS_PUBLIC;

        // Local drayverda absolyut yo'l orqali qaytarish Range so'rovlarini
        // (audio bo'ylab siljish) Symfony darajasida ishlatadi.
        return response()
            ->file(Storage::disk($disk)->path($file->getFileName()), [
                'Content-Type' => $file->getMimeType(),
                'Content-Disposition' => 'inline; filename="' . addslashes($file->getOriginalName()) . '"',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'SAMEORIGIN',
                'Cache-Control' => $isPublic
                    ? 'public, max-age=86400'
                    : 'private, no-store, max-age=0',
            ]);
    }
}
