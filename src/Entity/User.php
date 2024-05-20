<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Cet email est déjà utilisé.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $nomPrenom;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un mot de passe au mini de 5 caracteres"
     *
     *     )
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="date")
     */
    private $DateNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Numtel;

    /**
     * @ORM\OneToMany(targetEntity=Reviews::class, mappedBy="Client")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ReservationFlight::class, mappedBy="clientId", orphanRemoval=true)
     */
    private $flightReservation;

    /**
     * @ORM\OneToMany(targetEntity=ReservationHotel::class, mappedBy="client", orphanRemoval=true)
     */
    private $hotelReservation;

    /**
     * @ORM\OneToMany(targetEntity=ReservationTour::class, mappedBy="client", orphanRemoval=true)
     */
    private $tourReservation;
    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $reset_token;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->flightReservation = new ArrayCollection();
        $this->hotelReservation = new ArrayCollection();
        $this->tourReservation = new ArrayCollection();
    }


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

    public function getNomPrenom(): ?string
    {
    return $this->nomPrenom;
    }

    public function setNomPrenom(string $nomPrenom): self
    {
    $this->nomPrenom = $nomPrenom;

    return $this;
    }


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;

    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->DateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $DateNaissance): self
    {
        $this->DateNaissance = $DateNaissance;

        return $this;
    }

    public function getNumtel(): ?string
    {
        return $this->Numtel;
    }

    public function setNumtel(string $Numtel): self
    {
        $this->Numtel = $Numtel;

        return $this;
    }

    /**
     * @return Collection<int, reviews>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(reviews $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setClient($this);
        }

        return $this;
    }

    public function removeUser(reviews $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClient() === $this) {
                $user->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReservationFlight>
     */
    public function getFlightReservation(): Collection
    {
        return $this->flightReservation;
    }

    public function addFlightReservation(ReservationFlight $flightReservation): self
    {
        if (!$this->flightReservation->contains($flightReservation)) {
            $this->flightReservation[] = $flightReservation;
            $flightReservation->setClientId($this);
        }

        return $this;
    }

    public function removeFlightReservation(ReservationFlight $flightReservation): self
    {
        if ($this->flightReservation->removeElement($flightReservation)) {
            // set the owning side to null (unless already changed)
            if ($flightReservation->getClientId() === $this) {
                $flightReservation->setClientId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReservationHotel>
     */
    public function getHotelReservation(): Collection
    {
        return $this->hotelReservation;
    }

    public function addHotelReservation(ReservationHotel $hotelReservation): self
    {
        if (!$this->hotelReservation->contains($hotelReservation)) {
            $this->hotelReservation[] = $hotelReservation;
            $hotelReservation->setClient($this);
        }

        return $this;
    }

    public function removeHotelReservation(ReservationHotel $hotelReservation): self
    {
        if ($this->hotelReservation->removeElement($hotelReservation)) {
            // set the owning side to null (unless already changed)
            if ($hotelReservation->getClient() === $this) {
                $hotelReservation->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReservationTour>
     */
    public function getTourReservation(): Collection
    {
        return $this->tourReservation;
    }

    public function addTourReservation(ReservationTour $tourReservation): self
    {
        if (!$this->tourReservation->contains($tourReservation)) {
            $this->tourReservation[] = $tourReservation;
            $tourReservation->setClient($this);
        }

        return $this;
    }

    public function removeTourReservation(ReservationTour $tourReservation): self
    {
        if ($this->tourReservation->removeElement($tourReservation)) {
            // set the owning side to null (unless already changed)
            if ($tourReservation->getClient() === $this) {
                $tourReservation->setClient(null);
            }
        }

        return $this;
    }

    public function assignRoles($userType): self
    {
        if ($userType === 'admin') {
            $this->roles = ['ROLE_ADMIN'];
        } elseif ($userType === 'client') {
            $this->roles = ['ROLE_CLIENT'];
        }
        return $this;
    }
    public function addRole($role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }
    public function removeRole($role): self
    {
        $key = array_search($role, $this->roles, true);
        if ($key !== false) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles); // Reindex the array
        }

        return $this;
    }
    /**
     * @return mixed
     */
    public function getResetToken()
    {
        return $this->reset_token;
    }

    /**
     * @param mixed $reset_token
     */
    public function setResetToken($reset_token): void
    {
        $this->reset_token = $reset_token;
    }
}
