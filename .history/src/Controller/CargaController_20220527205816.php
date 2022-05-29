<?php

namespace App\Controller;

use App\Entity\Registro;
use App\Form\RegistroType;
use App\Repository\RegistroRepository;
use App\Repository\EnteRepository;
use App\Repository\CategoriaRepository;
use App\Repository\IndicadorRepository;
use App\Repository\PeriodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_USER')") 
 * @Route("/carga")
 */
class CargaController extends AbstractController
{
    /**
     * @Route("/new2/", name="app_registro_new2", methods={"GET","POST"})
     */
    public function new2(
        Request $request
    ): Response {
        $params = $request->request->all();
        $enteId = intval($params['id']);
        $url = $this->generateUrl('app_registro_new3', [
            'id' => $enteId
        ]);
        $respuesta = new Response($url);
        return $respuesta;
    }

    /**
     * @Route("/new3/", name="app_registro_new3", methods={"GET","POST"})
     */
    public function new3(
        Request $request,
        RegistroRepository $registroRepository,
        EnteRepository $enteRepository,
        CategoriaRepository $categoriaRepository,
        IndicadorRepository $indicadorRepository,
        PeriodoRepository $periodoRepository
    ): Response {
        $entes = $enteRepository->findAll();
        $periodos = $periodoRepository->findAll();
        $enteId = intval($request->query->get('id'));
        $ente = $enteRepository->find($enteId);
        $categorias = $categoriaRepository->findAllCategoriaByEnte($enteId);

        return $this->renderForm('registro/new2.html.twig', [
            'entes' => $entes,
            'ente' => $ente,
            'categorias' => $categorias,
            'periodos' => $periodos,
        ]);
    }

    /**
     * @Route("/busca_indicadores/", name="app_registro_busca_indicadores", methods={"POST"})
     */
    public function buscaIndicadores(
        Request $request,
        RegistroRepository $registroRepository
    ): Response {

        $params = $request->request->all();
        $enteId = intval($params['ente']);
        $categoriaId = intval($params['categoria']);
        $periodoId = intval($params['periodo']);
        $registros = $registroRepository->findAllRegistroByEnteCategoriaPeriodo($enteId, $categoriaId, $periodoId);
        if (!is_null($registros)) {
            $registros = $registroRepository->findAllRegistroByEnteCategoria($enteId, $categoriaId);
        }
        $salida = array($registros);
        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/guarda_indicadores/", name="app_registro_guarda_indicadores", methods={"POST"})
     */
    public function guardaIndicadores(
        Request $request,
        RegistroRepository $registroRepository,
        EnteRepository $enteRepository,
        CategoriaRepository $categoriaRepository,
        IndicadorRepository $indicadorRepository,
        PeriodoRepository $periodoRepository
    ): Response {

        $params = $request->request->all();
        $enteId = intval($params['ente']);
        $categoriaId = intval($params['categoria']);
        $periodoId = intval($params['periodo']);

        //---buscamos el ente ----
        $ente = $enteRepository->find($enteId);

        //---buscamos la categoria ----
        $categoria = $categoriaRepository->find($categoriaId);

        //---buscamos el periodo ----
        $periodo = $periodoRepository->find($periodoId);

        if (isset($params['ind_00'])) {
            $a = explode("-", $params['ind_00']);
            $indicadorId = $a[0];
            $registroId = $a[1];
            if ($registroId != '0') {
                //--- existe el registro se actualiza ----

            } else {
                //---- no existe el registro se crea ----
            }
        }

        $salida = array('1');
        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    private function crea_registro(
        ManagerRegistry $doctrine,
        RegistroRepository $registroRepository,
        int $enteId,
        int $categoriaId,
        int $periodoId,
        int $indicadorId,
        numeric $valor
    ) {
        //--- creamos unaa instancia al ente certificador ----
        $ente = $doctrine->getRepository(Ente::class)->find($enteId);

        //--- creamos una instancia a la categoria ----
        $categoria = $doctrine->getRepository(Categoria::class)->find($categoriaId);

        //--- creamos una instancia al periodo ----
        $periodo = $doctrine->getRepository(Periodo::class)->find($periodoId);

        //--- creamos una instancia al indicador ----
        $indicador = $doctrine->getRepository(Indicador::class)->find($indicadorId);

        $registro = new Registro();
        $registro->setEnte($ente);
        $registro->setCategoria($categoria);
        $registro->setPeriodo($periodo);
        $registro->setIndicador($indicador);
        $registro->setValor($valor);

        //--- hacemos persistente el cambio
        $entityManager = $doctrine->getManager();
        $entityManager->persist($registro);
        $entityManager->flush();

        $salida = array('1');
        return $salida;
    }
}
