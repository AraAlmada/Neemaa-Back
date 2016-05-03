<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=255, nullable=true)
     */
    private $sex;

    /**
     * @var int
     *
     * @ORM\Column(name="codePostal", type="integer", nullable=true)
     */
    private $codePostal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="skinType", type="string", length=255, nullable=true)
     */
    private $skinType;

    /**
     * @var string
     *
     * @ORM\Column(name="skinColor", type="string", length=255, nullable=true)
     */
    private $skinColor;

    /**
     * @var string
     *
     * @ORM\Column(name="hairType", type="string", length=255, nullable=true)
     */
    private $hairType;

    /**
     * @var string
     *
     * @ORM\Column(name="hairColor", type="string", length=255, nullable=true)
     */
    private $hairColor;

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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Post
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Post
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return Post
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     *
     * @return Post
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return int
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Post
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set skinType
     *
     * @param string $skinType
     *
     * @return Post
     */
    public function setSkinType($skinType)
    {
        $this->skinType = $skinType;

        return $this;
    }

    /**
     * Get skinType
     *
     * @return string
     */
    public function getSkinType()
    {
        return $this->skinType;
    }

    /**
     * Set skinColor
     *
     * @param string $skinColor
     *
     * @return Post
     */
    public function setSkinColor($skinColor)
    {
        $this->skinColor = $skinColor;

        return $this;
    }

    /**
     * Get skinColor
     *
     * @return string
     */
    public function getSkinColor()
    {
        return $this->skinColor;
    }

    /**
     * Set hairType
     *
     * @param string $hairType
     *
     * @return Post
     */
    public function setHairType($hairType)
    {
        $this->hairType = $hairType;

        return $this;
    }

    /**
     * Get hairType
     *
     * @return string
     */
    public function getHairType()
    {
        return $this->hairType;
    }

    /**
     * Set hairColor
     *
     * @param string $hairColor
     *
     * @return Post
     */
    public function setHairColor($hairColor)
    {
        $this->hairColor = $hairColor;

        return $this;
    }

    /**
     * Get hairColor
     *
     * @return string
     */
    public function getHairColor()
    {
        return $this->hairColor;
    }
}
