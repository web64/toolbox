<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:domains';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Domains from CloudFlare';

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
        $key     = new \Cloudflare\API\Auth\APIKey(env('CLOUDFLARE_EMAIL', ''), env('CLOUDFLARE_API_KEY', ''));
        $adapter = new \Cloudflare\API\Adapter\Guzzle($key);
        $user    = new \Cloudflare\API\Endpoints\User($adapter);
        $dns = new \Cloudflare\API\Endpoints\DNS($adapter);
        echo $user->getUserID();

        $zones = new \Cloudflare\API\Endpoints\Zones($adapter);
        $records_counter = 0;
        
        foreach ( $zones->listZones('','',1,50)->result as $zone ) 
        {
            // ('.$zone->plan->name.')
            echo PHP_EOL. $zone->name.' '.PHP_EOL;
            //dump( $zone );

            $_zone = \App\Zone::firstOrCreate(['name' => $zone->name]);

            foreach ($dns->listRecords( $zone->id )->result as $record)
            {
                if ($record->type == 'A' || $record->type == 'CNAME')
                {
                    if ( filter_var($record->content, FILTER_VALIDATE_IP) )
                    {
                        $domain = \App\Domain::firstOrCreate(
                            [
                                'name'      => $record->name,
                                'zone_id'   => $_zone->id
                            ]
                        );

                        $ip = \App\Ipaddress::firstOrCreate(['ip_address' => $record->content]);

                        $_record = \App\DnsRecord::firstOrCreate([
                            'type'          => $record->type,
                            'zone_id'       => $_zone->id,
                            'domain_id'     => $domain->id,
                            'ipaddress_id' => $ip->id,
                        ]);

                        $records_counter++;
                        echo " -- [{$record->type}] {$record->name} => {$record->content} \n";
                    }                   
                }
            }
        }

        echo "Zones: ". count($zones->listZones()->result) . PHP_EOL;
        echo "A and CNAME records: $records_counter \n";
    }


    private function getDns()
    {
        
    }
}
