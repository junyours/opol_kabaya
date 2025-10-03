<?php

namespace App\Http\Controllers\Mobile\Services;

use App\Http\Controllers\Controller;
use Http;
use Illuminate\Http\Request;

class SBController extends Controller
{
    public function getOrdinanceFolder()
    {
        $accessToken = $this->token();
        $folderId = config('services.google.folder_id');

        $query = sprintf("'%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false", $folderId);

        $response = Http::withToken($accessToken)->get('https://www.googleapis.com/drive/v3/files', [
            'q' => $query,
            'fields' => 'files(id, name)',
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to get folders: ' . $response->body());
        }

        return $response->json()['files'];
    }

    public function getOrdinancePdf($id)
    {
        $accessToken = $this->token();

        $query = sprintf(
            "'%s' in parents and mimeType = 'application/pdf' and trashed = false",
            $id
        );

        $response = Http::withToken($accessToken)->get('https://www.googleapis.com/drive/v3/files', [
            'q' => $query,
            'fields' => 'files(id, name)',
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to get pdfs: ' . $response->body());
        }

        return $response->json()['files'];
    }

    public function previewOrdinancePdf($id)
    {
        $accessToken = $this->token();

        $response = Http::withToken($accessToken)->post(
            "https://www.googleapis.com/drive/v3/files/{$id}/permissions",
            [
                'role' => 'reader',
                'type' => 'anyone',
            ]
        );

        if (!$response->successful()) {
            throw new \Exception('Failed to preview pdf: ' . $response->body());
        }

        return response()->json([
            'previewLink' => "https://drive.google.com/file/d/{$id}/preview"
        ]);
    }
}
