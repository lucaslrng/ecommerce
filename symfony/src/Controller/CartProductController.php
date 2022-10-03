<?php

namespace App\Controller;

use App\Entity\CartProduct;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class CartProductController extends AbstractController
{
    public function __construct(TokenStorageInterface $tokenStorageInterface)
    {
        $this->getProductsInfo = function($object, $outerObject, $name) {
            return [
                "name" => $object->getName(), 
                "weight" => $object->getWeight(), 
                "price" => $object->getPrice(), 
                "id" => $object->getId()
            ];
        };
        $this->tokenStorageInterface = $tokenStorageInterface;

    }

    #[Route('api/user/cart', name: 'user-read-cart', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour accéder à cette page.')]
    public function read(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->tokenStorageInterface->getToken()->getUser();
        $cart = $user->getCartProducts();
        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'product' => $this->getProductsInfo
            ],
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']
        ];
        $jsonProduct = $serializer->serialize($cart, 'json', $defaultContext);

        return new JsonResponse($jsonProduct, Response::HTTP_OK, json: true);
    }

    #[Route('api/user/cart', name: 'user-add-product-cart', methods: ['POST'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour accéder à cette page.')]
    public function addProductToCart(Request $request, ProductRepository $productRepository, EntityManagerInterface $em) 
    {   

        $datas = $request->toArray();
        $product = $productRepository->find($datas["id_product"]);
        $cartProduct = new CartProduct();
        $cartProduct->setUser($this->tokenStorageInterface->getToken()->getUser());
        $cartProduct->setProduct($product);
        $cartProduct->setAmount($datas["amount"]);
        $em->persist($cartProduct);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('api/user/cart/{id}', name: 'user-remove-product-cart', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour accéder à cette page.')]
    public function removeProductToCart(CartProduct $cartProduct, EntityManagerInterface $em) 
    {   
        if ($cartProduct) {
            $em->remove($cartProduct);
            $em->flush();
        }
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('api/user/cart', name: 'user-remove-cart', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour accéder à cette page.')]
    public function remove(EntityManagerInterface $em) 
    {   
        $cart = $this->tokenStorageInterface->getToken()->getUser()->getCartProducts();
        if ($cart) {
            foreach ($cart as $key => $value) {
                $em->remove($value);
            }
            $em->flush();
        }
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('api/user/cart/{id}', name: 'user-update-product-cart', methods: ['PATCH'])]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour accéder à cette page.')]
    public function updateCartProductAmount(CartProduct $cartProduct, EntityManagerInterface $em, Request $request) 
    {   
        $cartProduct->setAmount($request->toArray()["amount"]);
        $em->persist($cartProduct);
        $em->flush();
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('api/admin/cart/{id}', name: 'admin-read-cart', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour accéder à cette page.')]
    public function readAdmin(User $user, SerializerInterface $serializer): JsonResponse
    {
        $cart = $user->getCartProducts();
        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'product' => $this->getProductsInfo
            ],
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']
        ];
        $jsonProduct = $serializer->serialize($cart, 'json', $defaultContext);

        return new JsonResponse($jsonProduct, Response::HTTP_OK, json: true);
    }

    #[Route('api/admin/cart/{id}', name: 'admin-delete-cart', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour accéder à cette page.')]
    public function removeAdmin(User $user, EntityManagerInterface $em): JsonResponse
    {
        $cart = $user->getCartProducts();
        if ($cart) {
            foreach ($cart as $value) {
                $em->remove($value);
            }
            $em->flush();
        }
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
