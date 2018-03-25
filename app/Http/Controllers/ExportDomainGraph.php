<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GraphAware\Neo4j\Client\ClientBuilder;

class ExportDomainGraph extends Controller
{
	protected $neo4j;

    public function __construct()
    {
        $this->neo4j =ClientBuilder::create()
            ->addConnection('default', 'http://neo4j:password@localhost:7474') // Example for HTTP connection configuration (port is optional)
            ->addConnection('bolt', 'bolt://neo4j:password@localhost:7687') // Example for BOLT connection configuration (port is optional)
            ->build();
    }

    public function index()
    {
       $zones = \App\Zone::all();
       foreach($zones as $zone)
       {
            
            foreach( $zone->records as $record)
            {
                //dump($record->domain);
                //dump($record->ipaddress);
                //dd($record);
                $cql =  "MERGE (zone:Zone { name: '{$zone->name}' })
ON CREATE SET
    zone.zone_id={$zone->id}

MERGE (domain:Domain { name: '{$record->domain->name}' })
ON CREATE SET
    domain.domain_id={$record->domain->id}

MERGE (ip:IP { ip_address: '{$record->ipaddress->ip_address}' })
ON CREATE SET
    ip.name='{$record->ipaddress->name}'

CREATE UNIQUE (zone)-[:HAS]->(domain)
CREATE UNIQUE (domain)-[:{$record->type}]->(ip);

";
                //die( $cql );
                $this->neo4j->run( $cql );

            }

            
       }
    }
}
