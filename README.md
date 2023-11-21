# Biblioteca de ferramentas para análise de desempenho.

Biblioteca criada, para análises de desempenho de aplicação com o cálculo em segundos dos passos.  

## Casos de uso
### Análise do tempo de execução e performance do código  
1. Iniciando o contador de tempo:  
    Use o método estático ```PerformanceTracker::create()```, instanciado na variável que deseja manipular, aqui chamei de ```$performanceTracker```.
2. Adicione pontos de análise:  
    Através do método ```addMarkTracker(string $mensagem)```, descrevendo a mensagem o mais objetiva, quando maior o tamanho da mensagem, mais recurso será consumido.
3. Capturando os eventos:  
    Os eventos podem ser retornado através dos métodos ```getEventsTrackerToJson()``` e ```getEventsTracker()```;  
    O primeiro retorna como o próprio nome já diz em json, o segundo em serialize do php, sendo expresso abaixo, parte dos exemplo de cada método.
4. Capturando o total:  
    O total do tempo de processamento, pode ser retornado pelo método ```calc()``` ou ```getTotals()```.
    Para apresentação em tela é sugerido o ```getTotals()```, uma vez que ele já apresenta uma string formatada.
    Por sua vez, o método ```calc()```, retorna apenas o número formatado no padrão pt-br para por exemplo, salvar em logs e banco de dados (não recomendado).
```php
<?php

use Bahge\AnalisysTools\Domain\PerformanceTracker;

require '../vendor/autoload.php';

$performanceTracker = PerformanceTracker::create();

for ($i=0; $i < (2 ** 6); $i++) { 
    sleep(rand(0,1)); // Apenas como critério de teste, para gerar randomicamente uma pausa
    $performanceTracker->addMarkTracker("Iteração: $i");
}

echo $performanceTracker->getEventsTracker() . PHP_EOL;

echo $performanceTracker->getEventsTrackerToJson() . PHP_EOL;

echo $performanceTracker->getTotals() . PHP_EOL;

echo $performanceTracker->calc() . PHP_EOL;
```

> Nos testes, gerando 64 passos e imprimindo todos os resultados com as funções ```getEventsTracker(), getEventsTrackerToJson()``` os resultados foram:   
> ```bash
> Impressão dos eventos serializados: 
>  
> i:4;:a:3:{s:4:"time";d:1700533033.571543;s:3:"msg";s:18:"Inicio dos eventos";s:15:"processing_time";i:0;}:a:3:{s:4:"time";d:1700533033.571992;s:3:"msg";s:13:"Iteração: 0";s:15:"processing_time";s:10:"0.00044894";}:a:3:{s:4:"time";d:1700533033.572084;s:3:"msg";s:13:"Iteração: 1";s:15:"processing_time";s:10:"0.00009203";} ...
>  
> Impressãos dos eventos em json: 
>  
> [{"msg":"Inicio dos eventos","processing_time":0},{"msg":"Iteração: 0","processing_time":"0.00044894"},{"msg":"Iteração: 1","processing_time":"0.00009203"},{"msg":"Iteração: 2","processing_time":"0.00007010"},...]
> 
> Tempo de execução em laço for pelo método getTotals():  
> Processado em: 30.0108 segundos.  
>
> Tempo de execução em laço for pelo método calc():  
> 30,0108 
>
> Consumo de memória: 
> 48,06 Kb
> ```
> **Com 30 iterações tendo 1 segundo como sleep**, gerado randomicamente. **Use com sabedoria**  


### Análise do uso de memória pela aplicação
1. Iniciando o contador de memória:  
    Use o método estático ```MemoryUse::create()```, instanciado na variável que deseja manipular, aqui chamei de ```$memoryUse```.
2. Capturando o total:  
    O total da memória usada, pode ser retornado pelo método ```calc()```, ```calcKb(int <precisão>)``` ou ```calcMb(int <precisão>)```.  
    Sendo retornam o valor de bytes, KiloBytes e MegaBytes respectivamente, sendo indicado a primeira para salvamento e as outras para exibição em tela, com a precisão (zero após a vírgula), alteradas, conforme necessário por parâmetro.
```php
<?php

use Bahge\AnalisysTools\Domain\MemoryUse;

require '../vendor/autoload.php';

$memoryUse = MemoryUse::create();

$array = [];
// 1024 iterações 
for ($i=0; $i < (2 ** 10); $i++) { 
    array_push($array, $i);
}


echo implode(",", $array) . PHP_EOL;
echo $memoryUse->calcKb() . PHP_EOL;
```

> Exemplo de retorno:  
> 0,1,2,3,4,5 ... 1023  
>
> Memória consumida calcKb():  
> 36,12 Kb
>
> Tempo de execução:
> Processado em: 0.0009 segundos
