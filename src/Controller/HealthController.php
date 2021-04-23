<?php

declare(strict_types=1);

/*
 * This file is part of the Event project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Controller;

use App\Repository\UserRepository;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Health Controller.
 */
class HealthController extends AbstractController
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * Class Constructor.
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="health")
     *
     * @OA\Info(
     *   title="Events API",
     *   version="0.1.0",
     *   @OA\Contact(
     *     email="hello@clivern.com"
     *   )
     * )
     *
     * @OA\Get(
     *     path="/",
     *     @OA\Response(response="200", description="Application Health")
     * )
     */
    public function index(): Response
    {
        return $this->json([
            'status' => 'ok',
        ]);
    }
}
