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

class ImporterImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importer:import
                            {collection? : Name of the collection to generate entries in. If left blank you will be asked.}
                            {id? : Name of the collection to generate entries in. If left blank you will be asked.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a database entry.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = ($this->argument('id'))
            ? $this->argument('id')
            : $this->ask('What id should we import?');

        $collections = Collection::handles();

        $collection = $this->choice('In which collection would you like them?', $collections);

        $this->importContent($id, $collection);

        $this->info("Your content has been imported.");
    }

    /**
     * Import the Content
     *
     * @param int $id
     * @param string $collection_name
     */
    public function importContent($id, $collection_name)
    {
        $old_article = OldArticle::find($id);

        // Disable search auto indexing to prevent overhead especially if using an API-based driver like Algolia.
        Config::set('search.auto_index', false);

        $entry = Entry::create($old_article->slug)
            ->collection($collection_name)
            ->with(['title' => $old_article->title, 'body' => $old_article->body])
            ->published(false)
            ->date($old_article->created_at)
            ->get();

        $entry->ensureId();
        $entry->save();
    }
}