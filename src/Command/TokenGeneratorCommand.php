<?php


namespace App\Command;


use App\Security\TokenGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TokenGeneratorCommand extends  Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'test:create-token';

    protected function configure()
    { $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Create a new Token')
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('Test a Token Generator Class...')
        // configure an argument
        ->addArgument('length', InputArgument::REQUIRED, 'The length of the token.');
        // ...

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //OUTPUT WITH TABLE
        $table = new Table($output);
        $token = new TokenGenerator();
        $dateNow = new \DateTime();
        $dateNow->modify('+1 hour');

        $table
            ->setHeaders(['Author','Time','Class','Token Length','Token Generated'])
            ->setRows([
                ['Arsene Kevin' , $dateNow->format('d-m-Y H:i:s'), get_class($token) , $input->getArgument('length') , $token->getRandomSecureToken($input->getArgument('length')) ]
            ]);

        $table->render();

        // outputs multiple lines to the console (adding "\n" at the end of each line)
       /* $output->writeln([
            'Token Generator Process',
            '============',
            '',
        ]);

        $output->writeln('Token Generated -> '. $token->getRandomSecureToken($input->getArgument('length')));*/

        return 0;
    }
}