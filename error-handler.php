<?php
# sembunyikan semua pesan error bawaan PHP
error_reporting(1);
use models\ErrorLog;
/**
 * Fungsi untuk menangai error.
 * 
 * Fungsi ini wajib memiliki 4 paramter dan nama parameter bisa bebas.
 */
function tanganiError ($level, $message, $file, $line) {
    // echo "<div style='padding: 2rem; background: rgba(200, 0, 0, 0.5); color: white'>";
    // echo    "<b>Terjadi Error</b>";
    // echo    "<p>[{$level}] {$message} - {$file}:{$line}</p>";
    // echo "</div>";
    $error = date('Y-m-d H:i:s')." [$level] $message - $file : $line";
    // echo $error;
    // $createLog = error_log($error."\n\n", 3, __DIR__.'/log/error.log');
    $errorLog = new ErrorLog();
    $errorLog->level = $level;
    $errorLog->message = $message;
    $errorLog->file = $file;
    $errorLog->line = $line;
    $errorLog->created_at = \common\Config::now();
    $errorLog->tipe_log = ErrorLog::TIPE_ERROR;
    $errorLog->save();
    // var_dump($createLog);
    return true;
}

register_shutdown_function(function () {
    if (error_get_last()) {
        # ambil error terakhir
        $error = (object) error_get_last();
        
        # hapus semua output sebelum terjadi error
        ob_end_clean();

        tanganiError(
            $error->type, $error->message, $error->file, $error->line
        );
    }
});