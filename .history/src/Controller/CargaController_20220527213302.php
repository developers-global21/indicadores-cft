<?php

namespace App\Controller;

use App\Entity\Registro;
use App\Form\RegistroType;
use App\Repository\RegistroRepository;
use App\Repository\EnteRepository;
use App\Repository\CategoriaRepository;
use App\Repository\IndicadorRepository;
use App\Repository\PeriodoRepository;
use Doctrine\DBAL\Types\DecimalType;
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
        ManagerRegistry $doctrine,
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

        //---- primer elemento del formulario -----
        if (isset($params['indice_00'])) {
            $a = explode("-", $params['indice_00']);
            $indicadorId = $a[0];
            $registroId = $a[1];
            $valor = float_val($params['ind_00']);
            if ($registroId != '0') {
                //--- existe el registro se actualiza ----
                $respuesta1 = $this->actualizaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $registroId, $valor);
            } else {
                //---- no existe el registro se crea ----
                $respuesta1 = $this->creaRegistro($doctrine, $enteId, $categoriaId, $periodoId, $indicadorId, $valor);
            }
        }

        $salida = array($respuesta1);
        $response = new Response(json_encode($salida));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }



    /**
     * Esta función permite crear un registro en la base de datos
     * @param Doctrine $doctrine
     * @param int $enteId
     * @param int $categoriaId
     * @param int $periodoId
     * @param int $indicadorId
     * @param decimal $valor
     */
    private function creaRegistro(
        ManagerRegistry $doctrine,
        int $enteId,
        int $categoriaId,
        int $periodoId,
        int $indicadorId,
        decimal $valor
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

        $salida = '1';
        return $salida;
    }



    /**
     * Esta función permite actualizar un registro en la base de datos
     * @param Doctrine $doctrine
     * @param int $enteId
     * @param int $categoriaId
     * @param int $periodoId
     * @param int $indicadorId
     * @param int $registroId
     * @param decimal $valor
     */
    private function actualizaRegistro(
        ManagerRegistry $doctrine,
        int $enteId,
        int $categoriaId,
        int $periodoId,
        int $indicadorId,
        int $registroId,
        decimal $valor
    ) {
        //--- creamos unaa instancia al ente certificador ----
        $ente = $doctrine->getRepository(Ente::class)->find($enteId);

        //--- creamos una instancia a la categoria ----
        $categoria = $doctrine->getRepository(Categoria::class)->find($categoriaId);

        //--- creamos una instancia al periodo ----
        $periodo = $doctrine->getRepository(Periodo::class)->find($periodoId);

        //--- creamos una instancia al indicador ----
        $indicador = $doctrine->getRepository(Indicador::class)->find($indicadorId);

        $registro = $doctrine->getRepository(Registro::class)->find($registroId);
        if ($registro) {
            $registro->setValor($valor);

            //--- hacemos persistente el cambio
            $entityManager = $doctrine->getManager();
            $entityManager->persist($registro);
            $entityManager->flush();
        } else {
            $salida = '0';
        }


        $salida = '1';
        return $salida;
    }
}
