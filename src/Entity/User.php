<?php
/**
 * Created by PhpStorm.
 * User: Ryaan
 * Date: 17/01/18
 * Time: 10:22
 */

namespace App\Entity;

use App\Validator\Constraints\ComplexPassword;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @UniqueEntity(fields={"email"}, message="user.exists")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(groups={"Registration"})
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(groups={"Registration", "PasswordReset"})
     * @Assert\Length(min="6")
     * @ComplexPassword()
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $roles;
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $professionaltitle;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $token;
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="date")
     */
    private $dateOfBirth;
    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", nullable=true)
     */
    private $lng;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", nullable=true)
     */
    private $lat;
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $activatedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phone;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $imageName;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactemail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $foundeddate;

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
    private $contactphone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyadress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebooklink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitterlink;


    public function getImageName()
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getProfessionaltitle()
    {
        return $this->professionaltitle;
    }

    /**
     * @param mixed $professionaltitle
     */
    public function setProfessionaltitle($professionaltitle)
    {
        $this->professionaltitle = $professionaltitle;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param mixed $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }


    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param mixed $activatedAt
     */
    public function setActivatedAt($activatedAt)
    {
        $this->activatedAt = $activatedAt;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): User
    {
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }
        foreach ($roles as $role) {
            if (substr($role, 0, 5) !== 'ROLE_') {
                throw new InvalidArgumentException("Chaque rôle doit commencer par 'ROLE_'");
            }
        }
        $this->roles = $roles;
        return $this;
    }

    // not used

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }


    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }


    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->phone,
            $this->roles,
            $this->password,
            $this->isActive,

        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->phone,
            $this->roles,
            $this->password,
            $this->isActive,

            ) = unserialize($serialized);
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     */
    public function setLng(float $lng)
    {
        $this->lng = $lng;
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     */
    public function setLat(float $lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getCompanyname()
    {
        return $this->companyname;
    }

    /**
     * @param mixed $companyname
     */
    public function setCompanyname($companyname)
    {
        $this->companyname = $companyname;
    }

    /**
     * @return mixed
     */
    public function getContactemail()
    {
        return $this->contactemail;
    }

    /**
     * @param mixed $contactemail
     */
    public function setContactemail($contactemail)
    {
        $this->contactemail = $contactemail;
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
    public function getFoundeddate()
    {
        return $this->foundeddate;
    }

    /**
     * @param mixed $foundeddate
     */
    public function setFoundeddate($foundeddate)
    {
        $this->foundeddate = $foundeddate;
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
    public function getContactphone()
    {
        return $this->contactphone;
    }

    /**
     * @param mixed $contactphone
     */
    public function setContactphone($contactphone)
    {
        $this->contactphone = $contactphone;
    }

    /**
     * @return mixed
     */
    public function getCompanyadress()
    {
        return $this->companyadress;
    }

    /**
     * @param mixed $companyadress
     */
    public function setCompanyadress($companyadress)
    {
        $this->companyadress = $companyadress;
    }

    /**
     * @return mixed
     */
    public function getFacebooklink()
    {
        return $this->facebooklink;
    }

    /**
     * @param mixed $facebooklink
     */
    public function setFacebooklink($facebooklink)
    {
        $this->facebooklink = $facebooklink;
    }

    /**
     * @return mixed
     */
    public function getTwitterlink()
    {
        return $this->twitterlink;
    }

    /**
     * @param mixed $twitterlink
     */
    public function setTwitterlink($twitterlink)
    {
        $this->twitterlink = $twitterlink;
    }


}
