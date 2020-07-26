<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 * fields= {"email"},
 * message= "L'email est déja utilisée!"
 * )
 */
class User implements UserInterface
{
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('username', new Assert\Length([
            'min' => 2,
            'minMessage' => 'Le nom d\'utilisateur doit contenir au moins {{ limit }} caractères!',
            'allowEmptyString' => false
        ]));

        $metadata->addPropertyConstraint('email', new Assert\Length([
            'min' => 2,
            'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères!',
            'allowEmptyString' => false
        ]));

        $metadata->addPropertyConstraint('password', new Assert\Length([
            'min' => 8,
            'minMessage' => "Le mdp doit contenir au moins 8 caractères!",
            'allowEmptyString' => false

        ]));

        $metadata->addPropertyConstraint('confirm_password', new Assert\EqualTo([
            'propertyPath' => 'password',
            'message' => 'Les mots de passe doivent être identiques!',
        ]));
        
    }


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;


    public $confirm_password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials() {}

    public function getSalt() {}

    public function getRoles() {
        return ['ROLE_USER'];
    }
}
