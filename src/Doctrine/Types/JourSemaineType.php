<?php 

namespace App\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class JourSemaineType extends Type
{
    const ENUM_JOUR = 'jour_semaine';

    public function getName()
    {
        return self::ENUM_JOUR;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getStringTypeDeclarationSQL($fieldDeclaration);
    }

    
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        // Assurez-vous que la valeur est valide (par exemple, l'une des valeurs de votre ENUM).
        if (!in_array($value, ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'])) {
            throw new \InvalidArgumentException(sprintf('Invalid value for ENUM jour_semaine: %s', $value));
        }

        return $value; // La valeur est déjà valide pour un ENUM PostgreSQL.
    }


    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }
}