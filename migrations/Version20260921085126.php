<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260921085126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(11) NOT NULL, nom VARCHAR(32) NOT NULL, prenom VARCHAR(32) NOT NULL, adresse VARCHAR(128) NOT NULL, telephone INT NOT NULL, date_naissance DATE NOT NULL, nb_enfant INT DEFAULT NULL, age_enfant JSON DEFAULT NULL, mail_id INT NOT NULL, UNIQUE INDEX UNIQ_C7440455C8776F01 (mail_id), UNIQUE INDEX UNIQ_IDENTIFIER_PHONE (telephone), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE client_sport (client_id INT NOT NULL, sport_id INT NOT NULL, INDEX IDX_B9E8736519EB6921 (client_id), INDEX IDX_B9E87365AC78BCF8 (sport_id), PRIMARY KEY (client_id, sport_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, adresse_livraison VARCHAR(128) DEFAULT NULL, client_id INT NOT NULL, magasin_id INT DEFAULT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), INDEX IDX_6EEAA67D20096AE3 (magasin_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE entrepot (id INT AUTO_INCREMENT NOT NULL, ville VARCHAR(32) NOT NULL, zone_id INT NOT NULL, INDEX IDX_D805175A9F2C3FAB (zone_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(32) NOT NULL, description VARCHAR(128) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(32) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_NAME (nom), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE historique_etat (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, commande_id INT NOT NULL, etat_id INT NOT NULL, INDEX IDX_D193D08082EA2E54 (commande_id), INDEX IDX_D193D080D5E86FF (etat_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE magasin (id INT AUTO_INCREMENT NOT NULL, ville VARCHAR(32) NOT NULL, zone_id INT NOT NULL, INDEX IDX_54AF5F279F2C3FAB (zone_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, qte INT NOT NULL, produit_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_24CC0DF2F347EFB (produit_id), INDEX IDX_24CC0DF219EB6921 (client_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, chemin VARCHAR(128) NOT NULL, produit_id INT NOT NULL, INDEX IDX_14B78418F347EFB (produit_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, ref VARCHAR(18) NOT NULL, description VARCHAR(128) NOT NULL, prix NUMERIC(6, 2) NOT NULL, fournisseur_id INT NOT NULL, sport_id INT NOT NULL, INDEX IDX_29A5EC27670C757F (fournisseur_id), INDEX IDX_29A5EC27AC78BCF8 (sport_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE produit_commande (id INT AUTO_INCREMENT NOT NULL, qte INT NOT NULL, produit_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_47F5946EF347EFB (produit_id), INDEX IDX_47F5946E82EA2E54 (commande_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sport (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(32) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE stock_entrepot (id INT AUTO_INCREMENT NOT NULL, qte INT NOT NULL, produit_id INT NOT NULL, entrepot_id INT NOT NULL, INDEX IDX_97C34318F347EFB (produit_id), INDEX IDX_97C3431872831E97 (entrepot_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE stock_magasin (id INT AUTO_INCREMENT NOT NULL, qte INT NOT NULL, produit_id INT NOT NULL, magasin_id INT NOT NULL, INDEX IDX_4D094F84F347EFB (produit_id), INDEX IDX_4D094F8420096AE3 (magasin_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(32) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455C8776F01 FOREIGN KEY (mail_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE client_sport ADD CONSTRAINT FK_B9E8736519EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_sport ADD CONSTRAINT FK_B9E87365AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D20096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE entrepot ADD CONSTRAINT FK_D805175A9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE historique_etat ADD CONSTRAINT FK_D193D08082EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE historique_etat ADD CONSTRAINT FK_D193D080D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE magasin ADD CONSTRAINT FK_54AF5F279F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF219EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('ALTER TABLE produit_commande ADD CONSTRAINT FK_47F5946EF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit_commande ADD CONSTRAINT FK_47F5946E82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE stock_entrepot ADD CONSTRAINT FK_97C34318F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE stock_entrepot ADD CONSTRAINT FK_97C3431872831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id)');
        $this->addSql('ALTER TABLE stock_magasin ADD CONSTRAINT FK_4D094F84F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE stock_magasin ADD CONSTRAINT FK_4D094F8420096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455C8776F01');
        $this->addSql('ALTER TABLE client_sport DROP FOREIGN KEY FK_B9E8736519EB6921');
        $this->addSql('ALTER TABLE client_sport DROP FOREIGN KEY FK_B9E87365AC78BCF8');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D20096AE3');
        $this->addSql('ALTER TABLE entrepot DROP FOREIGN KEY FK_D805175A9F2C3FAB');
        $this->addSql('ALTER TABLE historique_etat DROP FOREIGN KEY FK_D193D08082EA2E54');
        $this->addSql('ALTER TABLE historique_etat DROP FOREIGN KEY FK_D193D080D5E86FF');
        $this->addSql('ALTER TABLE magasin DROP FOREIGN KEY FK_54AF5F279F2C3FAB');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2F347EFB');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF219EB6921');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418F347EFB');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27670C757F');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27AC78BCF8');
        $this->addSql('ALTER TABLE produit_commande DROP FOREIGN KEY FK_47F5946EF347EFB');
        $this->addSql('ALTER TABLE produit_commande DROP FOREIGN KEY FK_47F5946E82EA2E54');
        $this->addSql('ALTER TABLE stock_entrepot DROP FOREIGN KEY FK_97C34318F347EFB');
        $this->addSql('ALTER TABLE stock_entrepot DROP FOREIGN KEY FK_97C3431872831E97');
        $this->addSql('ALTER TABLE stock_magasin DROP FOREIGN KEY FK_4D094F84F347EFB');
        $this->addSql('ALTER TABLE stock_magasin DROP FOREIGN KEY FK_4D094F8420096AE3');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE client_sport');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE entrepot');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE historique_etat');
        $this->addSql('DROP TABLE magasin');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_commande');
        $this->addSql('DROP TABLE sport');
        $this->addSql('DROP TABLE stock_entrepot');
        $this->addSql('DROP TABLE stock_magasin');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE zone');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
