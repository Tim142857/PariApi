<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resultat
 *
 * @ORM\Table(name="resultat")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\ResultatRepository")
 */
class Resultat
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
     * @ORM\OneToOne(targetEntity="PariDispo", cascade={"persist"})
     */

    private $pariDispo;

    /**
     * @var int
     *
     * @ORM\Column(name="scoreEq1", type="integer")
     */
    private $scoreEq1;

    /**
     * @var int
     *
     * @ORM\Column(name="scoreEq2", type="integer")
     */
    private $scoreEq2;


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
     * Set scoreEq1
     *
     * @param integer $scoreEq1
     *
     * @return Resultat
     */
    public function setScoreEq1($scoreEq1)
    {
        $this->scoreEq1 = $scoreEq1;

        return $this;
    }

    /**
     * Get scoreEq1
     *
     * @return int
     */
    public function getScoreEq1()
    {
        return $this->scoreEq1;
    }

    /**
     * Set scoreEq2
     *
     * @param integer $scoreEq2
     *
     * @return Resultat
     */
    public function setScoreEq2($scoreEq2)
    {
        $this->scoreEq2 = $scoreEq2;

        return $this;
    }

    /**
     * Get scoreEq2
     *
     * @return int
     */
    public function getScoreEq2()
    {
        return $this->scoreEq2;
    }

    /**
     * Set pariDispo
     *
     * @param \CoreBundle\Entity\PariDispo $pariDispo
     *
     * @return Resultat
     */
    public function setPariDispo(\CoreBundle\Entity\PariDispo $pariDispo = null)
    {
        $this->pariDispo = $pariDispo;

        return $this;
    }

    /**
     * Get pariDispo
     *
     * @return \CoreBundle\Entity\PariDispo
     */
    public function getPariDispo()
    {
        return $this->pariDispo;
    }
}
