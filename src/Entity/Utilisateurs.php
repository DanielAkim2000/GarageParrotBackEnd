<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Utilisateurs
 *
 * @ORM\Table(name="utilisateurs", uniqueConstraints={@ORM\UniqueConstraint(name="utilisateurs_email_key", columns={"email"})})
 * @ORM\Entity
 */
class Utilisateurs implements UserInterface,PasswordHasherInterface
{
    /**
     * @var string
     * 
     * @ORM\Id
     * 
     * @ORM\Column(type="uuid", unique=true)
     * 
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * 
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firtsname", type="string", length=255, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var array|null
     *
     * @ORM\Column(name="roles", type="array", nullable=true)
     */
    private $roles;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
    public function hash(string $plainPassword): string
    {
        // Utiliser l'algorithme bcrypt pour hacher le mot de passe
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        return $hashedPassword;
    }
    public function verify(string $hashedPassword, string $submittedPassword): bool
    {
        // Vérifier si le mot de passe soumis correspond au mot de passe haché
        return password_verify($submittedPassword, $hashedPassword);
    }
    public function needsRehash(string $hashedPassword): bool
    {
        // Vérifier si le mot de passe nécessite d'être rehaché
        return password_needs_rehash($hashedPassword, PASSWORD_BCRYPT);
    }
        

    function getSalt(){}
    function eraseCredentials(){}
    function getUsername(){
        return $this->firstname+' '+$this->lastname;
    }

}
