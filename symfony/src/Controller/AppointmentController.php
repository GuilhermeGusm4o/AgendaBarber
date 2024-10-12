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

    #[Route("/api/appointments/{id}", methods: ["PUT"])]
    public function edit(Request $request, int $id): JsonResponse
    {
        // Decodifica o conteúdo da requisição
        $data = json_decode($request->getContent(), true);
        
        // Validação
        if (!isset($data['date']) || !isset($data['customerName'])) {
            return $this->json(['error' => 'Data missing. Please provide both date and customer name.'], JsonResponse::HTTP_BAD_REQUEST);
        }
        
        // Busca o compromisso
        $appointment = $this->entityManager->getRepository(Appointment::class)->find($id);
        
        if (!$appointment) {
            return $this->json(['error' => 'Appointment not found.'], JsonResponse::HTTP_NOT_FOUND);
        }
        
        try {
            // Validação
            $date = \DateTime::createFromFormat('Y-m-d', $data['date']);
            if (!$date) {
                return $this->json(['error' => 'Invalid date format. Use Y-m-d.'], JsonResponse::HTTP_BAD_REQUEST);
            }
    
            // Atualiza os campos do compromisso
            $appointment->setDate($date);
            $appointment->setCustomerName($data['customerName']);
    
            $this->entityManager->flush();
            
            $responseData = [
                'id' => $appointment->getId(),
                'date' => $appointment->getDate()->format('Y-m-d'),
                'customerName' => $appointment->getCustomerName(),
            ];
    
            return $this->json($responseData, JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred while updating the appointment: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    #[Route("/api/appointments/{id}", methods: ["DELETE"])]
    public function delete(int $id): JsonResponse
    {
        // Busca o compromisso
        $appointment = $this->entityManager->getRepository(Appointment::class)->find($id);
        
        if (!$appointment) {
            return $this->json(['error' => 'Appointment not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        
        try {
            $this->entityManager->remove($appointment);
            $this->entityManager->flush(); // Exclui o compromisso
            
            return $this->json(['message' => 'Appointment deleted'], JsonResponse::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}
