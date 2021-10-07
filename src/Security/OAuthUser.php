<?php

declare(strict_types=1);

namespace App\Security;

use App\Utils\HydrateTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * The OAuthUser class is the main representation of an S2P authenticated user.
 * -----.
 */
class OAuthUser implements UserInterface
{
    use HydrateTrait;
    private LoggerInterface $logger;

    private string $username;

    private string $sub;

    private string $name;
    private string $email;
    private string $phone;
    private string $fax;
    private string $mobile;
    private string $realm;

    private array $roles = [];

    /**
     * `payload` is an introspection response (as https://tools.ietf.org/html/rfc7662#section-2.2).
     * It contains information about the authenticated resource (e.g. exp, iat, jti, ...).
     * Some user information are included in the `metadata` attribute which will be used
     * to hydrate the OAuthUser instance.
     */
    public function __construct(LoggerInterface $logger, string $username, array $payload = [])
    {
        $this->logger = $logger;
        $this->username = $username;
        $this->logger->debug('[OAuthUser] - '.$username);

        $this->setSub($payload['sub']);

        if (!empty($payload['metadata'])) {
            $this->logger->debug('[OAuthUser] - user attributes:');
            foreach ($payload['metadata'] as $attribute => $value) {
                $this->logger->debug('[OAuthUser] - : '.$attribute.' = '.$value);
            }
            $this->hydrate($payload['metadata']);
        } else {
            $this->logger->debug('[OAuthUser] - no user attributes.');
        }
    }

    public function __toString()
    {
        $dump = [];
        foreach (get_object_vars($this) as $attribute => $value) {
            if (is_scalar($value)) {
                $dump[] = $attribute.' = "'.$value.'"';
            }
        }

        return implode(', ', $dump);
    }

    public function getId()
    {
        return $this->getSub();
    }

    public function getSub(): ?string
    {
        return $this->sub;
    }

    public function setSub(string $sub): self
    {
        $this->sub = $sub;

        return $this;
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

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
//        $this->plainPassword = null;
    }
}
