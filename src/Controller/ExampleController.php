<?php

namespace App\Controller;

use App\DTO\ExampleDTO;
use App\Service\ExampleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/example', name: 'api_example_')]
class ExampleController extends AbstractController
{
    public function __construct(
        private readonly ExampleService $exampleService
    ) {
    }

    #[Route('/process', name: 'process', methods: ['POST', 'GET'])]
    public function process(Request $request): JsonResponse
    {
        // For GET requests, use query parameter
        if ($request->isMethod('GET')) {
            $data = $request->query->get('data', 'default');
        } else {
            $requestData = json_decode($request->getContent(), true);
            $data = $requestData['data'] ?? 'default';
        }

        // Use service to process data
        $result = $this->exampleService->processData($data);

        // Convert to DTO
        $dto = new ExampleDTO();
        $dto->input = $data;
        $dto->output = $result;
        $dto->processedAt = new \DateTimeImmutable();

        return new JsonResponse($dto->toArray(), Response::HTTP_OK);
    }
}

