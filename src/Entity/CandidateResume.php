<?php

namespace App\Entity;

use App\Repository\CandidateResumeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CandidateResumeRepository::class)
 */
class CandidateResume
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ResumeHeadline;

    /**
     * @ORM\Column(type="array", length=255,nullable=true)
     */
    private $skills;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $experience;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Certification", mappedBy="resume",cascade={"persist"})
     *
     */
    private $certification;
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private  $userId;

    /**
     * @ORM\OneToMany(targetEntity=Education::class, mappedBy="resume")
     */
    private $education;

    public function __construct()
    {
        $this->education = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getResumeHeadline()
    {
        return $this->ResumeHeadline;
    }

    /**
     * @param mixed $ResumeHeadline
     */
    public function setResumeHeadline($ResumeHeadline)
    {
        $this->ResumeHeadline = $ResumeHeadline;
    }

    /**
     * @return mixed
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param mixed $skills
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;
    }

    /**
     * @return mixed
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param mixed $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }



    /**
     * @param mixed $certification
     */
    public function setCertification($certification)
    {
        $this->certification = $certification;
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
     * @return Collection|Certification[]
     */
    public function getCertification()
    {
        return $this->certification;
    }

    public function addCertification(Certification $certification)
    {
        if (!$this->certification->contains($certification)) {
            $this->certification[] = $certification;
            $certification->setResumeId($this);
        }

        return $this;
    }

    public function removeImage(Certification $certification)
    {
        if ($this->images->contains($certification)) {
            $this->images->removeElement($certification);
            // set the owning side to null (unless already changed)
            if ($certification->setResumeId() === $this) {
                $certification->setResumeId(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Education[]
     */
    public function getEducation(): Collection
    {
        return $this->education;
    }

    public function addEducation(Education $education): self
    {
        if (!$this->education->contains($education)) {
            $this->education[] = $education;
            $education->setResume($this);
        }

        return $this;
    }

    public function removeEducation(Education $education): self
    {
        if ($this->education->removeElement($education)) {
            // set the owning side to null (unless already changed)
            if ($education->getResume() === $this) {
                $education->setResume(null);
            }
        }

        return $this;
    }




}
