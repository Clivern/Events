<?php

/*
 * This file is part of the Event project.
 * (c) Clivern <hello@clivern.com>
 */

namespace App\Controller;

use App\Repository\UserRepository;
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
     */
    public function index(): Response
    {
        $this->userRepository->createOne('joe');

        return $this->json([
            'status' => 'ok',
        ]);
    }
}
