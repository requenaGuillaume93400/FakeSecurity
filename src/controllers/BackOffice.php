<?php

namespace Controllers;

require_once 'src/autoload.php';
require_once 'src/tables.php';

class BackOffice
{

    private $client;
    private $agent;
    private $order;
    private $sanction;

    protected const T_CLIENTS = CLIENTS;
    protected const T_AGENTS = AGENTS;
    protected const T_AGENTS_CLIENTS = AGENTS_CLIENTS;
    protected const T_ORDERS_DETAILS = ORDERS_DETAILS;
    protected const T_SANCTIONS = SANCTIONS;
    protected const T_SANCTIONS_AGENTS = SANCTIONS_AGENTS;

    public function __construct(\Models\Client $client, \Models\Agent $agent, \Models\Order $order, \Models\Sanction $sanction) 
    {
        $this->client = $client;
        $this->agent = $agent;
        $this->order = $order;
        $this->sanction = $sanction;
    }
    

    public function run(): void
    {
        session_start();

        // Initialise $id & $statut session
        $user = \Session::initSession();
        $id = $user['id'];
        $statut = $user['statut'];

        // Verify if you have admin rights, if not -> disconnect you and show a message
        \Access::admin();

        // Get all data to show them in backoffice
        $clients = $this->client->getAll();
        $agents = $this->agent->getAll();
        $orders = $this->order->getAll();
        $sanctions = $this->sanction->getAll();

        // Backoffice operations
        if(isset($_GET['statut']) && !empty($_GET['statut'])){

            if($_GET['statut'] === 'client'){
                if(isset($_GET['delete']) && !empty($_GET['delete'])){
                    $this->client->delete(intval($_GET['delete']), self::T_CLIENTS);
                }
                if(isset($_GET['confirm']) && !empty($_GET['confirm'])){
                    $this->client->confirm(intval($_GET['confirm']), self::T_CLIENTS);
                }
            }

            if($_GET['statut'] === 'agent'){
                if(isset($_GET['delete']) && !empty($_GET['delete'])){
                    $this->agent->delete(intval($_GET['delete']), self::T_AGENTS);
                }
                if(isset($_GET['confirm']) && !empty($_GET['confirm'])){
                    $this->agent->confirm(intval($_GET['confirm']), self::T_AGENTS);
                }
                if(isset($_GET['upgrade']) && !empty($_GET['upgrade'])){
                    $this->agent->setAdmin(intval($_GET['upgrade']));
                }
            }

            if($_GET['statut'] === 'sanction'){
                if(isset($_GET['delete']) && !empty($_GET['delete'])){
                    $this->sanction->delete(intval($_GET['delete']), self::T_SANCTIONS_AGENTS);
                }
            }

            if($_GET['statut'] === 'order'){
                if(isset($_GET['delete']) && !empty($_GET['delete'])){
                    $this->order->delete(intval($_GET['delete']), self::T_ORDERS_DETAILS);
                }
            }

        }

        $pageTitle = 'Administration';

        \Renderer::render('backOffice', compact('id', 'statut', 'pageTitle', 'clients', 'agents', 'orders', 'sanctions'));
    }


}