<?php

namespace App\Helpers;

use App\Models\Video;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;

/**
 * Helper to stream a video file with HTTP range support.
 */
class VideoStream
{
    /**
     * Return a StreamedResponse for the given video.
     */
    public static function stream(Video $video, Request $request)
    {
        $disk = $video->disk ?? 'public';
        // $path = $video->file_path;
        $path = $video->file;
        if (!Storage::disk($disk)->exists($path)) {
            abort(404, 'Video file not found');
        }
        $fullPath = Storage::disk($disk)->path($path);
        // dd($fullPath);

        $size = filesize($fullPath);
        $mime = $video->mime_type ?? 'application/octet-stream';

        $headers = [
            'Content-Type' => $mime,
            'Accept-Ranges' => 'bytes',
        ];

        $range = $request->header('Range');
        if ($range) {
            // Parse the Range header (e.g. "bytes=0-1023")
            list(, $range) = explode('=', $range, 2);
            if (strpos($range, ',') !== false) {
                $range = explode(',', $range)[0];
            }
            $range = trim($range);
            $start = 0;
            $end = $size - 1;
            if (strpos($range, '-') === 0) {
                // Suffix-byte-range-spec, e.g. "-500"
                $length = substr($range, 1);
                $start = $size - (int) $length;
            } else {
                $parts = explode('-', $range);
                $start = (int) $parts[0];
                $end = isset($parts[1]) && $parts[1] !== '' ? (int) $parts[1] : $end;
            }
            $length = $end - $start + 1;
            $headers['Content-Range'] = "bytes $start-$end/$size";
            $headers['Content-Length'] = $length;
            $status = 206; // Partial Content
        } else {
            $start = 0;
            $end = $size - 1;
            $headers['Content-Length'] = $size;
            $status = 200;
        }

        $response = new StreamedResponse(function () use ($fullPath, $start, $end) {
            $handle = fopen($fullPath, 'rb');
            fseek($handle, $start);
            $buffer = 1024 * 8;
            $bytesLeft = $end - $start + 1;
            while ($bytesLeft > 0 && !feof($handle)) {
                $read = ($bytesLeft > $buffer) ? $buffer : $bytesLeft;
                echo fread($handle, $read);
                $bytesLeft -= $read;
                flush();
            }
            fclose($handle);
        }, $status, $headers);

        return $response;
    }
}
