<?php

namespace App\Controller;

use App\Service\MovieService;
use App\Validation\MovieValidator;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \JMS\Serializer\Serializer;

/**
 * @Route("/movie", name="movie_")
*/
class MovieController extends AbstractController
{
    /**
     * @var MovieService $movieService
    */
    private $movieService;

    /**
     * @var Serializer $serializer
    */
    private $serializer;

    /**
     * @var Paginator $paginator
     */
    private $paginator;

    public function __construct(
        MovieService $movieService,
        Serializer $serializer,
        Paginator $paginator)
    {
        $this->movieService = $movieService;
        $this->serializer = $serializer;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        print_r($request->query->all());
        return new JsonResponse([], 200);
    }

    /**
     * @Route("/all", name="all", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function movies(Request $request): Response
    {
        $filter = $request->query->all();
        //print_r($filter);
        try {
            $query = $this->movieService->findAll($filter);
            $movies = $this->paginator->paginate(
                $query, $request->query->getInt('page', 1), 5);
            $result = $this->serializer->serialize($movies, 'json');
            return new Response($result, 200);
        } catch (DBALException $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], 501);
        }

    }

    /**
     * @Route("/create", name="create", methods={"POST", "OPTIONS"})
     * @param Request $request
     * @return Response
    */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), JSON_OBJECT_AS_ARRAY);
        $errors = (new MovieValidator())->validate($data);
        //print_r($data);
        if (count($errors)) {
            return new JsonResponse(compact('errors'));
        }
        try {
            $this->movieService->create($data);
            return new JsonResponse([
                'status' => 'OK'
            ]);
        } catch (ORMException|\DomainException $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], 501);
        }
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"POST", "OPTIONS"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function edit(Request $request, $id): Response
    {
        $data = json_decode($request->getContent(), JSON_OBJECT_AS_ARRAY);
        //print_r($data);
        try {
            $this->movieService->edit($id, $data);
            return new JsonResponse([
                'status' => 'OK'
            ]);
        } catch (ORMException|\DomainException $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], 501);
        }
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"POST", "OPTIONS"})
     * @param mixed $id
     * @return Response
    */
    public function delete($id): Response
    {
        try {
            $this->movieService->delete($id);
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
