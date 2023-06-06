<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230606164020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO club (name,budget) VALUES ("Deportivo",445000);');
        $this->addSql('INSERT INTO club (name,budget) VALUES ("Real Zaragoza",627000);');
        $this->addSql('INSERT INTO club (name,budget) VALUES ("Sevilla FC",601000);');
        $this->addSql('INSERT INTO club (name,budget) VALUES ("Valencia CF",435000);');
        $this->addSql('INSERT INTO club (name,budget) VALUES ("Atlético Madrid",751000);');
        $this->addSql('INSERT INTO club (name,budget) VALUES ("Athletic Bilbao",685000);');
        $this->addSql('INSERT INTO club (name,budget) VALUES ("FC Barcelona",663000);');
        $this->addSql('INSERT INTO club (name,budget) VALUES ("Real Madrid",521000);');
        
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Gavi",null,24000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Pedri",5,94000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Alvaro Morata",null,78000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Sergio Busquets",null,43000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Jordi Alba",null,77000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Kepa Arrizabalaga",6,57000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Marco Asensio",8,25000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Ansu Fali",null,79000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Unai Simón",null,86000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Rodri",5,68000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Ferran Torres",8,67000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Pedro Porro",5,74000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Dani Olmo",5,51000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Dani Ceballos",5,96000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("NIco Williams",6,61000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Iago Asps",null,22000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Miguel Oyarzabal",null,62000);');
        $this->addSql('INSERT INTO player (name,club_id,salary) VALUES ("Borja Iglesias",null,52000);');

        $this->addSql('INSERT INTO coach (name,club_id,salary) VALUES ("Luis Enrique",null,166000);');
        $this->addSql('INSERT INTO coach (name,club_id,salary) VALUES ("Julen Lopetegui",6,138000);');
        $this->addSql('INSERT INTO coach (name,club_id,salary) VALUES ("Vicente del Bosque",null,55000);');
        $this->addSql('INSERT INTO coach (name,club_id,salary) VALUES ("Luis Aragones",1,158000);');
        $this->addSql('INSERT INTO coach (name,club_id,salary) VALUES ("Fernando Hierro",8,135000);');
        $this->addSql('INSERT INTO coach (name,club_id,salary) VALUES ("José Antonio Camacho",null,173000);');
        $this->addSql('INSERT INTO coach (name,club_id,salary) VALUES ("Ricardo Zamora",null,176000);');
        $this->addSql('INSERT INTO coach (name,club_id,salary) VALUES ("Javier Clemente",7,113000);');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
