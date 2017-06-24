<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     */
    protected $points;

    /**
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\PariFait", mappedBy="user", cascade={"remove", "persist"})
     */
    private $parisFaits;

    public function __construct()
    {
        parent::__construct();
        $this->roles = array('ROLE_USER');
        $this->enabled = true;
        $this->points = 1000;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function hydrate($data)
    {

        $this->setUsername($data['username']);
        $this->setUsernameCanonical($data['username']);
        $this->setEmail($data['email']);
        $this->setEmailCanonical($data['email']);
        $this->setPlainPassword($data['plainPassword']);
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return User
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Add parisFait
     *
     * @param \CoreBundle\Entity\PariFait $parisFait
     *
     * @return User
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
}
