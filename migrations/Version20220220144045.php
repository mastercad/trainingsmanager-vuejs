<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220220144045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS=0');
        $this->addSql('ALTER TABLE dashboard_x_widget CHANGE dashboard dashboard INT UNSIGNED DEFAULT NULL, CHANGE widget widget INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE dashboards CHANGE user user CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE flag_active flag_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE device_groups CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE device_options CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE device_x_device_group CHANGE device device INT UNSIGNED DEFAULT NULL, CHANGE device_group device_group INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE device_x_device_option CHANGE device device INT UNSIGNED DEFAULT NULL, CHANGE device_option device_option INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE devices CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE exercise_options CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE exercise_types CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE exercise_x_device CHANGE device device INT UNSIGNED DEFAULT NULL, CHANGE exercise exercise INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE exercise_x_device_option CHANGE exercise exercise INT UNSIGNED DEFAULT NULL, CHANGE `option` `option` INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE exercise_x_exercise_option CHANGE exercise exercise INT UNSIGNED DEFAULT NULL, CHANGE exercise_option exercise_option INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE exercise_x_exercise_type CHANGE exercise exercise INT UNSIGNED DEFAULT NULL, CHANGE exercise_type exercise_type INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE exercise_x_muscle CHANGE muscle muscle INT UNSIGNED DEFAULT NULL, CHANGE exercise exercise INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE exercises CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE muscle_groups CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE muscle_x_muscle_group CHANGE muscle muscle INT UNSIGNED DEFAULT NULL, CHANGE muscle_group muscle_group INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE muscles CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE session CHANGE session_id session_id CHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE training_diaries CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE training_diary_x_device_option CHANGE device_option device_option INT UNSIGNED DEFAULT NULL, CHANGE training_plan_exercise training_plan_exercise INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE training_diary_x_exercise_option CHANGE exercise_option exercise_option INT UNSIGNED DEFAULT NULL, CHANGE training_plan_exercise training_plan_exercise INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE training_diary_x_training_plan_device CHANGE flag_finished flag_finished TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE training_diary_x_training_plan_exercise CHANGE training_plan_x_exercise training_plan_x_exercise INT UNSIGNED DEFAULT NULL, CHANGE training_diary training_diary INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE flag_finished flag_finished TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE training_plan_layouts CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('DROP INDEX training_plan_x_device_option_id ON training_plan_x_device_option');
        $this->addSql('ALTER TABLE training_plan_x_device_option CHANGE device_option device_option INT UNSIGNED DEFAULT NULL, CHANGE training_plan_x_exercise training_plan_x_exercise INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE training_plan_x_exercise CHANGE exercise exercise INT UNSIGNED DEFAULT NULL, CHANGE training_plan training_plan INT UNSIGNED DEFAULT NULL, CHANGE creater creater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE training_plan_x_exercise_option CHANGE exercise_option exercise_option INT UNSIGNED DEFAULT NULL, CHANGE training_plan_x_exercise training_plan_x_exercise INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE training_plans CHANGE training_plan_layout training_plan_layout INT UNSIGNED DEFAULT NULL, CHANGE user user CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE parent parent INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE active active TINYINT(1) NOT NULL COMMENT \'trainingsplan aktiv? damit nur der aktuellste angezeigt wird im tagebuch zum training\'');
        $this->addSql('ALTER TABLE user_state CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE users CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE state state INT UNSIGNED DEFAULT NULL, CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE flag_logged_in flag_logged_in TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE widgets CHANGE creator creator CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updater updater CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE editable editable TINYINT(1) NOT NULL');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_back (id INT UNSIGNED AUTO_INCREMENT NOT NULL, first_name VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, last_name VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, login VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, profile_picture_path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, password VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, roles JSON NOT NULL, facebook_id VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, twitter_id VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, google_plus_id VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, session_timeout INT DEFAULT NULL, last_login DATETIME DEFAULT NULL, login_count INT DEFAULT NULL, session_id VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, flag_logged_in TINYINT(1) DEFAULT 0 NOT NULL, flag_multilogin TINYINT(1) DEFAULT NULL, validate_hash VARCHAR(250) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, state INT UNSIGNED NOT NULL, created DATETIME NOT NULL, creator INT UNSIGNED NOT NULL, updated DATETIME DEFAULT NULL, updater INT UNSIGNED DEFAULT NULL, INDEX user_create_user_fk (creator), INDEX user_id (id), INDEX user_state_fk (state), INDEX user_update_user_fk (updater), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE dashboard_x_widget CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE dashboard dashboard INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL, CHANGE widget widget INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE dashboards CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL, CHANGE user user INT UNSIGNED NOT NULL, CHANGE flag_active flag_active TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE device_groups CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE device_options CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE device_x_device_group CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE device device INT UNSIGNED NOT NULL, CHANGE device_group device_group INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE device_x_device_option CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE device device INT UNSIGNED NOT NULL, CHANGE device_option device_option INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE devices CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise_options CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise_types CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise_x_device CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE device device INT UNSIGNED NOT NULL, CHANGE exercise exercise INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise_x_device_option CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE `option` `option` INT UNSIGNED NOT NULL, CHANGE exercise exercise INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise_x_exercise_option CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE exercise exercise INT UNSIGNED NOT NULL, CHANGE exercise_option exercise_option INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise_x_exercise_type CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE exercise exercise INT UNSIGNED NOT NULL, CHANGE exercise_type exercise_type INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise_x_muscle CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE exercise exercise INT UNSIGNED NOT NULL, CHANGE muscle muscle INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE exercises CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE muscle_groups CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE muscle_x_muscle_group CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE muscle muscle INT UNSIGNED NOT NULL, CHANGE muscle_group muscle_group INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE muscles CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE session CHANGE session_id session_id CHAR(32) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_0900_ai_ci`');
        $this->addSql('ALTER TABLE training_diaries CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE training_diary_x_device_option CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE device_option device_option INT UNSIGNED NOT NULL, CHANGE training_plan_exercise training_plan_exercise INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE training_diary_x_exercise_option CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE exercise_option exercise_option INT UNSIGNED NOT NULL, CHANGE training_plan_exercise training_plan_exercise INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE training_diary_x_training_plan_device CHANGE flag_finished flag_finished TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE training_diary_x_training_plan_exercise CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE training_diary training_diary INT UNSIGNED NOT NULL, CHANGE training_plan_x_exercise training_plan_x_exercise INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL, CHANGE flag_finished flag_finished TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE training_plan_layouts CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE training_plan_x_device_option CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE device_option device_option INT UNSIGNED NOT NULL, CHANGE training_plan_x_exercise training_plan_x_exercise INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('CREATE INDEX training_plan_x_device_option_id ON training_plan_x_device_option (id)');
        $this->addSql('ALTER TABLE training_plan_x_exercise CHANGE creater creater INT UNSIGNED NOT NULL, CHANGE exercise exercise INT UNSIGNED NOT NULL, CHANGE training_plan training_plan INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE training_plan_x_exercise_option CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE exercise_option exercise_option INT UNSIGNED NOT NULL, CHANGE training_plan_x_exercise training_plan_x_exercise INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE training_plans CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE parent parent INT UNSIGNED DEFAULT NULL COMMENT \'haupttrainingsplan (für splits)\', CHANGE training_plan_layout training_plan_layout INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL, CHANGE user user INT UNSIGNED NOT NULL COMMENT \'user für den dieser trainingsplan gilt\', CHANGE active active TINYINT(1) DEFAULT 0 NOT NULL COMMENT \'trainingsplan aktiv? damit nur der aktuellste angezeigt wird im tagebuch zum training\'');
        $this->addSql('ALTER TABLE user_state CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE state state INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL, CHANGE flag_logged_in flag_logged_in TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE widgets CHANGE creator creator INT UNSIGNED NOT NULL, CHANGE updater updater INT UNSIGNED DEFAULT NULL, CHANGE editable editable TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
