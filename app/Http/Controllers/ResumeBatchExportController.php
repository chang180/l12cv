<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class ResumeBatchExportController extends Controller
{
    public function download(Request $request, string $slug): BinaryFileResponse
    {
        $resume = Resume::where('slug', $slug)
            ->where('is_public', true)
            ->with('user')
            ->firstOrFail();

        $baseName = $resume->user->name.'_履歷_'.now()->format('Y-m-d');
        $zipPath = tempnam(storage_path('app'), 'resume-export-');
        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to create resume export archive.');
        }

        $pdfResponse = app(ResumePdfController::class)->download($request, $slug);
        $zip->addFromString('resume.pdf', $pdfResponse->getContent());

        $docxResponse = app(ResumeDocxController::class)->download($request, $slug);
        $docxPath = $docxResponse->getFile()->getPathname();
        $zip->addFile($docxPath, 'resume.docx');

        $zip->close();
        @unlink($docxPath);

        return response()->download($zipPath, $baseName.'.zip', [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }
}
