<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/products')]

final class ProductsController extends AbstractController
{

    #[Route(name: 'app_products_index', methods: ['GET'])]
    public function index(
        PaginatorInterface $paginator,
        ProductsRepository $productsRepository,
        Request $request // Make sure to include the Request object
    ): JsonResponse {
        // Get the current page from the request, defaulting to 1
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 10);
        $search = $request->query->get('search', ''); // Get the search query
        $sort = $request->query->get('sort', 'asc'); // Get the sort order (asc or desc)
        // Fetch products with pagination and search
        $queryBuilder = $productsRepository->createQueryBuilder('p');

        // If there's a search term, add a WHERE clause to the query
        if (!empty($search)) {
            $queryBuilder->where('p.name LIKE :search OR p.description LIKE :search')
            ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->orderBy('p.price', $sort);

        $products = $paginator->paginate(
            $queryBuilder,
            $page,
            $limit
        );

        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'image' => '/uploads/' . $product->getImage(),
            ];
        }

        return new JsonResponse([
            'data' => $data,
            'meta' => [
                'current_page' => $products->getCurrentPageNumber(),
                'total_pages' => $products->getPageCount(),
                'total_items' => $products->getTotalItemCount(),
            ],
        ]);
    }

    #[Route('/new', name: 'app_products_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Create a new product
        $product = new Products();

        // Validate the incoming data (you may want to implement additional validation)
        if (!$request->request->has('name') || !$request->request->has('price') || !$request->request->has('description')) {
            return new JsonResponse(['error' => 'Invalid input'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $product->setName($request->request->get('name'));
        $product->setPrice((float)$request->request->get('price'));
        $product->setDescription($request->request->get('description'));

        // Handle image upload
        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $uploadsDir = $this->getParameter('uploads_directory');  // Ensure this parameter is set in services.yaml
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            try {
                // Move the uploaded file to the uploads directory
                $imageFile->move($uploadsDir, $newFilename);
                $product->setImage($newFilename);
            } catch (FileException $e) {
                return new JsonResponse(['error' => 'Failed to upload image'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

        }

        // Persist the product in the database
        $entityManager->persist($product);
        $entityManager->flush();

        // Return the created product's ID as a JSON response
        return new JsonResponse(['id' => $product->getId(),'message' => 'Product added successfully!'], JsonResponse::HTTP_CREATED);
    }





    #[Route('/show/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product): JsonResponse
    {
        $data = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(), // Assuming you have a description field
            'price' => $product->getPrice(),
            'image' => '/uploads/' . $product->getImage(),
        ];
        return new JsonResponse($data);
    }

    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['POST'], defaults: ['_format' => 'json'])]
    public function edit(Request $request, Products $product, ValidatorInterface $validator,EntityManagerInterface $entityManager): JsonResponse
    {


        $product->setName($request->request->get('name'));
        $product->setPrice($request->request->get('price'));

        if ($request->files->has('image')) {
            $file = $request->files->get('image');
            // Perform validation if needed

            // Generate a unique filename and move the file
            $filePath = 'uploads/' . uniqid() . '.' . $file->guessExtension();
            $file->move('uploads/', $filePath);

            // Set the new image path
            $product->setImage($filePath);
        }
        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], JsonResponse::HTTP_BAD_REQUEST);
        }
        // Save the updated product to the database
        $entityManager->persist($product);
        $entityManager->flush();

        return new JsonResponse($product);

    }

    #[Route('/{id}', name: 'app_products_delete', methods: ['DELETE'])]
    public function delete(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return new JsonResponse(['message' => 'Product deleted successfully']);
    }
}
