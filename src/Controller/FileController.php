<?php

declare(strict_types = 1);

namespace Mega\Controller;

use finfo;
use Lib\Http\DownloadableResponse;
use Lib\Http\JsonResponse;
use Lib\Http\Request;
use Lib\Http\Response;
use Mega\Exception\EntityNotFoundException;
use Mega\Exception\TooLargeFileException;
use Mega\Service\FileService;

class FileController extends AbstractController
{
    public function __construct(protected FileService $FileService) {}

    public function upload(Request $request): JsonResponse
    {
        if (!isset($_FILES)) {
            $response = new JsonResponse(['message' => 'No Files Sent!']);
            $response->setStatusCode(Response::HTTP_STATUS_UNPROCESSABLE_ENTITY);

            return $response;
        }

        $fileSize = $_FILES['file']['size'];

        $content = file_get_contents($_FILES['file']['tmp_name']);

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($content);

        $fileName = $request->getParam('name');

        try {
            $fileData = $this->FileService->store($fileName, $content, $mimeType, $fileSize, $this->authenticatedUsed);

            $response = new JsonResponse(['message' => 'File uploaded!', 'file' => $fileData->toArray()]);
            $response->setStatusCode(Response::HTTP_STATUS_CREATED);

            return $response;
        } catch (TooLargeFileException $e) {
            $response = new JsonResponse(['message' => $e->getMessage()]);
            $response->setStatusCode(Response::HTTP_STATUS_UNPROCESSABLE_ENTITY);

            return $response;
        }
    }

    public function listOne(Request $request): Response
    {
        try {
            $file = $this->FileService->getOneByUidAndUser($request->getParam(':uid:'), $this->authenticatedUsed);
        } catch (EntityNotFoundException $e) {
            $response = new JsonResponse(['message' => 'Not Found']);
            $response->setStatusCode(Response::HTTP_STATUS_NOT_FOUND);

            return $response;
        }

        if ($request->getParam('download', false)) {
            return new DownloadableResponse($file);
        }

        return new JsonResponse($file->toArray());
    }
}
