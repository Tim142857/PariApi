<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PariDispo
 *
 * @ORM\Table(name="pari_dispo")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\PariDispoRepository")
 */
class PariDispo
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
     * @ORM\Column(name="equipe1", type="string", length=255)
     */
    private $equipe1;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="equipe2", type="string", length=255)
     */
    private $equipe2;

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
     *      minMessage = "Cote must be positiv",
     * )
     *
     * @ORM\Column(name="cote1", type="float", length=255)
     */
    private $cote1;

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
     *      minMessage = "Cote must be positiv",
     * )
     *
     * @ORM\Column(name="cote2", type="float", length=255)
     */
    private $cote2;

    /**
     * @var float
     *
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Cote must be positiv",
     * )
     *
     * @ORM\Column(name="coteNul", type="float", length=255)
     */
    private $coteNul;

    /**
     * @var \Sport
     *
     * @ORM\ManyToOne(targetEntity="Sport", inversedBy="parisDispos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sport", referencedColumnName="id", nullable=false)
     * })
     */
    private $sport;

    /**
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\PariFait", mappedBy="pariDispo", cascade={"remove", "persist"})
     */
    private $parisFaits;


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
     * Set equipe1
     *
     * @param string $equipe1
     *
     * @return PariDispo
     */
    public function setEquipe1($equipe1)
    {
        $this->equipe1 = $equipe1;

        return $this;
    }

    /**
     * Get equipe1
     *
     * @return string
     */
    public function getEquipe1()
    {
        return $this->equipe1;
    }

    /**
     * Set equipe2
     *
     * @param string $equipe2
     *
     * @return PariDispo
     */
    public function setEquipe2($equipe2)
    {
        $this->equipe2 = $equipe2;

        return $this;
    }

    /**
     * Get equipe2
     *
     * @return string
     */
    public function getEquipe2()
    {
        return $this->equipe2;
    }

    /**
     * Set cote1
     *
     * @param string $cote1
     *
     * @return PariDispo
     */
    public function setCote1($cote1)
    {
        $this->cote1 = $cote1;

        return $this;
    }

    /**
     * Get cote1
     *
     * @return string
     */
    public function getCote1()
    {
        return $this->cote1;
    }

    /**
     * Set cote2
     *
     * @param string $cote2
     *
     * @return PariDispo
     */
    public function setCote2($cote2)
    {
        $this->cote2 = $cote2;

        return $this;
    }

    /**
     * Get cote2
     *
     * @return string
     */
    public function getCote2()
    {
        return $this->cote2;
    }

    /**
     * Set coteNul
     *
     * @param string $coteNul
     *
     * @return PariDispo
     */
    public function setCoteNul($coteNul)
    {
        $this->coteNul = $coteNul;

        return $this;
    }

    /**
     * Get coteNul
     *
     * @return string
     */
    public function getCoteNul()
    {
        return $this->coteNul;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parisFaits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set sport
     *
     * @param \CoreBundle\Entity\Sport $sport
     *
     * @return PariDispo
     */
    public function setSport(\CoreBundle\Entity\Sport $sport = null)
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sport
     *
     * @return \CoreBundle\Entity\Sport
     */
    public function getSport()
    {
        return $this->sport;
    }

    /**
     * Add parisFait
     *
     * @param \CoreBundle\Entity\PariFait $parisFait
     *
     * @return PariDispo
     */
    public function addParisFait(\CoreBundle\Entity\PariFait $parisFait)
    {
        $this->parisFaits[] = $parisFait;

        return $this;
    }

    /**
     * Remove parisFait
     *
     * @param \CoreBundle\Entity\PariFait $parisFait
     */
    public function removeParisFait(\CoreBundle\Entity\PariFait $parisFait)
    {
        $this->parisFaits->removeElement($parisFait);
    }

    /**
     * Get parisFaits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParisFaits()
    {
        return $this->parisFaits;
    }

    public function hydrate($data)
    {
        $this->setSport($em);
        $this->setEquipe1($data['equipe1']);
        $this->setEquipe2($data['equipe2']);
        $this->setCote1($data['cote1']);
        $this->setCote2($data['cote2']);
        $this->setCoteNul($data['coteNul']);
    }
}
