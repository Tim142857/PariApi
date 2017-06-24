<?php

namespace CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * PariFait
 *
 * @ORM\Table(name="pari_fait")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\PariFaitRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PariFait
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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="parisFaits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     *
     * @Assert\NotNull()
     *
     */
    private $user;

    /**
     * @var \PariDispo
     *
     * @ORM\ManyToOne(targetEntity="PariDispo", inversedBy="parisFaits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pariDispo", referencedColumnName="id")
     * })
     *
     * @Assert\NotNull()
     *
     */
    private $pariDispo;

    /**
     * @var string
     *
     * @ORM\Column(name="equipe", type="string", length=255)
     */
    private $equipe;

    /**
     *
     * @var float
     *
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\NotNull()
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Mise must be positiv",
     * )
     * @ORM\Column(name="montantMise", type="float")
     */
    private $montantMise;


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
     * Set equipe
     *
     * @param string $equipe
     *
     * @return PariFait
     */
    public function setEquipe($equipe)
    {
        $this->equipe = $equipe;

        return $this;
    }

    /**
     * Get equipe
     *
     * @return string
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * Set montantMise
     *
     * @param integer $montantMise
     *
     * @return PariFait
     */
    public function setMontantMise($montantMise)
    {
        $this->montantMise = $montantMise;

        return $this;
    }

    /**
     * Get montantMise
     *
     * @return int
     */
    public function getMontantMise()
    {
        return $this->montantMise;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return PariFait
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set pariDispo
     *
     * @param \CoreBundle\Entity\PariDispo $pariDispo
     *
     * @return PariFait
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

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $equipe = $this->getEquipe();
        $equipesDispos = array($this->getPariDispo()->getEquipe1(), $this->getPariDispo()->getEquipe2(), 'nul');
        if (!in_array($equipe, $equipesDispos, true)) {
            throw new \Exception('Equipe non valide');
        }

        $sport = $this->getPariDispo()->getSport();
        if (!$sport->getNulPossible() && $this->getEquipe() == 'nul') {
            throw new \Exception("Le sport de ce pari n'accepte pas les nuls");
        }

    }

    public static function getEquipes()
    {
        return array('PSG', 'Lyon');
//        return array($this->getPariDispo()->getEquipe1(), $this->getPariDispo()->getEquipe2(), 'nul');
    }
}
