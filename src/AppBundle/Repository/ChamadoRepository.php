<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Chamado;
use AppBundle\Entity\Pedido;
use AppBundle\Entity\Cliente;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Expr\Comparison;

/**
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog post information.
 *
 * See http://symfony.com/doc/current/book/doctrine.html#custom-repository-classes
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class ChamadoRepository extends EntityRepository {

    /**
     * @return Query
     */
    public function queryAll($email = null, $pedido = null) {

        return $this->getEntityManager()
                        ->createQuery('
                SELECT c
                FROM AppBundle:Chamado c 
                LEFT JOIN AppBundle:Cliente l
                WHERE l.email LIKE :email
                LEFT JOIN AppBundle:Pedido p
                WHERE p.id = :pedido
            ')
                ->setParameter('email', $email)
                ->setParameter('pedido', $pedido);
    }

    /**
     * @param int $page
     *
     * @return Paginator
     */
    public function findAllChamados($page = 1, $email = null, $pedido = null) {

        $filter = array();
        $filter['email']  = $email === 'all' ? null : $email;
        $filter['pedido'] = $pedido === 0 ? null : (integer) $pedido;

        $startItem = ($page > 1) ? Chamado::NUM_ITEMS * ($page - 1) : 0;

        $query = $this->queryAll($filter['email'], $filter['pedido'])
                ->setFirstResult($startItem)
                ->setMaxResults(Chamado::NUM_ITEMS);
        $paginator = new Paginator($query, true);

        $items = array();
        foreach ($paginator as $chamado) {
            array_push($items, $chamado);
        }
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / Chamado::NUM_ITEMS);

        $pagination = $this->pagination($page, $totalPages);

        return array(
            'items' => $items,
            'pagination' => $pagination,
            'filters' => ($filter['email'] || $filter['pedido']) ? $filter : null
        );
    }

    private function pagination($page, $total) {
        $limitPerPage = 4;
        $meio = ($limitPerPage / 2);

        $end = $page + $meio < $total ? $limitPerPage + 1 < $total ? $limitPerPage + 1 : $page + $meio + 1 : $total;
        $start = $end - $limitPerPage > 1 ? $end - $limitPerPage : 1;

        $items = array();
        for ($i = $start; $i <= $end; $i++) {
            $items[] = $i;
        }
        return array(
            'firstPage' => $page === 1 ? null : 1,
            'totalPages' => $total,
            'currentPage' => $page,
            'nextPage' => $page == $total ? null : $page + 1,
            'prevPage' => $page > 1 ? $page - 1 : null,
            'lastPage' => $total,
            'pages' => $items,
        );
    }

}
