<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @ApiResource(
 *     attributes={
 *          "pagination_items_per_page"=10
 *     }
 * )
 */

class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $companyName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactEmail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="date")
     */
    private $foundedDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $contactPhone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyAdress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookLink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitterLink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyImageName;
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private  $userId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(name="stars", type="float")
     */
    private $stars=0;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return mixed
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param mixed $contactEmail
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getFoundedDate()
    {
        return $this->foundedDate;
    }

    /**
     * @param mixed $foundedDate
     */
    public function setFoundedDate($foundedDate)
    {
        $this->foundedDate = $foundedDate;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * @param mixed $contactPhone
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;
    }

    /**
     * @return mixed
     */
    public function getCompanyAdress()
    {
        return $this->companyAdress;
    }

    /**
     * @param mixed $companyAdress
     */
    public function setCompanyAdress($companyAdress)
    {
        $this->companyAdress = $companyAdress;
    }

    /**
     * @return mixed
     */
    public function getFacebookLink()
    {
        return $this->facebookLink;
    }

    /**
     * @param mixed $facebookLink
     */
    public function setFacebookLink($facebookLink)
    {
        $this->facebookLink = $facebookLink;
    }

    /**
     * @return mixed
     */
    public function getTwitterLink()
    {
        return $this->twitterLink;
    }

    /**
     * @param mixed $twitterLink
     */
    public function setTwitterLink($twitterLink)
    {
        $this->twitterLink = $twitterLink;
    }

    /**
     * @return mixed
     */
    public function getCompanyImageName()
    {
        return $this->companyImageName;
    }

    /**
     * @param mixed $companyImageName
     */
    public function setCompanyImageName($companyImageName)
    {
        $this->companyImageName = $companyImageName;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStars(): int
    {
        return $this->stars;
    }

    /**
     * @param int $stars
     */
    public function setStars(int $stars): void
    {
        $this->stars = $stars;
    }


}
