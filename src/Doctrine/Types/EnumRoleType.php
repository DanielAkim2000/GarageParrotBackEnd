<?php 

namespace App\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EnumRoleType extends Type
{
    const ENUM_ROLE = 'role';

    public function getName()
    {
        return self::ENUM_ROLE;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'varchar(50)';
    }

    
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        // Assurez-vous que la valeur est valide (par exemple, l'une des valeurs de votre ENUM).
        if (!in_array($value, ['Administrateur', 'Employe'])) {
            throw new \InvalidArgumentException(sprintf('Invalid value for ENUM role: %s', $value));
        }

        return $value; // La valeur est déjà valide pour un ENUM PostgreSQL.
    }


    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }
}
