<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

// #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour accéder à cette page.')]

class UserProductController extends AbstractController
{
    public function __construct()
    {
        $this->getFeaturesInfos = function($object, $outerObject, $name) {
            $output = [];
            foreach ($object as $value) {
                $output[] = [
                    "name" => $value->getName(),
                    "id" => $value->getId()
                ];
            }
            return $output;
        };

        $this->getCategoryInfos = function($object) {
            return [
                "name" => $object->getName(),
                "id" => $object->getId()
            ];
        };

        $this->getPromotionsInfos = function($object) {
            $output = [];
            foreach ($object as $value) {
                $output[] = [
                    "name" => $value->getName(),
                    "amount" => $value->getAmount(),
                    "id" => $value->getId()
                ];
            }
            return $output;
        };

        $this->getPhotosInfos = function($object) {
            $output = [];
            foreach ($object as $value) {
                $output[] = [
                    "url" => $value->getImageUrl(),
                    "id" => $value->getId()
                ];
            }
            return $output;
        };

        $this->getCommentsInfos = function($object) {
            $output = [];
            foreach ($object as $value) {
                $output[] = [
                    "comment" => $value->getComment(),
                    "rating" => $value->getRating(),
                    "user_email" => $value->getUser()->getEmail(),
                    "id" => $value->getId()
                ];
            }
            return $output;
        };
    }

    #[Route('api/product/list', name: 'list-products', methods: ['GET'])]
    public function list(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $products = $productRepository->findAll();
        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'photos' => $this->getPhotosInfos
            ],
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['orders', 'description', 'carts', 'inventory', 'promotions', 'features', 'category', 'weight']
        ];
        $jsonProducts = $serializer->serialize($products, 'json', $defaultContext);
        
        return new JsonResponse($jsonProducts, Response::HTTP_OK, json: true);
    }

    #[Route('api/product/search', name: 'search-product', methods: ['GET'])]
    public function search(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $search = $content['search'];
        unset($content['search']);
        $products = $productRepository->findAllByParams($search, $content);
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'features' => $this->getFeaturesInfos,
                'category' => $this->getCategoryInfos,
                'photos' => $this->getPhotosInfos
            ],
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['orders', 'description', 'carts', 'inventory', 'promotions', 'comments']
        ];
        $normalizer = new ObjectNormalizer(null, defaultContext: $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $jsonProducts = $serializer->serialize($products, 'json');

        return new JsonResponse($jsonProducts, Response::HTTP_OK, json: true);

    }

    #[Route('api/product/{id}', name: 'read-product', methods: ['GET'])]
    public function read(Product $product, SerializerInterface $serializer): JsonResponse
    {
        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'features' => $this->getFeaturesInfos,
                'category' => $this->getCategoryInfos,
                'promotions' => $this->getPromotionsInfos,
                'photos' => $this->getPhotosInfos,
                'comments' => $this->getCommentsInfos
            ],
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['orders', 'description', 'carts', 'inventory']
        ];
        $jsonProduct = $serializer->serialize($product, 'json', $defaultContext);

        return new JsonResponse($jsonProduct, Response::HTTP_OK, json: true);
    }

}
