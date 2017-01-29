<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Pedido;

class PedidoController extends Controller {

    /**
     * @Route("/pedidos", name="pedido")
     */
    public function indexAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('pedido/index.html.twig', [
                    'action' => $this->generateUrl('pedido_create'),
                    'pedidos' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Pedido')->findAll()
        ]);
    }

    /**
     * @Route("/pedidos/add", name="pedido_create")
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $pedido = new Pedido();

        if ($request->isMethod('POST')) {

            $pedido->setTitulo($request->get('titulo'));

            $em->persist($pedido);
            $em->flush();

            $this->addFlash(
                    'notice', 'Pedido salvo com sucesso. Id ' . $pedido->getId()
            );
            return $this->redirectToRoute('pedido');
        }

        return $this->render('pedido/edit.html.twig', [
                    'pedido' => $pedido,
                    'action' => $this->generateUrl('pedido_create')
        ]);
    }

    /**
     * @Route("/pedidos/delete/{id}", name="pedido_delete")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager()->getRepository('AppBundle:Pedido');

        if (!$id) {
            $this->addFlash(
                    'error', 'Pedido não foi encontrado. Id ' . $id
            );
            return $this->redirectToRoute('pedido');
        }
        $pedido = $em->find(['id' => $id]);
        if ($pedido) {

            $em->remove($pedido);
            $em->frush();
            $this->addFlash(
                    'notice', 'Pedido removido com sucesso. Id ' . $id
            );
        }
        return $this->redirectToRoute('pedido');
        
    }

    /**
     * @Route("/pedidos/buscar", name="pedido_buscar")
     */
    public function buscar(Request $request) {
        $json = false;

        if ($request->isMethod('POST')) {

            if ($request->get('id_pedido')) {
                $pedido = $this->getDoctrine()
                        ->getRepository('AppBundle:Pedido')
                        ->findOneById($request->get('id_pedido'));
                if ($pedido) {
                    $json = $this->toArray($pedido);
                }
            }
        }

        return $this->json(array('data' => $json));
    }

    private function toArray(Pedido $pedido) {
        return array(
            'id' => $pedido->getId(),
            'titulo' => $pedido->getTitulo(),
        );
    }

}
