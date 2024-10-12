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
        
        // Converte cada objeto em um array
        $responseData = [];
        foreach ($appointments as $appointment) {
            $responseData[] = [
                'id' => $appointment->getId(),
                'date' => $appointment->getDate()->format('Y-m-d'),
                'customerName' => $appointment->getCustomerName(),
            ];
        }
    
        return $this->json($responseData);
    }
    

    #[Route("/api/appointments", methods: ["POST"])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        // Validação
        if (!isset($data['date']) || !isset($data['customerName'])) {
            return $this->json(['error' => 'Data missing'], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        try {
            $appointment = new Appointment();
            
            $appointment->setDate(new \DateTime($data['date']));
            $appointment->setCustomerName($data['customerName']);
    
            $this->entityManager->persist($appointment);
            
            $this->entityManager->flush();
    
            // Converter o objeto em array
            $responseData = [
                'id' => $appointment->getId(),
                'date' => $appointment->getDate()->format('Y-m-d'),
                'customerName' => $appointment->getCustomerName(),
            ];
    
            return $this->json($responseData, JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}
