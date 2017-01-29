<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChamadoRepository")
 * @ORM\Table(name="chamados")
 */
class Chamado {

    const NUM_ITEMS = 5;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Many Chamados have One Pedido.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pedido")
     * @ORM\JoinColumn(name="pedido_id", referencedColumnName="id")
     */
    private $pedido;

    /**
     * @ORM\Column(type="text")
     */
    private $observacao;

    /**
     * Many Pedidos have One Cliente.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cliente")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id", nullable=false)
     */
    private $cliente;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set pedido
     *
     * @param \AppBundle\Entity\Pedido $pedido
     *
     * @return Chamado
     */
    public function setPedido(\AppBundle\Entity\Pedido $pedido = null) {
        $this->pedido = $pedido;

        return $this;
    }

    /**
     * Get pedido
     *
     * @return \AppBundle\Entity\Pedido
     */
    public function getPedido() {
        return $this->pedido;
    }

    /**
     * Set observacao
     *
     * @param string $observacao
     *
     * @return Pedido
     */
    public function setObservacao($observacao) {
        $this->observacao = $observacao;

        return $this;
    }

    /**
     * Get observacao
     *
     * @return string
     */
    public function getObservacao() {
        return $this->observacao;
    }

    /**
     * Set cliente
     *
     * @param \AppBundle\Entity\Cliente $cliente
     *
     * @return Pedido
     */
    public function setCliente(\AppBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \AppBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }
}
