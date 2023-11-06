<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231104203855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE contacts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE equipementsoptions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE horairesouverture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE jour_semaine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE services_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE temoignages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contacts (id INT NOT NULL, voiture_id UUID DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, numero_telephone VARCHAR(20) DEFAULT NULL, message TEXT NOT NULL, sujet VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_33401573181A8BA ON contacts (voiture_id)');
        $this->addSql('COMMENT ON COLUMN contacts.voiture_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE equipementsoptions (id INT NOT NULL, voiture_id UUID DEFAULT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7561C74D181A8BA ON equipementsoptions (voiture_id)');
        $this->addSql('COMMENT ON COLUMN equipementsoptions.voiture_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE horairesouverture (id INT NOT NULL, jour_semaine VARCHAR(20) DEFAULT NULL, heure_ouverture TIME(0) WITHOUT TIME ZONE NOT NULL, heure_fermeture TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C1CC7CBDE8E3089 ON horairesouverture (jour_semaine)');
        $this->addSql('CREATE TABLE jour_semaine (id VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE services (id INT NOT NULL, nom VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE temoignages (id INT NOT NULL, nom VARCHAR(255) DEFAULT NULL, commentaire TEXT NOT NULL, note INT DEFAULT NULL, modere BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE utilisateurs (id UUID NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX utilisateurs_email_key ON utilisateurs (email)');
        $this->addSql('COMMENT ON COLUMN utilisateurs.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN utilisateurs.roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE voituresoccasion (id UUID NOT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, annee_mise_en_circulation INT NOT NULL, prix NUMERIC(10, 2) NOT NULL, kilometrage INT NOT NULL, image_path VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN voituresoccasion.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE contacts ADD CONSTRAINT FK_33401573181A8BA FOREIGN KEY (voiture_id) REFERENCES voituresoccasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE equipementsoptions ADD CONSTRAINT FK_7561C74D181A8BA FOREIGN KEY (voiture_id) REFERENCES voituresoccasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE horairesouverture ADD CONSTRAINT FK_8C1CC7CBDE8E3089 FOREIGN KEY (jour_semaine) REFERENCES jour_semaine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE contacts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE equipementsoptions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE horairesouverture_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE jour_semaine_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE services_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE temoignages_id_seq CASCADE');
        $this->addSql('ALTER TABLE contacts DROP CONSTRAINT FK_33401573181A8BA');
        $this->addSql('ALTER TABLE equipementsoptions DROP CONSTRAINT FK_7561C74D181A8BA');
        $this->addSql('ALTER TABLE horairesouverture DROP CONSTRAINT FK_8C1CC7CBDE8E3089');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE equipementsoptions');
        $this->addSql('DROP TABLE horairesouverture');
        $this->addSql('DROP TABLE jour_semaine');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE temoignages');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('DROP TABLE voituresoccasion');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
