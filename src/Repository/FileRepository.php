<?php

declare(strict_types = 1);

namespace Mega\Repository;

use Mega\Entity\File;
use Mega\Entity\User;
use Mega\Exception\EntityNotFoundException;
use Mega\Repository\EntityBuilder\FileBuilder;
use Mega\Repository\EntityBuilder\UserBuilder;
use PDO;

class FileRepository extends AbstractRepository
{
    public function __construct(protected PDO $pdo, protected FileBuilder $FileBuilder, protected UserBuilder $userBuilder) {}

    public function persist(File $file): void
    {
        $sql = 'INSERT INTO user_file_data(uid, fk_user, `name`, content_type, file_data, `size`) VALUES (:uid, :fk_user, :name, :content_type, :file_data, :size)';
        $stmt = $this->pdo->prepare($sql);

        $uid = $file->getUid();
        $userId = $file->getUser()->getId();
        $contentType = $file->getContentType();
        $data = $file->getData();
        $name = $file->getName();
        $size = $file->getSize();

        $stmt->bindParam(':uid', $uid, PDO::PARAM_STR);
        $stmt->bindParam(':fk_user', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':content_type', $contentType, PDO::PARAM_STR);
        $stmt->bindParam(':size', $size, PDO::PARAM_INT);
        $stmt->bindParam(':file_data', $data, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function findOneByUidAndUser(string $uid, User $user): File
    {
        $sql = 'SELECT ufd.*, u.id as u__id, u.uid as u__uid, u.username as u__username, u.created_at as u__created_at, u.is_active as u__is_active, u.updated_at as u__updated_at FROM user_file_data ufd ';
        $sql .= 'INNER JOIN user u ON u.id=ufd.fk_user WHERE ufd.uid = :uid AND u.id = :user_id ';

        $stmt = $this->pdo->prepare($sql);

        $userId = $user->getId();

        $stmt->bindParam(':uid', $uid);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!$row) {
            throw new EntityNotFoundException(File::class);
        }

        $file = $this->FileBuilder->buildFromRow($row);
        $user = $this->userBuilder->buildFromRow($row, 'u__');
        $file->setUser($user);

        return $file;
    }
}
