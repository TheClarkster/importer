<?php

namespace Statamic\Addons\importer;

use Illuminate\Database\Eloquent\Model;

class OldArticle extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';
}