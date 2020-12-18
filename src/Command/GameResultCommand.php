<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Helper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question;

class GameResultCommand extends Command
{
    protected static $defaultName = 'gameResult';
    protected $team = array();
    protected $is_valid = false;
    protected $error;
    protected $result = 'Win';

    protected function configure()
    {
        $this
        ->setDescription('Eastern Enterprise – Assignment');
    }
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $formatter = $this->getHelper('formatter');
        $helper = $this->getHelper('question');
        $question =  new Question('Enter A Teams players:');
        $strTeamA = $helper->ask($input, $output, $question);
        $this->validateArgument($strTeamA, 'A');
        if($this->is_valid == false){
            $errorMessages = array('Error!', $this->error );
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            return Command::SUCCESS;
        }
        $helper = $this->getHelper('question');
        $question =  new Question('Enter B Teams players:');
        $strTeamB = $helper->ask($input, $output, $question);
        $this->validateArgument($strTeamB, 'B');
        if($this->is_valid == false){
            $errorMessages = array('Error!', $this->error );
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            return Command::SUCCESS;
        }
        if($this->is_valid ==true){
            foreach($this->team['A'] as $id => $playerStrength){
                if($this->team['B'][$id] > $playerStrength ){
                    $this->result = 'Loss';
                }
            }
            $output->writeln($this->result );
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       
        return Command::SUCCESS;
    }
    
    protected function validateArgument(string $argumentInput, string $team):void {
        $this->error = '';
        $this->is_valid = true;
        if( $argumentInput){
            if(\strpos($argumentInput, ',') === false ){
                $this->error = 'Player`s of '. $team.' must be comma separeted';
                $this->is_valid = false;
            }else{
                if(!is_numeric(trim(str_replace(',','',$argumentInput)))) {
                    $this->error = 'Values of '. $team.' must be integer';
                    $this->is_valid =  false;
                }else{
                    $arrTeam = \explode(',', trim($argumentInput));

                    if(count($arrTeam) != 5){
                        $this->error = 'Player`s of '. $team.' must be 5';
                        $this->is_valid =  false;
                    }else{
                        
                     $this->team[$team] = $arrTeam;
                     $this->is_valid =  true;
                    } 
                } 
            }
        }
    }
}
