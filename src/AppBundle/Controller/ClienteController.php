<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cliente;

class ClienteController extends Controller {

    /**
     * @Route("/clientes", name="cliente")
     */
    public function indexAction() {
        
        return $this->render('cliente/index.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
                    'clientes' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Cliente')->findAll(),
                    'action' => $this->generateUrl('cliente_create')
        ]);
    }

    /**
     * Matches /clientes/create
     *
     * @Route("/clientes/add", name="cliente_create")
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $cliente = new Cliente();

        if ($request->isMethod('POST')) {
            $cliente->setNome($request->get('nome'));
            $cliente->setEmail($request->get('email'));
            
            $flag = 'notice';
            if($em->getRepository('AppBundle:Cliente')->findOneByEmail($cliente->getEmail())){
                $msg = 'Já existe um cliente para o email ' . $cliente->getEmail();
                $flag = 'error';
            } else {
                $em->persist($cliente);    
                $msg = 'Cliente salvo com sucesso. Id ' . $cliente->getId();
            }
            $em->flush();

            $this->addFlash($flag, $msg);
            return $this->redirectToRoute('cliente');
        }

        return $this->render('cliente/edit.html.twig', [
                    'cliente' => $cliente,
                    'action' => $this->generateUrl('cliente_create')
        ]);
    }
    
    /**
     * @Route("/clientes/buscar", name="cliente_buscar")
     */
    public function buscar(Request $request) {
        $json = false;

        if ($request->isMethod('POST')) {

            if ($request->get('email')) {
                $cliente = $this->getDoctrine()
                        ->getRepository('AppBundle:Cliente')
                        ->findOneByEmail($request->get('email'));
                if ($cliente) {
                    $json = $this->toArray($cliente);
                }
            }
        }

        return $this->json(array('data' => $json));
    }

    /**
     * Matches /clientes/*
     *
     * @Route("/clientes/{clienteId}", name="cliente_show")
     */
    public function showAction($clienteId) {

        $cliente = $this->getDoctrine()
                ->getRepository('AppBundle:Cliente')
                ->find($clienteId);

        if (!$cliente) {
            throw $this->createNotFoundException(
                    'Cliente inexistente para o id ' . $clienteId
            );
        }

        return $this->render('cliente/edit.html.twig', [
            'cliente' => $cliente
        ]);

        // ... do something, like pass the $product object into a template
    }


    private function toArray(Cliente $cliente) {
        return array(
            'cliente_id' => $cliente->getId(),
            'email' => $cliente->getEmail(),
            'nome' => $cliente->getNome(),
        );
    }

}
