<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Cliente;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog post information.
 *
 * See http://symfony.com/doc/current/book/doctrine.html#custom-repository-classes
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class ClienteRepository extends EntityRepository {

    /**
     * @return Query
     */
    public function queryAll() {
        return $this->getEntityManager()
                        ->createQuery('
                SELECT c
                FROM AppBundle:Cliente c
                ORDER BY c.nome DESC
            ')
        ;
    }

    /**
     * @param int $page
     *
     * @return Paginator
     */
    public function findAllClientes($page = 1) {
        $startItem = ($page > 1) ? Cliente::NUM_ITEMS * ($page - 1) : 0;

        $query = $this->queryAll()
                ->setFirstResult($startItem)
                ->setMaxResults(Cliente::NUM_ITEMS);
        $paginator = new Paginator($query, $fetchJoinCollection = true);

        $items = array();
        foreach ($paginator as $chamado) {
            array_push($items, $chamado);
        }
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / Cliente::NUM_ITEMS);

        $pagination = $this->pagination($page, $totalPages);

        return array(
            'items' => $items,
            'pagination' => $pagination
        );
    }

    private function pagination($page, $total) {
        $limitPerPage = 2;
        $meio = ($limitPerPage / 2);
        $end = $total <= $limitPerPage ? $total : $page + $meio;
        $start = $page - $meio > 1 ? $page - ($meio) : 1;
//        $start = ($end-$start < $limitPerPage) ? $end-$limitPerPage : $start;

        for ($i = $start; $i <= $end; $i++) {
            $items[] = $i;
        }
        return array(
            'firstPage' => 1,
            'totalPages' => $total,
            'currentPage' => $page,
            'nextPage' => $page == $total ? null : $page + 1,
            'prevPage' => $page > 1 ? $page - 1 : null,
            'lastPage' => $total,
            'pages' => $items,
        );
    }

}
