<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resume->user->name ?? '未知' }} - 履歷</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Microsoft JhengHei', 'Arial Unicode MS', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* 頁面分頁 */
        .page-break {
            page-break-before: always;
        }
        
        /* 標題區域 */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header h2 {
            font-size: 18px;
            opacity: 0.9;
        }
        
        /* 個人簡介 */
        .summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }
        
        .summary h3 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        /* 區塊標題 */
        .section-title {
            font-size: 20px;
            color: #667eea;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #667eea;
        }
        
        /* 學歷背景 */
        .education-item, .experience-item {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }
        
        .experience-item {
            border-left-color: #17a2b8;
        }
        
        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        
        .item-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .item-company {
            color: #667eea;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .item-date {
            color: #666;
            font-size: 12px;
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .item-description {
            color: #555;
            font-size: 14px;
            line-height: 1.5;
            white-space: pre-line;
        }
        
        /* 統計資訊 */
        .stats {
            display: flex;
            justify-content: space-around;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        /* 頁尾 */
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }
        
        /* 避免在元素內分頁 */
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 標題區域 -->
        <div class="header no-break">
            <h1>{{ $resume->user->name ?? '未知' }}</h1>
            <h2>{{ $resume->title }}</h2>
        </div>
        
        <!-- 個人簡介 -->
        @if($resume->summary)
        <div class="summary no-break">
            <h3>個人簡介</h3>
            <p>{{ $resume->summary }}</p>
        </div>
        @endif
        
        <!-- 統計資訊 -->
        <div class="stats no-break">
            <div class="stat-item">
                <div class="stat-number">{{ count($resume->education ?? []) }}</div>
                <div class="stat-label">學歷背景</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ count($resume->experience ?? []) }}</div>
                <div class="stat-label">工作經驗</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ abs(round(now()->diffInDays($resume->created_at))) }}</div>
                <div class="stat-label">天前建立</div>
            </div>
        </div>
        
        <!-- 學歷背景 -->
        @if(!empty($resume->education))
        <h3 class="section-title">學歷背景</h3>
        @foreach($resume->education as $edu)
        <div class="education-item no-break">
            <div class="item-header">
                <div>
                    <div class="item-title">{{ $edu['school'] }}</div>
                    <div class="item-company">{{ $edu['degree'] }} · {{ $edu['field'] }}</div>
                </div>
                <div class="item-date">{{ $edu['start_date'] }} - {{ $edu['end_date'] }}</div>
            </div>
            @if(!empty($edu['description']))
            <div class="item-description">{{ $edu['description'] }}</div>
            @endif
        </div>
        @endforeach
        @endif
        
        <!-- 工作經驗 -->
        @if(!empty($resume->experience))
        <h3 class="section-title">工作經驗</h3>
        @foreach($resume->experience as $exp)
        <div class="experience-item no-break">
            <div class="item-header">
                <div>
                    <div class="item-title">{{ $exp['position'] }}</div>
                    <div class="item-company">{{ $exp['company'] }}</div>
                </div>
                <div class="item-date">{{ $exp['start_date'] }} - {{ $exp['current'] ? '至今' : $exp['end_date'] }}</div>
            </div>
            @if(!empty($exp['description']))
            <div class="item-description">{{ $exp['description'] }}</div>
            @endif
        </div>
        @endforeach
        @endif
        
        <!-- 頁尾 -->
        <div class="footer">
            <p>此履歷由 L12CV 履歷平台生成於 {{ now()->format('Y年m月d日') }}</p>
        </div>
    </div>
</body>
</html>
