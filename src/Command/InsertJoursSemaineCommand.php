<?php 


namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\JourSemaine;

class InsertJoursSemaineCommand extends Command
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
            ->setName('app:insert-jours-semaine')
            ->setDescription('Insertion jours de la semaine dans la base de donnée');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Début de l\'insertion des jours de la semaine...');

        $weekdays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        foreach ($weekdays as $dayName) {
            $jourSemaine = new JourSemaine();
            $jourSemaine->setId($dayName);

            $this->entityManager->persist($jourSemaine);
        }

        $this->entityManager->flush();

        $output->writeln('Jours de la semaine inséré avec succès.');

        return Command::SUCCESS;
    }
}
