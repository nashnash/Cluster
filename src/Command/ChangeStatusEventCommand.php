<?php


namespace App\Command;

use App\Repository\EventRepository;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ChangeStatusEventCommand
 * @package App\Command
 */
class ChangeStatusEventCommand extends Command
{
    private $eventRepository;
    private $em;
    protected static $defaultName = 'app:changeStatusEvent';

    /**
     * ChangeStatusEventCommand constructor.
     * @param string|null $name
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(string $name = null,EventRepository $eventRepository,
                                EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->eventRepository = $eventRepository;
        $this->em = $em ;


    }


    protected function configure()
    {
        $this
            ->setDescription('edits the status of the Event Entity')
            ->setHelp('This command allows you to edit the status of the Event Entity...')
            ->addOption('dry-run', null, InputOption::VALUE_NONE,
                'Dry run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('dry-run')) {
            $io->note('Dry mode enabled');

            $count = $this->commentRepository->countOldRejected();
        } else {
            $count = $this->commentRepository->deleteOldRejected();
        }

        $io->success(sprintf('Deleted "%d" old rejected/spam comments.', $count));

        return 0;
    }
}
