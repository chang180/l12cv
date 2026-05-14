<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Support\ResumeTemplates;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResumeDocxController extends Controller
{
    public function download(Request $request, string $slug): BinaryFileResponse
    {
        $resume = Resume::where('slug', $slug)
            ->where('is_public', true)
            ->with(['user.projects' => fn ($query) => $query
                ->orderByDesc('is_featured')
                ->orderBy('order')
                ->orderByDesc('created_at'),
            ])
            ->firstOrFail();

        $phpWord = new PhpWord;
        $phpWord->setDefaultFontName('Microsoft JhengHei');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection([
            'marginTop' => 720,
            'marginRight' => 720,
            'marginBottom' => 720,
            'marginLeft' => 720,
        ]);

        $this->writeResume($section, $resume);

        $filename = $resume->user->name.'_履歷_'.now()->format('Y-m-d').'.docx';
        $tempPath = tempnam(storage_path('app'), 'resume-docx-');

        IOFactory::createWriter($phpWord, 'Word2007')->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    private function writeResume(Section $section, Resume $resume): void
    {
        $template = ResumeTemplates::resolve($resume->template ?? null);

        $section->addText($resume->user->name ?? '未知', ['bold' => true, 'size' => 22]);
        $section->addText($resume->title, ['size' => 14]);
        $section->addText('模板：'.$template['name'], ['size' => 9, 'color' => '666666']);
        $section->addTextBreak();

        if ($resume->summary) {
            $this->addHeading($section, '個人簡介');
            $section->addText($resume->summary);
            $section->addTextBreak();
        }

        if (! empty($resume->skills)) {
            $this->addHeading($section, '技能標籤');
            $section->addText(implode('、', $resume->skills));
            $section->addTextBreak();
        }

        if (! empty($resume->languages)) {
            $this->addHeading($section, '語言能力');
            foreach ($resume->languages as $language) {
                $name = $language['name'] ?? '';

                if ($name !== '') {
                    $section->addText($name.'：'.($language['level'] ?? '基礎'));
                }
            }
            $section->addTextBreak();
        }

        if (! empty($resume->certifications)) {
            $this->addHeading($section, '證照和認證');
            foreach ($resume->certifications as $certification) {
                $name = $certification['name'] ?? '';

                if ($name === '') {
                    continue;
                }

                $section->addText($name, ['bold' => true]);
                $details = array_filter([
                    $certification['issuer'] ?? '',
                    $certification['issued_at'] ?? '',
                    $certification['url'] ?? '',
                ]);

                if ($details !== []) {
                    $section->addText(implode(' · ', $details), ['size' => 9, 'color' => '666666']);
                }
            }
            $section->addTextBreak();
        }

        $this->writeProjects($section, $resume);

        if ($template['key'] === 'modern') {
            $this->writeExperience($section, $resume);
            $this->writeEducation($section, $resume);
        } else {
            $this->writeEducation($section, $resume);
            $this->writeExperience($section, $resume);
        }

        $section->addTextBreak();
        $section->addText('此履歷由 L13CV 履歷平台生成於 '.now()->format('Y年m月d日'), ['size' => 8, 'color' => '999999']);
    }

    private function writeProjects(Section $section, Resume $resume): void
    {
        $projects = $resume->user?->projects?->take(3) ?? collect();

        if ($projects->isEmpty()) {
            return;
        }

        $this->addHeading($section, '專案經驗');

        foreach ($projects as $project) {
            $section->addText($project->title, ['bold' => true]);

            $details = array_filter([
                $project->completion_date?->format('Y-m'),
                $project->technologies ? implode('、', array_slice($project->technologies, 0, 5)) : null,
            ]);

            if ($details !== []) {
                $section->addText(implode(' · ', $details), ['size' => 9, 'color' => '666666']);
            }

            if ($project->description) {
                $section->addText($project->description);
            }
        }

        $section->addTextBreak();
    }

    private function writeEducation(Section $section, Resume $resume): void
    {
        if (empty($resume->education)) {
            return;
        }

        $this->addHeading($section, '學歷背景');

        foreach ($resume->education as $education) {
            $section->addText($education['school'] ?? '', ['bold' => true]);
            $section->addText(($education['degree'] ?? '').' · '.($education['field'] ?? ''), ['color' => '2563EB']);
            $section->addText(($education['start_date'] ?? '').' - '.($education['end_date'] ?? ''), ['size' => 9, 'color' => '666666']);

            if (! empty($education['description'])) {
                $section->addText($education['description']);
            }
        }

        $section->addTextBreak();
    }

    private function writeExperience(Section $section, Resume $resume): void
    {
        if (empty($resume->experience)) {
            return;
        }

        $this->addHeading($section, '工作經驗');

        foreach ($resume->experience as $experience) {
            $section->addText($experience['position'] ?? '', ['bold' => true]);
            $section->addText($experience['company'] ?? '', ['color' => '059669']);

            $endDate = ($experience['current'] ?? false) ? '至今' : ($experience['end_date'] ?? '');
            $section->addText(($experience['start_date'] ?? '').' - '.$endDate, ['size' => 9, 'color' => '666666']);

            if ($experience['current'] ?? false) {
                $section->addText('目前在職', ['size' => 9, 'color' => '2563EB']);
            }

            if (! empty($experience['description'])) {
                $section->addText($experience['description']);
            }
        }
    }

    private function addHeading(Section $section, string $text): void
    {
        $section->addText($text, ['bold' => true, 'size' => 14, 'color' => '1F2937']);
    }
}
