# Prometheus Client em PHP

## Instalação

```sh
composer require aleteus/prometheus_client_php
```

## Como usar? 

```php
            
            $parameters = [
                'name1' => $var1,
                'name2' => $var2,
                'name3' => $var3
            ];
            
            $prometheus = new PrometheusClient(
                '*:9091', 
                'nome', 
                'da_metrica', 
                'produto', 
                $parameters, 
                'tipo da metrica (histogram, counter ou gauge)', 'nome_da_aplicação');  
               
                $prometheus->pushGateway($timeProcess, $parameters, $help);
```
# Explicando:

## Parameters ($parameters):

- O parameters armazena informações adicionais que são escolhidas de acordo com a necessidade da monitoria, e aparecem em forma de 'etiquetas' na interface do Pushgateway, as quais serão manipuladas na hora de montar os gráficos no Grafana. Este mantém o padrão 'name1' => $var1. A string 'name1' pra indicar o nome que indique o valor da váriável '$var1'.

## Prometheus ($prometheus):

- O cliente é criado a partir de sete argumentos: 
  - Endereço do Pushgateway,
  - Nome da métrica (separado por duas strings)
  - Nome do produto
  - O próprio $parameters
  - Tipo da métrica
  - Nome da aplicação

### Exemplo:

```
$prometheus = new PrometheusClient(
                '*:9091', 
                'nome', 
                'da_metrica', 
                'produto', 
                $parameters, 
                'tipo da metrica (histogram, counter ou gauge)', 'nome_da_aplicação');  
               
                $prometheus->pushGateway($timeProcess, $parameters, $help);
```

## Pushgateway

```
$prometheus->pushGateway($timeProcess, $parameters, $help);
```

Essa parte do código é a responsável por enviar todas as informações para o Prometheus e Pushgateway e ela possui três parâmetros obrigatórios: ($timeProcess, $parameters, $help). 

- O  ```$timeprocess``` precisa ter o valor de algum momento do processamento do código.
- O ```$parameters``` vai enviar o array que criamos no exemlo anterior.
- E o ```$help``` vai possuir alguma informação adicional que queira armazenar no Pushgateway.

#### Obs: Se algum desses parâmetros for nulo, o envio não irá funcionar! =)

### Guias de instalação do Prometheus e Pushgateway:

#### Prometheus: https://blog.ruanbekker.com/blog/2019/05/07/setup-prometheus-and-node-exporter-on-ubuntu-for-epic-monitoring/
#### Pushgateway: https://blog.ruanbekker.com/blog/2019/05/17/install-pushgateway-to-expose-metrics-to-prometheus/









