<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\PostApiService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:save-posts-from-api',
    description: 'Download posts from api and save to database',
)]
class SavePostsFromApiCommand extends Command
{
    private PostApiService $postApiService;

    /**
     * PostService $postService
     */
    public function __construct(PostApiService $postApiService)
    {
        parent::__construct();
        $this->postApiService = $postApiService;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Downloading posts from api');

        try {
            $this->postApiService->importPostsFromApi();
            $io->success('Successfully downloaded posts from api and saved to database');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('An error occurred: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
