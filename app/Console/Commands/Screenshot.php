<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Spatie\Browsershot\Browsershot;

use Illuminate\Support\Facades\Storage;

class Screenshot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'screenshot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take Screenshots -> Save to S3';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        $sql = " 
            fetched_at IS NULL OR 
            fetched_at < ( CURRENT_TIMESTAMP() - (interval_value * interval_type) ) ";
        
        $pages = \App\Screenshot::whereRaw($sql)->get();

        if ( !empty($pages) )
        {
            foreach( $pages as $page )
            {
                echo "Taking screenshot: {$page->url} \n";
                $this->takePageShot( $page );
            }
        }
    }

    public function takePageShot( $page )
    {
        $image = Browsershot::url( $page->url )
            ->windowSize( $page->width, $page->height )
            ->fullPage()
            ->timeout(120)
            ->setDelay( $page->delay )
            ->optimize()
            ->screenshot();
        
        Storage::disk('s3')->put( $page->newImagePath() , $image);

        $page->shot_counter++;
        $page->fetched_at = \Carbon\Carbon::now();

        $page->save();
    }
}
