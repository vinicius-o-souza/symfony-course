<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="This e-mail is already used")
 * @UniqueEntity(fields="username", message="This username is already used")
 */
class User implements UserInterface, \Serializable
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=50)
     */
    private $username;
    
    /**
     * @ORM\Column(type="string")
     */
    private $password;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=4096)
     */
    private $plainPassword;
    
    /**
     * @ORM\Column(type="string", length=254, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(min=4, max=50)
     */
    private $fullName;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MicroPost", mappedBy="likedBy")
     */
    private $postsLiked;
    
    /**
     * @var array
     * @ORM\Column(type="simple_array")
     */
    private $roles;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user")
     */
    private $posts;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="following")
     */
    private $followers;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="followers")
     * @ORM\JoinTable(name="following",
     *      joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="following_user_id", referencedColumnName="id")
     *      }
     * )
     */
    private $following;
    
    /**
     * @ORM\Column(type="string", nullable=true, length=30)
     */
    private $confirmationToken;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;
    
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->postsLiked = new ArrayCollection();
        $this->roles = [self::ROLE_USER];
        $this->enabled = false;
    }
    
    public function getRoles()
    {
        return $this->roles;
    }
    
    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        
    }
    
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->enabled
        ]);
    }
    
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->enabled)  = unserialize($serialized);
            
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function getFullName()
    {
        return $this->fullName;
    }
    
    public function setFullName($fullName): void
    {
        $this->fullName = $fullName;
    }
    
    public function setUsername($username): void
    {
        $this->username = $username;
    }
    
    public function setEmail($email): void
    {
        $this->email = $email;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
    
    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }
    
    /**
     * @return Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }
    
    /**
     * @return Collection
     */
    public function getFollowing()
    {
        return $this->following;
    }
    
    public function follow(User $userToFollow)
    {
        if ($this->getFollowing()->contains($userToFollow)) {
            return;
        }
        $this->getFollowing()->add($userToFollow);
    }
    
    /**
     * @return Collection
     */
    public function getPostsLiked()
    {
        return $this->postsLiked;
    }
    
    /**
     * @return mixed
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }
    
    /**
     * @param mixed $confirmationToken
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }
    
    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    
    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
    
    public function isEnabled()
    {
        return $this->enabled;
    }

}
