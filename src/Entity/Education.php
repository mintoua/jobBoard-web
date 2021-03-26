<?php

namespace App\Entity;

use App\Repository\EducationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EducationRepository::class)
 */
class Education
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
    private $course;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $datefrom;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $dateto;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $institute;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CandidateResume", inversedBy="education")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resume;

    public function getId()
    {
        return $this->id;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setCourse(string $course)
    {
        $this->course = $course;

        return $this;
    }

    public function getDatefrom(): ?\DateTimeInterface
    {
        return $this->datefrom;
    }

    public function setDatefrom(\DateTimeInterface $datefrom)
    {
        $this->datefrom = $datefrom;

        return $this;
    }

    public function getDateto(): ?\DateTimeInterface
    {
        return $this->dateto;
    }

    public function setDateto(\DateTimeInterface $dateto)
    {
        $this->dateto = $dateto;

        return $this;
    }

    public function getInstitute()
    {
        return $this->institute;
    }

    public function setInstitute(string $institute)
    {
        $this->institute = $institute;

        return $this;
    }

    public function getResume(): ?CandidateResume
    {
        return $this->resume;
    }

    public function setResume(?CandidateResume $resume)
    {
        $this->resume = $resume;

        return $this;
    }
}
