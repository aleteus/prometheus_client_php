<?php
use Prometheus\PushGateway;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;

class PrometheusClient{
    public function __construct($gateway, $jobName, $label, $product, $groupings, $graphType, $appName){
        $this->gateway = new PushGateway($gateway); 
        $this->jobName = $jobName;
        $this->label = $label;
        $this->product = $product;
        $this->groupings = $groupings;    
        $this->graphType = $graphType;
        $this->appName = $appName;
    }
    function pushValue($value, $parameters, $help){
        $pushGateway = $this->gateway;
        $graph = $this->createGraph($this->graphType, $help);
        foreach($this->groupings as $key => $value){
            $parameters[$key] = $value;
        }
        switch ($this->graphType) {   
            case 'histogram':
                $graph->observe($value, [$this->product]);
                break;  
            case 'gauge':
                $graph->set($value, [$this->product]);
                break;
            case 'counter':
                $graph->incBy($value, [$this->product]); 
                break;
            default:
                throw new Exception('Metric not found !!', 2);
                break;
            }
            $pushGateway->pushAdd($this->registry, $this->jobName . '_' . $this->label, $parameters
        );
    }
    function pushGateway($start, $parameters, $help){
        $pushGateway = $this->gateway;
        $graph = $this->createGraph($this->graphType, $help);
        $end = date("Y-m-d H:i:s:v");
        $timeProcess = $end - $start;
        foreach($this->groupings as $key => $value){
            $parameters[$key] = $value;
        }
        switch ($this->graphType) {   
            case 'histogram':
                $graph->observe($timeProcess, [$this->product]);
                break;  
            case 'gauge':
                $graph->set($timeProcess, [$this->product]);
                break;
            case 'counter':
                $graph->incBy($timeProcess, [$this->product]); 
                break;
            default:
                throw new Exception('Metric not found !!', 2);
                break;
            }
            $pushGateway->pushAdd($this->registry, $this->jobName . '_' . $this->label, $parameters 
        );
    }
    
    public function createGraph($graphTyoe, $help){
        $this->adapter = new Prometheus\Storage\InMemory();
        $this->registry = new CollectorRegistry($this->adapter);
        switch ($graphTyoe) {
            case 'histogram':
                $graph = $this->registry->registerHistogram($this->jobName, $this->label, $help, ['product']);
                break;  
            case 'gauge':
                $graph = $this->registry->registerGauge($this->jobName, $this->label, $help, ['product']);
                break;
            case 'counter':
                $graph = $this->registry->registerCounter($this->jobName, $this->label, $help, ['product']); 
                break;  
            default:
                throw new Exception('Prometheus graph not found !!', 1);
                break;  
        }
        return $graph;
    }
}
