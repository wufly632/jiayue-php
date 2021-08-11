<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\ModelCache\Cacheable;
use HyperfExt\Auth\Authenticatable;
use HyperfExt\Auth\Contracts\AuthenticatableInterface;
use HyperfExt\Jwt\Contracts\JwtSubjectInterface;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Model implements AuthenticatableInterface,JwtSubjectInterface
{
    use Authenticatable, Cacheable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function getJwtIdentifier()
    {
        // TODO: Implement getJwtIdentifier() method.

        return $this->getKey();
    }

    public function getJwtCustomClaims(): array
    {
        // TODO: Implement getJwtCustomClaims() method.
        return [];
    }

    public function getAuthIdentifierName(): string
    {
        // TODO: Implement getAuthIdentifierName() method.
        return 'id';
    }

    public function getAuthIdentifier()
    {
        // TODO: Implement getAuthIdentifier() method.
        return (string)$this->attributes['id'];
    }

    public function getAuthPassword(): ?string
    {
        return $this->attributes['password'];
        // TODO: Implement getAuthPassword() method.
    }

    public function getRememberToken(): ?string
    {
        // TODO: Implement getRememberToken() method.
    }

    public function setRememberToken(string $value)
    {
        // TODO: Implement setRememberToken() method.
    }

    public function getRememberTokenName(): ?string
    {
        // TODO: Implement getRememberTokenName() method.
        return  'remember_token';
    }
}
