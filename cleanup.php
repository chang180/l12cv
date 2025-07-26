<?php
// 要刪除的文件列表
$filesToDelete = [
    'resources/views/livewire/portfolio/create.blade.php',
    'resources/views/livewire/portfolio/edit.blade.php',
    'resources/views/livewire/portfolio/manage.blade.php'
];

// 刪除文件
foreach ($filesToDelete as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "已刪除: $file\n";
    } else {
        echo "文件不存在: $file\n";
    }
}

echo "清理完成!\n";
?>