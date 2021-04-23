<?php

declare(strict_types=1);

/*
 * This file is part of the Clivern/Events project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Controller;

use App\Basket\Basket;
use App\Basket\BasketId;
use App\Basket\Repository\BasketRepository;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Basket Controller.
 */
class BasketController extends AbstractController
{
    /** @var BasketRepository */
    private $basketRepository;

    /**
     * Class Constructor.
     *
     * @OA\Schema(
     *   schema="Basket",
     *   @OA\Property(
     *     property="id",
     *     description="Basket Id",
     *     type="string"
     *   ),
     *   @OA\Property(
     *     property="products",
     *     description="Basket Products",
     *     type="array",
     *     items=@OA\Items(type="integer")
     *   )
     * )
     *
     * @OA\Parameter(
     *    @OA\Schema(type="string"),
     *    in="path",
     *    required=true,
     *    name="uuid",
     *    parameter="uuid"
     * )
     *
     * @OA\Parameter(
     *    @OA\Schema(type="integer"),
     *    in="path",
     *    required=true,
     *    name="id",
     *    parameter="id"
     * )
     */
    public function __construct(
        BasketRepository $basketRepository
    ) {
        $this->basketRepository = $basketRepository;
    }

    /**
     * Create Basket Action.
     *
     * @Route("/v1/basket", methods={"POST"}, name="basket.createAction")
     *
     * @OA\Post(
     *     path="/v1/basket",
     *     summary="Create a basket",
     *     @OA\Response(
     *       response="200",
     *       description="a New Basket Created",
     *       @OA\JsonContent(ref="#/components/schemas/Basket")
     *     )
     * )
     */
    public function createAction(): Response
    {
        $basketId = BasketId::fromString((string) Uuid::uuid4());
        $basket = Basket::init($basketId);
        $this->basketRepository->persist($basket);

        return $this->json([
            'id' => $basketId->toString(),
            'products' => $basket->getProducts(),
        ]);
    }

    /**
     * Get Basket Action.
     *
     * @Route("/v1/basket/{uuid}", methods={"GET"}, name="basket.indexAction")
     *
     * @OA\Get(
     *     path="/v1/basket/{uuid}",
     *     summary="Get a basket with UUID",
     *     @OA\Parameter(
     *        ref="#/components/parameters/uuid"
     *     ),
     *     @OA\Response(
     *       response="200",
     *       description="Get basket Info",
     *       @OA\JsonContent(ref="#/components/schemas/Basket")
     *     )
     * )
     *
     * @param string $uuid
     */
    public function indexAction($uuid): Response
    {
        $basketId = BasketId::fromString($uuid);
        $basket = $this->basketRepository->retrieve($basketId);

        return $this->json([
            'id' => $basketId->toString(),
            'products' => $basket->getProducts(),
        ]);
    }

    /**
     * Add Product to a Basket Action.
     *
     * @Route("/v1/basket/{uuid}/product/{id}", methods={"POST"}, name="basket.addProductAction")
     *
     * @OA\Post(
     *     path="/v1/basket/{uuid}/product/{id}",
     *     summary="Add a product to a basket",
     *     @OA\Parameter(
     *        ref="#/components/parameters/uuid"
     *     ),
     *     @OA\Parameter(
     *        ref="#/components/parameters/id"
     *     ),
     *     @OA\Response(
     *       response="200",
     *       description="Product added",
     *       @OA\JsonContent(ref="#/components/schemas/Basket")
     *     )
     * )
     *
     * @param string $uuid
     * @param int    $id
     */
    public function addProductAction($uuid, $id): Response
    {
        $basketId = BasketId::fromString($uuid);
        $basket = $this->basketRepository->retrieve($basketId);
        $basket->addProduct((int) $id);

        $this->basketRepository->persist($basket);

        return $this->json([
            'id' => $basketId->toString(),
            'products' => $basket->getProducts(),
        ]);
    }

    /**
     * Remove a Product from a Basket Action.
     *
     * @Route("/v1/basket/{uuid}/product/{id}", methods={"DELETE"}, name="basket.removeProductAction")
     *
     * @OA\Delete(
     *     path="/v1/basket/{uuid}/product/{id}",
     *     summary="Remove a product from a basket",
     *     @OA\Parameter(
     *        ref="#/components/parameters/uuid"
     *     ),
     *     @OA\Parameter(
     *        ref="#/components/parameters/id"
     *     ),
     *     @OA\Response(response="204", description="Product removed")
     * )
     *
     * @param string $uuid
     * @param int    $id
     */
    public function removeProductAction($uuid, $id): Response
    {
        $basketId = BasketId::fromString($uuid);
        $basket = $this->basketRepository->retrieve($basketId);
        $basket->removeProduct((int) $id);

        $this->basketRepository->persist($basket);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
