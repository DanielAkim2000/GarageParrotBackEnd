<?php 
// src/Command/InsertUserCommand.php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateurs;

class InsertUserCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('app:insert-user')
            ->setDescription('Insertion d\'un Admin en Bdd')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('firstname', InputArgument::REQUIRED, 'User first name')
            ->addArgument('lastname', InputArgument::REQUIRED, 'User last name')
            ->addArgument('password', InputArgument::REQUIRED, 'User password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Début de l\'insertion..');

        $adminUser = new Utilisateurs();
        $adminUser->setFirstname($input->getArgument('firstname'));
        $adminUser->setLastname($input->getArgument('lastname'));
        $adminUser->setEmail($input->getArgument('email'));
        $adminUser->setRoles(['ROLE_ADMIN', 'ROLE_USER']); // Roles admin et user
        $adminUser->setPassword($input->getArgument('password')); //

        $this->entityManager->persist($adminUser);
        $this->entityManager->flush();

        $output->writeln('Admin utilisateurs inséré avec succès.');

        return Command::SUCCESS;
    }
}
