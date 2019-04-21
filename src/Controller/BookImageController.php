<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookImage;
use App\Utils\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookImageController extends AbstractController
{
    /**
     * @Route("/book/{id}/image", name="book_add_image", methods={"POST"})
     *
     * @param Book           $book
     * @param Request                $request
     * @param UploaderHelper         $uploaderHelper
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface     $validator
     *
     * @return JsonResponse
     */
    public function uploadBookImage(Book $book, Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('image');
        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank([
                    'message' => 'Please select a file to upload',
                ]),
                new File([
                    'maxSize' => '20M',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Please upload image only!',
                ]),
            ]
        );

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            $violation = $violations[0];

            return $this->json([
                'detail' => $violation->getMessage(),
            ], 400);
        }

        $filename = $uploaderHelper->uploadBookImage($uploadedFile);
        $bookImage = new BookImage($book);
        $bookImage->setFilename($filename);
        $bookImage->setOriginalFilename($uploadedFile->getClientOriginalName() ?? $filename);
        $bookImage->setMimeType($uploadedFile->getClientMimeType() ?? 'application/none');
        $entityManager->persist($bookImage);
        $entityManager->flush();

        return $this->json($bookImage,
            201);
    }
}
