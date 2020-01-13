<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table()
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
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /* Jointure ManyToOne - Cardinalité multiple qui a le inversedBy (nom de la propriété dans l'entité jointe) ; c'est lui qui detient la clé étrangère */
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /*
        joinColumns={@ORM\JoinColumn(name="post_id",referencedColumnName="id")} --> propriété Id Entité courante
        inverseJoinColumns={@ORM\JoinColumn(name="user_id",referencedColumnName="id")} --> propriété Id Entité dans Inversed by
    */
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     * @ORM\JoinTable(name="post_likes",
     *      joinColumns={@ORM\JoinColumn(name="post_id",referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id",referencedColumnName="id")}
     * )
     */
    private $likedBy;

    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
    }

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
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getText()
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
    public function setTimeOnPersist(): void {
        $this->time = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    public function like(User $user)
    {
        if($this->likedBy->contains($user))
        {
            return;
        }

        $this->likedBy->add($user);
    }




    /* Comment section

        - Don't forget to use annotation @ORM\Table which is necessary to create table with command doctrine:migration
        - Don't forget name space for validators constraints
        - @Assert\Length(min=10,minMessage="Custom error message")
        - before using a lifecycle callbacks we have to
    */
}


