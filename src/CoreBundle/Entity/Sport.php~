<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Sport
 *
 * @ORM\Table(name="sport")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\SportRepository")
 * @UniqueEntity("nom")
 */
class Sport
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
     * @var string
     *
     * @Assert\NotBlank()
     *
     *
     * @ORM\Column(name="nom", type="string", length=255, unique=true, nullable=false)
     */
    private $nom;

    /**
     * @var bool
     *
     * @Assert\NotBlank()
     * @Assert\Choice({"true", "false"})
     *
     * @ORM\Column(name="nulPossible", type="boolean", nullable=false)
     */
    private $nulPossible;

    /**
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\PariDispo", mappedBy="sport", cascade={"remove", "persist"})
     */
    private $parisDispos;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Sport
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set nulPossible
     *
     * @param boolean $nulPossible
     *
     * @return Sport
     */
    public function setNulPossible($nulPossible)
    {
        $this->nulPossible = $nulPossible;

        return $this;
    }

    /**
     * Get nulPossible
     *
     * @return bool
     */
    public function getNulPossible()
    {
        return $this->nulPossible;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parisDispos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add parisDispo
     *
     * @param \CoreBundle\Entity\PariDispo $parisDispo
     *
     * @return Sport
     */
    public function addParisDispo(\CoreBundle\Entity\PariDispo $parisDispo)
    {
        $this->parisDispos[] = $parisDispo;

        return $this;
    }

    /**
     * Remove parisDispo
     *
     * @param \CoreBundle\Entity\PariDispo $parisDispo
     */
    public function removeParisDispo(\CoreBundle\Entity\PariDispo $parisDispo)
    {
        $this->parisDispos->removeElement($parisDispo);
    }

    /**
     * Get parisDispos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParisDispos()
    {
        return $this->parisDispos;
    }

    public function hydrate($data)
    {
        $this->setNom($data['nom']);
        $nulPossible=false;
        if($data['nulPossible']==="true"){
            $nulPossible=true;
        }
        $this->setNulPossible($nulPossible);
    }
}
