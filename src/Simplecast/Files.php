<?php

namespace Simplecast;

use GuzzleHttp\Client;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;
use Symfony\Component\Console\Helper\ProgressBar;

class Files
{
    private $backupDirectory;
    private $fileSystem;

    public function __construct()
    {
        $this->backupDirectory = \getenv('BACKUP_DIRECTORY');

        $this->fileSystem = new Filesystem(
            new Adapter($this->backupDirectory)
        );
    }

    public function createJsonFile($meta)
    {
        $this->fileSystem->write(
            "Season-{$meta->season}" . DIRECTORY_SEPARATOR . $meta->number . DIRECTORY_SEPARATOR . "{$meta->id}.json",
            \json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        return $this;
    }

    public function downloadFile($meta, Client $client, ProgressBar $progressBar): void
    {
        $this->hasDirectory($meta);

        $baseDir = \sprintf(
            '%s' . DIRECTORY_SEPARATOR . 'Season-%s' . DIRECTORY_SEPARATOR . '%s',
            $this->backupDirectory,
            $meta->season,
            $meta->number
        );

        $audioSink = $baseDir . DIRECTORY_SEPARATOR . \basename($meta->audio_url);
        $artworkSink = $baseDir . DIRECTORY_SEPARATOR . \basename($meta->images->large);

        $this->download($meta, $audioSink, $client, $progressBar);
        $this->download($meta, $artworkSink, $client, $progressBar, true);
    }

    protected function download($meta, $sink, Client $client, ProgressBar $progressBar, $isImage = false): void
    {
        $file = $isImage ? $meta->images->large : $meta->audio_url;
        $type = $isImage ? 'artwork' : 'audio';

        $client->get($file, [
            'sink' => $sink,
            'progress' => function ($dl_total_size, $dl_size_so_far, $ul_total_size, $ul_size_so_far) use ($progressBar, $meta, $type) {
                $total = \ByteUnits\bytes($dl_total_size)->format('MB');
                $sofar = \ByteUnits\bytes($dl_size_so_far)->format('MB');
                $percentage = '0.00' != $dl_total_size ? \number_format($dl_size_so_far * 100 / $dl_total_size) : 0;
                $progressBar->setMessage("Downloading {$meta->title} {$type} file, {$sofar}/{$total} ({$percentage}%)", 'status');
                $progressBar->display();
            },
        ]);
    }

    protected function hasDirectory($meta): void
    {
        if (! $this->fileSystem->has("Season-{$meta->season}" . DIRECTORY_SEPARATOR . $meta->number)) {
            $this->fileSystem->createDir("season-{$meta->season}" . DIRECTORY_SEPARATOR . $meta->number);
        }
    }
}
