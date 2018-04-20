<?php

namespace Correios;

class Frete
{
    /**
     * @var string
     */
    private $servico;

    /**
     * @var string
     */
    private $codigo;

    /**
     * @var float
     */
    private $valor;

    /**
     * @var int
     */
    private $prazo;

    /**
     * @var float
     */
    private $valorSemAdicionais;

    /**
     * @var float
     */
    private $valorMaoPropria;

    /**
     * @var float
     */
    private $valorAvisoRecebimento;

    /**
     * @var float
     */
    private $valorDeclarado;

    /**
     * @var string
     */
    private $entregaDomiciliar;

    /**
     * @var string
     */
    private $entregaSabado;

    /**
     * @var string
     */
    private $erro;

    /**
     * @var string
     */
    private $mensagemErro;

    /**
     * @var string
     */
    private $observacao;

    /**
     * @return string
     */
    public function getServico()
    {
        return $this->servico;
    }

    /**
     * @param string $servico
     *
     * @return self
     */
    public function setServico($servico)
    {
        $this->servico = $servico;

        return $this;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param string $codigo
     *
     * @return self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     *
     * @return self
     */
    public function setValor($valor)
    {
        $this->valor = $this->strToNumber($valor);

        return $this;
    }

    /**
     * @return int
     */
    public function getPrazo()
    {
        return $this->prazo;
    }

    /**
     * @param int $prazo
     *
     * @return self
     */
    public function setPrazo($prazo)
    {
        $this->prazo = $prazo;

        return $this;
    }

    /**
     * @return float
     */
    public function getValorSemAdicionais()
    {
        return $this->valorSemAdicionais;
    }

    /**
     * @param float $valorSemAdicionais
     *
     * @return self
     */
    public function setValorSemAdicionais($valorSemAdicionais)
    {
        $this->valorSemAdicionais = $this->strToNumber($valorSemAdicionais);

        return $this;
    }

    /**
     * @return float
     */
    public function getValorMaoPropria()
    {
        return $this->valorMaoPropria;
    }

    /**
     * @param float $valorMaoPropria
     *
     * @return self
     */
    public function setValorMaoPropria($valorMaoPropria)
    {
        $this->valorMaoPropria = $this->strToNumber($valorMaoPropria);

        return $this;
    }

    /**
     * @return float
     */
    public function getValorAvisoRecebimento()
    {
        return $this->valorAvisoRecebimento;
    }

    /**
     * @param float $valorAvisoRecebimento
     *
     * @return self
     */
    public function setValorAvisoRecebimento($valorAvisoRecebimento)
    {
        $this->valorAvisoRecebimento = $this->strToNumber($valorAvisoRecebimento);

        return $this;
    }

    /**
     * @return float
     */
    public function getValorDeclarado()
    {
        return $this->valorDeclarado;
    }

    /**
     * @param float $valorDeclarado
     *
     * @return self
     */
    public function setValorDeclarado($valorDeclarado)
    {
        $this->valorDeclarado = $this->strToNumber($valorDeclarado);

        return $this;
    }

    /**
     * @return string
     */
    public function getEntregaDomiciliar()
    {
        return $this->entregaDomiciliar;
    }

    /**
     * @param string $entregaDomiciliar
     *
     * @return self
     */
    public function setEntregaDomiciliar($entregaDomiciliar)
    {
        $this->entregaDomiciliar = $entregaDomiciliar;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntregaSabado()
    {
        return $this->entregaSabado;
    }

    /**
     * @param string $entregaSabado
     *
     * @return self
     */
    public function setEntregaSabado($entregaSabado)
    {
        $this->entregaSabado = $entregaSabado;

        return $this;
    }

    /**
     * @return string
     */
    public function getErro()
    {
        return $this->erro;
    }

    /**
     * @param string $erro
     *
     * @return self
     */
    public function setErro($erro)
    {
        $this->erro = $erro;

        return $this;
    }

    /**
     * @return string
     */
    public function getMsgErro()
    {
        return $this->mensagemErro;
    }

    /**
     * @param string $mensagemErro
     *
     * @return self
     */
    public function setMsgErro($mensagemErro)
    {
        $this->mensagemErro = $mensagemErro;

        return $this;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     *
     * @return self
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;

        return $this;
    }

    private function strToNumber($number)
    {
        $number = (float) str_replace(',', '.', $number);

        return number_format($number, 2, '.', '');
    }
}
