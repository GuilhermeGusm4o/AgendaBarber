<?php

namespace App\Controller;

use App\Entity\Appointment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route("/", methods: ["HEAD", "GET"])]
    public function index(): JsonResponse
    {
        return $this->json(['message' => 'API is running.'], JsonResponse::HTTP_OK);
    }

    
    #[Route("/api/appointments", methods: ["GET"])]
    public function list(): JsonResponse
    {
        $appointments = $this->entityManager->getRepository(Appointment::class)->findAll();
        return $this->json($appointments);
    }

    #[Route("/api/appointments", methods: ["POST"])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $appointment = new Appointment();
        $appointment->setDate(new \DateTime($data['date']));

        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        return $this->json($appointment, JsonResponse::HTTP_CREATED);
    }
}
