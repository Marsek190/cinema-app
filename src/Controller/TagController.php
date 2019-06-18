<?php

namespace App\Controller;

use App\Service\TagService;
use Doctrine\ORM\ORMException;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tag", name="tag_")
*/
class TagController extends AbstractController
{
    /**
     * @var TagService $tagService
    */
    private $tagService;

    /**
     * @var Serializer $serializer
     */
    private $serializer;

    public function __construct(TagService $tagService, Serializer $serializer)
    {
        $this->tagService = $tagService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/create/{movieId}", name="tag", methods={"POST", "OPTIONS"})
     * @param Request $request
     * @param mixed $movieId
     * @return Response
     */
    public function create(Request $request, $movieId): Response
    {
        $data = json_decode($request->getContent(), JSON_OBJECT_AS_ARRAY);
        //print_r($data);
        try {
            $tag = $this->tagService->create($movieId, $data['tags']);
            $result = $this->serializer->serialize($tag, 'json');
            //print_r($tag);
            return new Response($result, 200);
        } catch (ORMException|\DomainException $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], 501);
        }
    }

    /**
     * @Route("/delete/{movieId}/{tagId}", name="delete", methods={"POST", "OPTIONS"})
     * @param mixed $movieId
     * @param mixed $tagId
     * @return Response
     */
    public function delete($movieId, $tagId): Response
    {
        try {
            $this->tagService->delete($movieId, $tagId);
            return new JsonResponse([
                'status' => 'OK'
            ]);
        } catch (ORMException|\DomainException $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], 501);
        }
    }
}
