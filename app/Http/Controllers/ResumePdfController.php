<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use TCPDF;

class ResumePdfController extends Controller
{
    public function download(Request $request, string $slug): Response
    {
        $resume = Resume::where('slug', $slug)
            ->where('is_public', true)
            ->with('user')
            ->firstOrFail();

        // 創建 TCPDF 實例
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // 設定文檔資訊
        $pdf->SetCreator('L12CV');
        $pdf->SetAuthor($resume->user->name ?? '未知');
        $pdf->SetTitle($resume->user->name . ' - 履歷');
        $pdf->SetSubject('履歷');
        $pdf->SetKeywords('履歷, 簡歷, CV, Resume');
        
        // 設定頁眉頁腳
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // 設定邊距
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // 添加頁面
        $pdf->AddPage();
        
        // 設定字體 - 使用支援中文的字體
        $pdf->SetFont('stsongstdlight', '', 12);
        
        // 生成履歷內容
        $this->generateResumeContent($pdf, $resume);
        
        $filename = $resume->user->name . '_履歷_' . now()->format('Y-m-d') . '.pdf';
        
        // 輸出 PDF
        return response($pdf->Output($filename, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
    
    private function generateResumeContent(TCPDF $pdf, Resume $resume): void
    {
        $user = $resume->user;
        
        // 標題區域
        $pdf->SetFont('stsongstdlight', 'B', 24);
        $pdf->SetTextColor(102, 126, 234); // 藍色
        $pdf->Cell(0, 15, $user->name ?? '未知', 0, 1, 'C');
        
        $pdf->SetFont('stsongstdlight', '', 16);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, $resume->title, 0, 1, 'C');
        
        $pdf->Ln(10);
        
        // 個人簡介
        if ($resume->summary) {
            $pdf->SetFont('stsongstdlight', 'B', 14);
            $pdf->SetTextColor(102, 126, 234);
            $pdf->Cell(0, 8, '個人簡介', 0, 1);
            
            $pdf->SetFont('stsongstdlight', '', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(0, 6, $resume->summary, 0, 'L');
            $pdf->Ln(5);
        }
        
        // 統計資訊
        $pdf->SetFont('stsongstdlight', 'B', 14);
        $pdf->SetTextColor(102, 126, 234);
        $pdf->Cell(0, 8, '基本資訊', 0, 1);
        
        $pdf->SetFont('stsongstdlight', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        $educationCount = count($resume->education ?? []);
        $experienceCount = count($resume->experience ?? []);
        $daysAgo = abs(round(now()->diffInDays($resume->created_at)));
        
        $pdf->Cell(0, 6, "學歷背景：{$educationCount} 項", 0, 1);
        $pdf->Cell(0, 6, "工作經驗：{$experienceCount} 項", 0, 1);
        $pdf->Cell(0, 6, "建立時間：{$daysAgo} 天前", 0, 1);
        $pdf->Ln(5);
        
        // 學歷背景
        if (!empty($resume->education)) {
            $pdf->SetFont('stsongstdlight', 'B', 14);
            $pdf->SetTextColor(102, 126, 234);
            $pdf->Cell(0, 8, '學歷背景', 0, 1);
            
            $pdf->SetFont('stsongstdlight', '', 12);
            $pdf->SetTextColor(0, 0, 0);
            
            foreach ($resume->education as $edu) {
                $pdf->SetFont('stsongstdlight', 'B', 12);
                $pdf->Cell(0, 6, $edu['school'], 0, 1);
                
                $pdf->SetFont('stsongstdlight', '', 10);
                $pdf->SetTextColor(102, 126, 234);
                $pdf->Cell(0, 5, $edu['degree'] . ' · ' . $edu['field'], 0, 1);
                
                $pdf->SetTextColor(100, 100, 100);
                $pdf->Cell(0, 5, $edu['start_date'] . ' - ' . $edu['end_date'], 0, 1);
                
                if (!empty($edu['description'])) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->MultiCell(0, 5, $edu['description'], 0, 'L');
                }
                $pdf->Ln(3);
            }
            $pdf->Ln(5);
        }
        
        // 工作經驗
        if (!empty($resume->experience)) {
            $pdf->SetFont('stsongstdlight', 'B', 14);
            $pdf->SetTextColor(102, 126, 234);
            $pdf->Cell(0, 8, '工作經驗', 0, 1);
            
            $pdf->SetFont('stsongstdlight', '', 12);
            $pdf->SetTextColor(0, 0, 0);
            
            foreach ($resume->experience as $exp) {
                $pdf->SetFont('stsongstdlight', 'B', 12);
                $pdf->Cell(0, 6, $exp['position'], 0, 1);
                
                $pdf->SetFont('stsongstdlight', '', 10);
                $pdf->SetTextColor(102, 126, 234);
                $pdf->Cell(0, 5, $exp['company'], 0, 1);
                
                $pdf->SetTextColor(100, 100, 100);
                $endDate = $exp['current'] ? '至今' : $exp['end_date'];
                $pdf->Cell(0, 5, $exp['start_date'] . ' - ' . $endDate, 0, 1);
                
                if ($exp['current'] ?? false) {
                    $pdf->SetTextColor(23, 162, 184);
                    $pdf->Cell(0, 5, '目前在職', 0, 1);
                }
                
                if (!empty($exp['description'])) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->MultiCell(0, 5, $exp['description'], 0, 'L');
                }
                $pdf->Ln(3);
            }
        }
        
        // 頁尾
        $pdf->SetY(-20);
        $pdf->SetFont('stsongstdlight', '', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 5, '此履歷由 L12CV 履歷平台生成於 ' . now()->format('Y年m月d日'), 0, 1, 'C');
    }
}