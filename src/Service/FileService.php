<?php

declare(strict_types = 1);

namespace Mega\Service;

use Lib\Uid;
use Lib\Http\UploadedFile;
use Mega\Entity\File;
use Mega\Entity\User;
use Mega\Exception\TooLargeFileException;
use Mega\Repository\FileRepository;

class FileService
{
    public const MAX_FILE_SIZE = 65535;

    public function __construct(protected FileRepository $fileRepository) {}

    public function store(UploadedFile $uploadedFile, User $owner): File
    {
        if ($uploadedFile->getFileSize() > self::MAX_FILE_SIZE) {
            throw new TooLargeFileException($uploadedFile->getFileSize(), self::MAX_FILE_SIZE);
        }

        $uid = Uid::generate();
        $file = new File(
            null,
            $uid,
            $owner,
            $uploadedFile->getName(),
            $uploadedFile->getContentType(),
            $uploadedFile->getContent(),
            $uploadedFile->getFileSize(),
            null,
            null
        );

        $this->fileRepository->persist($file);
        $commitedFile = $this->fileRepository->findOneByUidAndUser($uid, $owner);

        return $commitedFile;
    }

    public function getOneByUidAndUser(string $fileUid, User $user): File
    {
        return $this->fileRepository->findOneByUidAndUser($fileUid, $user);
    }

    public function deleteByUidAndUser(string $uid, User $user): void
    {
        $file = $this->fileRepository->findOneByUidAndUser($uid, $user);
        $this->fileRepository->delete($file);
    }

    public function getAllByUser(User $user): array
    {
        return $this->fileRepository->findAllByUser($user);
    }

    public function update(File $file, UploadedFile $uploadedFile): File
    {
        $file->setName($uploadedFile->getName())
            ->setContentType($uploadedFile->getContentType())
            ->setSize($uploadedFile->getFileSize())
            ->setData($uploadedFile->getContent());

        $this->fileRepository->update($file);

        return $file;
    }
}
