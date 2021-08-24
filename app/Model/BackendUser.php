<?php


namespace App\Model;


use Hyperf\ModelCache\Cacheable;
use HyperfExt\Auth\Authenticatable;
use HyperfExt\Auth\Contracts\AuthenticatableInterface;
use HyperfExt\Jwt\Contracts\JwtSubjectInterface;

class BackendUser extends Model implements AuthenticatableInterface,JwtSubjectInterface
{
    use Authenticatable, Cacheable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'backend_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mobile','password','nickname','age','name','sex','qq','address'];
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
