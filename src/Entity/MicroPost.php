<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\Table("micro_post")
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=280)
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */
    private $text;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $time;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return mixed
     */
    public function getText(): ?string
    {
        return $this->text;
    }
    
    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }
    
    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }
    
    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function setTimeOnPersist(): void 
    {
        $this->time = new \DateTime();
    }
    
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * @param User $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
}
