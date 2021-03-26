<?php

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CertificationRepository::class)
 */
class Certification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $label;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CandidateResume", inversedBy="certification")
     *
     */
    private  $resume;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageName;
    public function getImageName()
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * @param mixed $resume
     */
    public function setResumeId($resume)
    {
        $this->resume = $resume;
    }




}
