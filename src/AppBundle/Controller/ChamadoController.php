<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Chamado;
use AppBundle\Entity\Cliente;

class ChamadoController extends Controller {

    /**
     * @Route("/{page}", name="homepage", requirements={"page": "\d+"})
     */
    public function indexAction($page = 1, Request $request) {

        $chamados = $this->getDoctrine()->getRepository('AppBundle:Chamado')->findAllChamados($page);

        if ($request->isMethod('POST')) {
            $email = $request->get('filter_email') ? $request->get('filter_email') : 'all';
            $numero = $request->get('filter_numero') ? $request->get('filter_numero') : 0;

            if ($email != 'all' || $numero != 0) {
                return $this->redirectToRoute('homepage_pagination', ['email' => $email, 'pedido' => $numero]);
            }
            $this->addFlash(
                    'error', 'Nenhum filtro encontrado'
            );
        }

        return $this->render('chamado/index.html.twig', [
                    'action' => $this->generateUrl('homepage'),
                    'action_add' => $this->generateUrl('chamado_create'),
                    'chamados' => $chamados['items'],
                    'pagination' => $chamados['pagination'],
                    'filters' => $chamados['filters'],
                    'url_base' => $this->generateUrl('homepage')

        ]);
    }


    /**
     * @Route("/chamados/add", name="chamado_create")
     */
    public function create(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $chamado = new Chamado();

        if ($request->isMethod('POST')) {

            $cliente = $this->getDoctrine()
                    ->getRepository('AppBundle:Cliente')
                    ->findOneByEmail($request->get('email'));

            if (!$cliente) {
                $cliente = new Cliente();
                $cliente->setNome($request->get('nome'));
                $cliente->setEmail($request->get('email'));

                $em->persist($cliente);
                $em->flush();
            }
            $chamado->setCliente($cliente);
            $chamado->setObservacao($request->get('observacao'));

            $pedido = $this->getDoctrine()
                    ->getRepository('AppBundle:Pedido')
                    ->find($request->get('numero'));

            if (!$pedido) {
                $this->addFlash(
                        'error', 'Numero de Pedido inexistente: '
                );

                return $this->render('chamado/edit.html.twig', [
                            'action' => $this->generateUrl('chamado_create'),
                            'chamado' => $chamado,
                ]);
            }

            $chamado->setPedido($pedido);

            $em->persist($chamado);
            $em->flush();


            $this->addFlash(
                    'notice', 'Chamado salvo com sucesso. Id ' . $chamado->getId()
            );
            return $this->redirectToRoute('homepage');
        }

        return $this->render('chamado/edit.html.twig', [
                    'action' => $this->generateUrl('chamado_create'),
                    'chamado' => null,
        ]);
    }

    /**
     * @Route("/chamados/show/{id}", name="chamado_show")
     */
    public function show($id) {

        $em = $this->getDoctrine()->getManager();

        $chamado = $this->getDoctrine()
                ->getRepository('AppBundle:Chamado')
                ->findOneById($id);

        if (!$chamado) {
            $this->addFlash(
                    'error', 'Chamado inexistente: '
            );
            return $this->redirectToRoute('homepage');
        }

        return $this->render('chamado/show.html.twig', [
                    'action' => null,
                    'chamado' => $chamado,
        ]);
    }

    /**
     * @Route("/chamados/{email}/{pedido}/{page}", name="homepage_pagination", requirements={"email": "\D+","pedido": "\d+","page": "\d+"})
     */
    public function index_paginationAction($email = null, $pedido = null, $page = 1) {
        $chamados = $this->getDoctrine()->getRepository('AppBundle:Chamado')->findAllChamados($page, $email, $pedido);

        return $this->render('chamado/index.html.twig', [
                    'action' => $this->generateUrl('homepage'),
                    'action_add' => $this->generateUrl('chamado_create'),
                    'chamados' => $chamados['items'],
                    'pagination' => $chamados['pagination'],
                    'filters' => $chamados['filters'],
                    'url_base' => $this->generateUrl('homepage_pagination', ['email' => $email, 'pedido' => $pedido])
        ]);
    }
}
