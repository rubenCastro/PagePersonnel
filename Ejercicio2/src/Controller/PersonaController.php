<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Form\PersonaType;
use App\Repository\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\ORM\EntityManagerInterface;

/**
    * @Route("/API/persona")
*/
class PersonaController extends AbstractController
{

    private $em;
    private $repositoryDishes;
    private $repositoryRestaurant;

    public function __construct(EntityManagerInterface $entityManager, 
        PersonaRepository $personaRepository)
    {
        $this->em = $entityManager;
        $this->personaRepository = $personaRepository;
    }

    /**
     * @Route("/{id}", name="app_get_persona", methods={"GET"})
     */
    public function getPersona($id): JsonResponse
    {
        $persona = $this->personaRepository->find($id);
        
        return new JsonResponse($persona->toArray(), Response::HTTP_OK);
    }

    /**
     * Actualizamos la entidad persona, con Datetime controla que la fecha no se salga de rango, sino lanza una excepcion
     * @Route("/{id}", name="app_update_persona", methods={"PUT", "PATCH"})
     */
    public function updatePersona($id, Request $request): JsonResponse
    {
        
        $data = json_decode($request->getContent(), true);
        try{
            if(isset($data['nombre']) && !empty($data['nombre']) && isset($data['fechaNacimiento']) && !empty($data['fechaNacimiento'])){
                $persona = $this->personaRepository->find($id);
                $persona->setNombre($data['nombre']);
                $date = new \Datetime($data['fechaNacimiento']);
                $persona->setFechaNacimiento($date);
                $this->em->persist($persona);
                $this->em->flush();
            
                return new JsonResponse($persona->toArray(), Response::HTTP_OK);
            }else{
                return new JsonResponse(['error' => 'Datos incorrectos'], Response::HTTP_NOT_FOUND);
            }
        }catch(\Exception $e){
            return new JsonResponse(['error' => 'No se pudieron actualizar los campos'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/new", name="app_nueva_persona", methods={"POST"})
     */
    public function nuevaPersona(Request $request): JsonResponse
    {
        
        $data = $request->request->all();

        try{
            if(isset($data['nombre']) && !empty($data['nombre']) && isset($data['fechaNacimiento']) && !empty($data['fechaNacimiento'])){
                $persona = new Persona();
                $persona->setNombre($data['nombre']);
                $date = new \Datetime($data['fechaNacimiento']);
                $persona->setFechaNacimiento($date);
                $this->em->persist($persona);
                $this->em->flush();
            
                return new JsonResponse($persona->toArray(), Response::HTTP_OK);
            }else{
                return new JsonResponse(['error' => 'Datos incorrectos'], Response::HTTP_NOT_FOUND);
            }
        }catch(\Exception $e){
            return new JsonResponse(['error' => 'No se pudo crear a la persona'], Response::HTTP_NOT_FOUND);
        }
        
    }

    /**
     * @Route("/{id}", name="app_delete_persona", methods={"DELETE"})
     */
    public function eliminarPersona($id): JsonResponse
    {
        $persona = $this->personaRepository->find($id);
        $this->em->remove($persona);
        $this->em->flush();

        return new JsonResponse(['msg' => 'Eliminado'], Response::HTTP_OK);
    }
}
