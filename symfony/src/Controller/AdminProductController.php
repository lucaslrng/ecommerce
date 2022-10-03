<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Entity\Inventory;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\FeatureRepository;
use App\Repository\InventoryRepository;
use App\Repository\ProductRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[IsGranted('ROLE_ADMIN', message: "Vous n'êtes pas autorisé à accéder à cette page")]
class AdminProductController extends AbstractController
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
                    "name" => $value->getImage(),
                    "url" => $value->getImageUrl(),
                    "id" => $value->getId()
                ];
            }
            return $output;
        };
    }

    #[Route('api/admin/product/{id}', name: 'admin-update-product', methods: ['PUT'])]
    public function update(Product $currentProduct, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $updatedProduct = $serializer->deserialize(
            $request->getContent(),
            Product::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentProduct]
    );
        $em->persist($updatedProduct);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('api/admin/product/{id}', name: 'admin-delete-product', methods: ['DELETE'])]
    public function delete(Product $product, EntityManagerInterface $em): JsonResponse
    {
        if ($product) {
            $em->remove($product);
            $em->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } else {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('api/admin/product', name: 'admin-create-product', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, FeatureRepository $featureRepository, ObjectNormalizer $normalizer, PromotionRepository $promotionRepository, CategoryRepository $categoryRepository): JsonResponse
    {
        $content = $request->toArray();
        $product = $content["product"];
        $features = $content["features"] ?? null;
        $category = $content["category"] ?? null;
        $promotions = $content["promotions"] ?? null;
        $inventoryAmount = $content["inventory"] ?? null;

        $newProduct = $normalizer->denormalize($product, Product::class);

        if ($features) {
            foreach ($features as $id) {
                $newProduct->addFeature($featureRepository->find($id));
            }
        }

        if ($category) {
            $newProduct->setCategory($categoryRepository->find($category));
        }

        if ($promotions) {
            foreach ($promotions as $id) {
                $newProduct->addPromotion($promotionRepository->find($id));
            }
        }

        if ($inventoryAmount) {
            $inventory = new Inventory();
            $inventory->setAmount($inventoryAmount);
            $newProduct->setInventory($inventory);
        }

        $em->persist($newProduct);
        $em-> flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
