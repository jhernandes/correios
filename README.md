# Biblioteca para Calculo de Frete dos Correios (Brasil)

**Índice**

- [Dependências](#dependências)
- [Instalação](#instalação)
- [Como Utilizar](#como-utilizar)
- [Testes](#testes)
- [Licença](#licença)
- [Dúvidas & Sugestões](#duvidas-&-sugestões)

## Dependências

**require**
 - [PHP >= 5.4]

**require-dev**
 - [phpunit/phpunit]

## Instalação

Execute em seu shell:

    composer require jhernandes/correios

## Como Utilizar

```php

require_once 'vendor/autoload.php';

use Correios\Correios;

// CEP deve ter 8 dígitos com mascara ou sem
$cepOrigem = '01156-060';
$cepDestino = '88034-685';

// Defina o Peso em Quilos (kg) (float)
$peso = 0.50;

// Altura, Largura e Comprimento em centimetros (cm) (integer)
$altura = 5;
$largura = 12;
$comprimento = 8;

$servicos = array(
    Correios::SEDEX,
    Correios::PAC,
);

$correios = new Correios(
    $servicos,
    $cepOrigem,
    $cepDestino,
    $peso,
    $altura,
    $largura,
    $comprimento
);

$fretes = $correios->calculaFretes();

foreach($fretes as $frete) {
    print_r($frete);
}
```

## Testes

É necessário a instalação do PHPUnit para a realização dos testes.

## Licença
[The MIT License](https://github.com/jhernandes/correios/blob/master/LICENSE)

## Dúvidas & Sugestões

Em caso de dúvida ou sugestão para a Lib abra uma nova [Issue](https://github.com/jhernandes/correios/issues).
