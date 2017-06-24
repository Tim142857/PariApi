<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BaremeCote
 *
 * @ORM\Table(name="bareme_cote")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\BaremeCoteRepository")
 */
class BaremeCote
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\NotNull()
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "must be positiv",
     * )
     *
     * @ORM\Column(name="coteMin", type="float")
     */
    private $coteMin;

    /**
     * @var float
     *
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\NotNull()
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "must be positiv",
     * )
     *
     * @ORM\Column(name="coteMax", type="float")
     */
    private $coteMax;

    /**
     * @var float
     *
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\NotNull()
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "must be positiv",
     * )
     *
     * @ORM\Column(name="multiplicateur", type="float")
     */
    private $multiplicateur;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set coteMin
     *
     * @param integer $coteMin
     *
     * @return BaremeCote
     */
    public function setCoteMin($coteMin)
    {
        $this->coteMin = $coteMin;

        return $this;
    }

    /**
     * Get coteMin
     *
     * @return int
     */
    public function getCoteMin()
    {
        return $this->coteMin;
    }

    /**
     * Set coteMax
     *
     * @param integer $coteMax
     *
     * @return BaremeCote
     */
    public function setCoteMax($coteMax)
    {
        $this->coteMax = $coteMax;

        return $this;
    }

    /**
     * Get coteMax
     *
     * @return int
     */
    public function getCoteMax()
    {
        return $this->coteMax;
    }

    /**
     * Set multiplicateur
     *
     * @param integer $multiplicateur
     *
     * @return BaremeCote
     */
    public function setMultiplicateur($multiplicateur)
    {
        $this->multiplicateur = $multiplicateur;

        return $this;
    }

    /**
     * Get multiplicateur
     *
     * @return int
     */
    public function getMultiplicateur()
    {
        return $this->multiplicateur;
    }
}
