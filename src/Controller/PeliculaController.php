<?php
namespace App\Controller;
use App\Repository\PeliculaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class peliculaController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class peliculaController
{
    private $peliculaRepository;

    public function __construct(PeliculaRepository $peliRepository)
    {
        $this->peliculaRepository = $peliculaRepository;
    }

    /**
     * @Route("pelicula", name="add_pelicula", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $nombre = $data['nombre'];
        $genero = $data['genero'];
        $descripcion = $data['descripcion'];

        if (empty($nombre) || empty($nombre) || empty($descripcion)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->peliculaRepository->savePelicula($nombre, $genero, $descripcion);

        return new JsonResponse(['status' => 'Pelicula created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("pelicula/{id}", name="get_one_pelicula", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $pelicula = $this->peliculaRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $pelicula->getId(),
            'nombre' => $pelicula->getNombre(),
            'genero' => $pelicula->getGenero(),
            'descripcion' => $pelicula->getDescripcion(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("peliculass", name="get_all_peliculas", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $peliculas = $this->peliculaRepository->findAll();
        $data = [];

        foreach ($peliculas as $pelicula) {
            $data[] = [
                'id' => $pelicula->getId(),
                'name' => $pelicula->getNombre(),
                'genero' => $pelicula->getGenero(),
                'descripcion' => $pelicula->getDescripcion(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("pelicula/{id}", name="update_pelicula", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $pelicula = $this->peliculaRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['nombre']) ? true : $pelicula->setNombre($data['nombre']);
        empty($data['genero']) ? true : $pelicula->setgenero($data['genero']);
        empty($data['descripcion']) ? true : $pelicula->setdescripcion($data['descripcion']);

        $updatedpelicula = $this->peliRepository->updatePeli($pelicula);

		return new JsonResponse(['status' => 'pelicula updated!'], Response::HTTP_OK);
    }

    /**
     * @Route("pelicula/{id}", name="delete_pelicula", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $pelicula = $this->peliculaRepository->findOneBy(['id' => $id]);

        $this->peliculaRepository->removePelicula($pelicula);

        return new JsonResponse(['status' => 'pelicula deleted'], Response::HTTP_OK);
    }
}

?>