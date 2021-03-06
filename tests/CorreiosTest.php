<?php

namespace Tests;

use Correios\Correios;
use PHPUnit\Framework\TestCase;

class CorreiosTest extends TestCase
{
    public function testGetFretesSuccessfully()
    {
        $cepOrigem = '01156-060';
        $cepDestino = '88034-685';
        $peso = 0.50;
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

        $this->assertEquals(2, count($fretes));
    }

    public function testGetFreteSuccessfully()
    {
        $cepOrigem = '01156-060';
        $cepDestino = '88034-685';
        $peso = 1.78;
        $altura = 5;
        $largura = 12;
        $comprimento = 18;

        $correios = new Correios(
            Correios::PAC,
            $cepOrigem,
            $cepDestino,
            $peso,
            $altura,
            $largura,
            $comprimento
        );

        $fretes = $correios->calculaFretes();

        $this->assertEquals(1, count($fretes));
        $this->assertEquals('PAC', $fretes[0]->getServico());
    }

    public function testGetEmptyFretes()
    {
        $cepOrigem = '';
        $cepDestino = '';
        $peso = 1.78;
        $altura = 5;
        $largura = 12;
        $comprimento = 18;

        $correios = new Correios(
            Correios::PAC,
            $cepOrigem,
            $cepDestino,
            $peso,
            $altura,
            $largura,
            $comprimento
        );

        $fretes = $correios->calculaFretes();

        $this->assertEmpty($fretes);
    }

    public function testGetErrosFromCorreios()
    {
        $cepOrigem = '19053210';
        $cepDestino = '02881050';
        $peso = 0.50;
        $altura = 5;
        $largura = 12;
        $comprimento = 62;

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

        $this->assertEquals(2, count($fretes));
        $this->assertEquals('Sedex', $fretes[0]->getServico());
        $this->assertEquals('011', $fretes[0]->getErro());
        $this->assertEquals('011', $fretes[1]->getErro());
    }
}
