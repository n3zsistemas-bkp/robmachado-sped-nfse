<?php

namespace NFePHP\NFSe\Models\Dsfnet\Factories;

use NFePHP\NFSe\Models\Dsfnet\Factories\Factory;
use NFePHP\NFSe\Models\Dsfnet\Factories\Header;

class ConsultarNFSeRps extends Factory
{
    public function render(
        $versao,
        $remetenteCNPJCPF,
        $codcidade,
        $transacao,
        $prestadorIM,
        $lote,
        $chavesNFSe = [],
        $chavesRPS = []
    ) {
        $method = 'ReqConsultaNFSeRPS';
        $content = $this->requestFirstPart($method);
        $content .= Header::render(
            $versao,
            $remetenteCNPJCPF,
            null,
            $transacao,
            $codcidade
        );
        $content .= "<Lote Id=\"lote:$lote\">";
        foreach ($chavesNFSe as $nota) {
            $content .= "<NotaConsulta>";
            $content .= "<Nota Id=\"nota:".$nota['numero']."\">";
            $content .= "<InscricaoMunicipalPrestador>$prestadorIM</InscricaoMunicipalPrestador>";
            $content .= "<NumeroNota>".$nota['numero']."</NumeroNota>";
            $content .= "<CodigoVerificacao>".$nota['codigo']."</CodigoVerificacao>";
            $content .= "</Nota>";
            $content .= "</NotaConsulta>";
        }
        foreach ($chavesRPS as $rps) {
            $content .= "<RPSConsulta>";
            $content .= "<RPS Id=\"rps:".$rps['numero']."\">";
            $content .= "<InscricaoMunicipalPrestador>$prestadorIM</InscricaoMunicipalPrestador>";
            $content .= "<NumeroRPS>".$rps['numero']."</NumeroRPS>";
            $content .= "<SeriePrestacao>".$rps['serie']."</SeriePrestacao>";
            $content .= "</RPS>";
            $content .= "</RPSConsulta>";
        }
        $content .= "</Lote>";
        $content .= "</ns1:$method>";
        $body = $this->oCertificate->signXML($content, 'Lote', 'Id', $this->signAlgorithm);
        $body = $this->clear($body);
        $this->validar($versao, $body, $method, '');
        return $body;
    }
}