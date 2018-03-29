<?php

namespace Correios;

class Correios
{
    const PAC = '04510';
    const PAC_A_COBRAR = '04707';
    const SEDEX = '04014';
    const SEDEX_A_COBRAR = '04065';
    const SEDEX_10 = '40215';
    const SEDEX_12 = '40169';
    const SEDEX_HOJE = '40290';

    /**
     * @var string
     */
    private $codServico;

    /**
     * @var string
     */
    private $cepOrigem;

    /**
     * @var string
     */
    private $cepDestino;

    /**
     * @var float
     */
    private $peso;

    /**
     * @var int
     */
    private $altura;

    /**
     * @var int
     */
    private $largura;

    /**
     * @var int
     */
    private $comprimento;

    /**
     * @var float
     */
    private $valorDeclarado;

    /**
     * @param type $cod_servico - codigo do servico desejado
     * @param type $cep_origem - cep de origem, apenas numeros
     * @param type $cep_destino - cep de destino, apenas numeros
     * @param type $peso - valor dado em Kg incluindo a embalagem. 0.1, 0.3, 1, 2 ,3 , 4
     * @param type $altura - altura do produto em cm incluindo a embalagem
     * @param type $largura - altura do produto em cm incluindo a embalagem
     * @param type $comprimento - comprimento do produto incluindo embalagem em cm
     * @param type $valor_declarado - indicar 0 caso nao queira o valor declarado
     * @return string|boolean
     */
    public function __construct(
        $cod_servico,
        $cep_origem,
        $cep_destino,
        $peso,
        $altura,
        $largura,
        $comprimento,
        $valor_declarado = '0'
    ) {
        if (is_array($cod_servico)) {
            $this->setCodServico(implode(',', $cod_servico));
        } else {
            $this->setCodServico($cod_servico);
        }

        $this->setCepOrigem($cep_origem);
        $this->setCepDestino($cep_destino);
        $this->setPeso($peso);
        $this->setAltura($altura);
        $this->setLargura($largura);
        $this->setComprimento($comprimento);
        $this->setValorDeclarado($valor_declarado);

        return $this;
    }

    public function calculaFretes()
    {
        $cod_servico = $this->getCorreiosCode($this->getCodServico());

        $correiosResponse = $this->request($cod_servico);

        $fretes = array();
        if (is_array($correiosResponse->cServico)) {
            foreach ($correiosResponse->cServico as $cServico) {
                if ($cServico->Erro == '0') {
                    $fretes[] = array(
                        'servico' => $this->getServicoByCorreiosCode($cServico->Codigo),
                        'codigo'  => $cServico->Codigo,
                        'valor'   => $this->strToNumber($cServico->Valor),
                        'prazo'   => $cServico->PrazoEntrega.' Dias',
                    );
                }
            }
        } else {
            $cServico = $correiosResponse->cServico;
            if ($cServico->Erro == '0') {
                $fretes[] = array(
                    'servico' => $this->getServicoByCorreiosCode($cServico->Codigo),
                    'codigo'  => $cServico->Codigo,
                    'valor'   => $this->strToNumber($cServico->Valor),
                    'prazo'   => $cServico->PrazoEntrega.' Dias',
                );
            }
        }

        return $fretes;
    }

    private function getServicoByCorreiosCode($code)
    {
        $servico = '';
        switch ($code) {
            case self::SEDEX:
                $servico = 'Sedex';
                break;
            case self::SEDEX_A_COBRAR:
                $servico = 'Sedex a cobrar';
                break;
            case self::PAC:
                $servico = 'PAC';
                break;
            case self::PAC_A_COBRAR:
                $servico = 'PAC a cobrar';
                break;
            case self::SEDEX_12:
                $servico = 'Sedex 12';
                break;
            case self::SEDEX_10:
                $servico = 'Sedex 10';
                break;
            case self::SEDEX_HOJE:
                $servico = 'Sedex Hoje Varejo';
                break;
        }

        return $servico;
    }

    private function getCorreiosCode($cod_servico)
    {
        $cod_servico = strtoupper($cod_servico);
        if ($cod_servico == 'SEDEX10') {
            return self::SEDEX_10;
        }

        if ($cod_servico == 'SEDEXACOBRAR') {
            return self::SEDEX_A_COBRAR;
        }

        if ($cod_servico == 'SEDEX') {
            return self::SEDEX;
        }

        if ($cod_servico == 'PAC') {
            return self::PAC;
        }

        return $cod_servico;
    }

    private function request($cod_servico)
    {
        $correios = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$this->getCepOrigem().
        "&sCepDestino=".$this->getCepDestino().
        "&nVlPeso=".$this->getPeso().
        "&nCdFormato=1&nVlComprimento=".$this->getComprimento().
        "&nVlAltura=".$this->getAltura().
        "&nVlLargura=".$this->getLargura().
        "&sCdMaoPropria=n&nVlValorDeclarado=".$this->getValorDeclarado().
            "&sCdAvisoRecebimento=n&nCdServico=".$cod_servico.
            "&nVlDiametro=0&StrRetorno=xml";

        if (!empty($correios)) {
            try {
                return $this->xmlToStdClass($this->validate($correios));
            } catch (\Exception $e) {
                return null;
            }
        }
    }

    private function validate($file)
    {
        libxml_use_internal_errors(true);
        $response = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($response === false) {
            throw new \Exception('Não foi possível identificar o XML de retorno.');
        }

        return $response;
    }

    private function xmlToStdClass($xml)
    {
        return json_decode(json_encode((array) $xml));
    }

    private function strToNumber($number)
    {
        $number = (float) str_replace(',', '.', $number);

        return number_format($number, 2, '.', '');
    }

    /**
     * @return string
     */
    public function getCodServico()
    {
        return $this->codServico;
    }

    /**
     * @param string $codServico
     *
     * @return self
     */
    public function setCodServico($codServico)
    {
        $this->codServico = $codServico;

        return $this;
    }

    /**
     * @return string
     */
    public function getCepOrigem()
    {
        return $this->cepOrigem;
    }

    /**
     * @param string $cepOrigem
     *
     * @return self
     */
    public function setCepOrigem($cepOrigem)
    {
        $this->cepOrigem = preg_replace('/\D/', '', $cepOrigem);

        return $this;
    }

    /**
     * @return string
     */
    public function getCepDestino()
    {
        return $this->cepDestino;
    }

    /**
     * @param string $cepDestino
     *
     * @return self
     */
    public function setCepDestino($cepDestino)
    {
        $this->cepDestino = preg_replace('/\D/', '', $cepDestino);

        return $this;
    }

    /**
     * @return float
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * @param float $peso
     *
     * @return self
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * @return int
     */
    public function getAltura()
    {
        return $this->altura;
    }

    /**
     * @param int $altura
     *
     * @return self
     */
    public function setAltura($altura)
    {
        if ((int) $altura < 2) {
            $altura = 2;
        }

        $this->altura = (int) $altura;

        return $this;
    }

    /**
     * @return int
     */
    public function getLargura()
    {
        return $this->largura;
    }

    /**
     * @param int $largura
     *
     * @return self
     */
    public function setLargura($largura)
    {
        if ((int) $largura < 11) {
            $largura = 11;
        }

        $this->largura = (int) $largura;

        return $this;
    }

    /**
     * @return int
     */
    public function getComprimento()
    {
        return $this->comprimento;
    }

    /**
     * @param int $comprimento
     *
     * @return self
     */
    public function setComprimento($comprimento)
    {
        if ((int) $comprimento < 16) {
            $comprimento = 16;
        }

        $this->comprimento = $comprimento;

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
        $this->valorDeclarado = $valorDeclarado;

        return $this;
    }
}
