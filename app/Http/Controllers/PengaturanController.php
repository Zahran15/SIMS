<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class PengaturanController extends Controller
{
    public function backupIndex()
    {
        return view('admin.pengaturan.backup.index');
    }

    public function websiteIndex()
    {
        return view('admin.pengaturan.website.index');
    }

    public function downloadBackup()
    {
        $filename = "backup-SIMS-" . date('Y-m-d_H-i-s') . ".sql";
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbName = env('DB_DATABASE', 'SIMS');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPass = env('DB_PASSWORD', '');

        return new StreamedResponse(function () use ($dbHost, $dbName, $dbUser, $dbPass) {
            if ($dbPass) {
                $command = "mysqldump --user={$dbUser} --password='{$dbPass}' --host={$dbHost} {$dbName}";
            } else {
                $command = "mysqldump --user={$dbUser} --host={$dbHost} {$dbName}";
            }

            $handle = popen($command, 'r');
            while (!feof($handle)) {
                echo fread($handle, 1024);
                flush();
            }
            pclose($handle);
        }, 200, [
            "Content-Type" => "application/octet-stream",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function downloadCode()
    {
        $zip = new ZipArchive();
        $fileName = 'backup-code-' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path($fileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Tentukan folder mana yang mau di backup (base_path adalah root project)
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(base_path()),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen(base_path()) + 1);
                    // Filter agar tidak terlalu berat
                    // if (str_contains($relativePath, 'vendor/') || 
                    //     str_contains($relativePath, 'node_modules/') ||
                    //     str_contains($relativePath, 'storage/logs/') ||
                    //     str_contains($relativePath, '.git/')) {
                    //     continue;
                    // }
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function updateWebsite(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $namaFile = $key . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $namaFile);
                $value = 'uploads/settings/' . $namaFile;
            }

            // Simpan ke database
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}