<?php

namespace Statamic\Addons\Importer\Commands;

use Statamic\API\File;
use Statamic\API\YAML;
use Statamic\API\Entry;
use Statamic\API\Config;
use Statamic\API\Content;
use Statamic\API\Collection;
use Illuminate\Console\Command;
use Statamic\Addons\importer\OldArticle;

class ImporterImportAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importer:import_all
                            {collection? : Name of the collection to generate entries in. If left blank you will be asked.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all database entries from a table.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $collections = Collection::handles();

        $collection = $this->choice('In which collection would you like them?', $collections);

        $this->importContent($collection);

        $this->info("Your content has been imported.");
    }

    /**
     * Import the Content
     *
     * @param string $collection_name
     */
    public function importContent($collection_name)
    {
        $old_articles = OldArticle::get();

        $count = $old_articles->count();

        $this->info("Importing " . $count . " entries." );

        $this->output->progressStart($count);

        // Disable search auto indexing to prevent overhead especially if using an API-based driver like Algolia.
        Config::set('search.auto_index', false);

        foreach ($old_articles as $old_article) {

            $entry = Entry::create($old_article->slug)
                ->collection($collection_name)
                ->with([
                    'title' => $old_article->title,
                    'body' => $old_article->body,
                    'meta_description' => $old_article->meta_description,
                    'display' => 'hidden',
                    'publish_on' => $old_article->updated_at,
                    'views' => $old_article->views
                ])
                ->published(false)
                ->date($old_article->created_at)
                ->get();

            $entry->ensureId();
            $entry->save();

            $this->output->progressAdvance();

        }

        $this->output->progressFinish();
    }
}