<?php

namespace Simplecast;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SimpleCast extends Command
{
    private $apiUrl = 'podcasts/_PODCAST_ID_/episodes.json';
    private $client;
    private $progressBar;

    public function __construct(Client $client)
    {
        parent::__construct('download');
        $this->client = $client;
    }

    protected function configure()
    {
        $this->setDescription('Download your podcast files from your SimpleCast account.')
            ->addOption('key', null, InputOption::VALUE_REQUIRED, 'The API key')
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'The podcast id');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if($input->getOption('key') === null | $input->getOption('id') === null) {
            $output->writeln('<error>You should provide both options Key and Id</error>');
            exit;
        }

        $response = $this->getResponse($input, $output);

        $meta = collect(\json_decode($response));
        $this->initProgressBar($meta->count(), $output);

        $fileSystem = new Files();

        $meta->reverse()
            ->each(function ($item) use ($fileSystem) {
                $fileSystem
                ->createJsonFile($item)
                ->downloadFile($item, $this->client, $this->progressBar);

                $this->progressBar->advance();
            });

        $output->writeln('<info>And we are done, we have downloaded your ' . $meta->count() . ' podcasts.</info>');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->apiUrl = \str_replace('_PODCAST_ID_', $input->getOption('id'), $this->apiUrl);
    }

    protected function initProgressBar($count, OutputInterface $output): void
    {
        // Progressbar
        $this->progressBar = new ProgressBar($output, $count);
        $this->progressBar->setFormat("%status%\n%current%/%max%  [%bar%] %percent:3s%%\n");
        $this->progressBar->start();
    }

    protected function getResponse(InputInterface $input, OutputInterface $output): \Psr\Http\Message\StreamInterface
    {
        try {
            return $this->client->get($this->apiUrl, [
                'headers' => ['X-API-KEY' => $input->getOption('key')],
            ])->getBody();
        } catch (ClientException $clientException) {
            $output->writeln("<error>{$clientException->getMessage()}</error>");
            exit;
        } catch (\Exception $exception) {
            $output->writeln("<error>{$exception->getMessage()}</error>");
            exit;
        }
    }
}
