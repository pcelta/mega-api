<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Lib\Http\DownloadableResponse;
use Lib\Http\JsonResponse;
use Lib\Http\Request;
use Lib\Http\Response;
use Mega\Exception\EntityNotFoundException;
use Mega\Exception\TooLargeFileException;
use Mega\Service\FileService;

class FileController extends AbstractController
{
    public function __construct(protected FileService $fileService) {}

    public function upload(Request $request): JsonResponse
    {
        if (!isset($_FILES)) {
            $response = new JsonResponse(['message' => 'No File Sent!']);
            $response->setStatusCode(Response::HTTP_STATUS_UNPROCESSABLE_ENTITY);

            return $response;
        }

        $uploadedFile = $request->getUploadedFile();
        $uploadedFile->setName($request->getParam('name'));

        try {
            $fileData = $this->fileService->store($uploadedFile, $this->authenticatedUsed);

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
            $file = $this->fileService->getOneByUidAndUser($request->getParam(':uid:'), $this->authenticatedUsed);
        } catch (EntityNotFoundException $e) {
            return JsonResponse::createNotFound();
        }

        if ($request->getParam('download', false)) {
            return new DownloadableResponse($file);
        }

        if ($request->getParam('raw', false)) {
            $rawResponse = new Response();
            $rawResponse->setContent($file->getData());

            return $rawResponse;
        }

        return new JsonResponse($file->toArray());
    }

    public function delete(Request $request): JsonResponse
    {
        $fileUid = $request->getParam(':uid:');
        try {
            $this->fileService->deleteByUidAndUser($fileUid, $this->authenticatedUsed);

            return new JsonResponse(['message' => 'File has been deleted!']);
        } catch (EntityNotFoundException $e) {
            return JsonResponse::createNotFound();
        }
    }

    public function listAll(): JsonResponse
    {
        $files = $this->fileService->getAllByUser($this->authenticatedUsed);

        $responseData = [];
        foreach ($files as $file) {
            $responseData[] = $file->toArray();
        }

        return new JsonResponse($responseData);
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $file = $this->fileService->getOneByUidAndUser($request->getParam(':uid:'), $this->authenticatedUsed);
        } catch (EntityNotFoundException $e) {
            return JsonResponse::createNotFound();
        }

        if (!isset($_FILES)) {
            $response = new JsonResponse(['message' => 'No File Sent!']);
            $response->setStatusCode(Response::HTTP_STATUS_UNPROCESSABLE_ENTITY);

            return $response;
        }

        $uploadedFile = $request->getUploadedFile();
        $uploadedFile->setName($request->getParam('name'));

        try {
            $file = $this->fileService->update($file, $uploadedFile);

            $response = new JsonResponse(['message' => 'File updated!', 'file' => $file->toArray()]);
            $response->setStatusCode(Response::HTTP_STATUS_OK);

            return $response;
        } catch (TooLargeFileException $e) {
            $response = new JsonResponse(['message' => $e->getMessage()]);
            $response->setStatusCode(Response::HTTP_STATUS_UNPROCESSABLE_ENTITY);

            return $response;
        }
    }
}
