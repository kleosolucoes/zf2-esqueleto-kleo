<?php

namespace Application\Form;

use Zend\Form\Element\Hidden;
use Zend\Form\Element\Csrf;
use Zend\Form\Form;

/**
 * Nome: KleoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario base  
 *              
 */
class KleoForm extends Form {

    const inputNome = 'inputNome';
    const inputDDD = 'inputDDD';
    const inputTelefone = 'inputTelefone';
    const inputEmail = 'inputEmail';
    const inputCPF = 'inputCPF';
    const inputDia = 'inputDia';
    const inputMes = 'inputMes';
    const inputAno = 'inputAno';
    const inputUploadCPF = 'inputUploadCPF';
    
    const inputNomeFantasia = 'inputNomeFantasia';
    const inputRazaoSocial = 'inputRazaoSocial';
    const inputUploadContratoSocial = 'inputUploadContratoSocial';
    const inputCNPJ = 'inputCNPJ';
    const inputDDDEmpresa = 'inputDDDEmpresa';
    const inputTelefoneEmpresa = 'inputTelefoneEmpresa';
    const inputEmailEmpresa = 'inputEmailEmpresa';
    const inputNumeroLojas = 'inputNumeroLojas';
  
    const inputId = 'inputId';
    const inputCSRF = 'inputCSRF';
    const inputSituacao = 'inputSituacao';

    const stringClass = 'class';
    const stringClassFormControl = 'form-control';
    const stringId = 'id';
    const stringPlaceholder = 'placeholder';
    const stringAction = 'action';
    const stringRequired = 'required';
    const stringValue = 'value';

    const traducaoNome = 'Nome';
    const traducaoDDD = 'DDD';
    const traducaoTelefone = 'Telefone';
    const traducaoEmail = 'Email';
    const traducaoDia = 'Dia';
    const traducaoMes = 'Mês';
    const traducaoAno = 'Ano';
    const traducaoCPF = 'CPF';
    const traducaoUploadCPF = 'Suba um arquivo com o CPF';
  
    const traducaoNomeFantasia = 'Nome Fantasia';
    const traducaoRazaoSocial = 'Razão Social';
    const traducaoCNPJ = 'CNPJ';
    const traducaoDDDEmpresa = 'DDD';
    const traducaoTelefoneEmpresa = 'Telefone Empresa';
    const traducaoEmailEmpresa = 'Email Empresa';
    const traducaoNumeroLojas = 'Número de Lojas';
    const traducaoUploadContratoSocial = 'Suba um arquivo com o contrato social';
    
  
    const traducaoSituacao = 'Situação';
    
    public function __construct($name = null) {
    
      parent::__construct($name);
        $this->setAttributes(array(
            'method' => 'post',
        ));
      
        $this->add(
                (new Hidden())
                        ->setName('inputId')
                        ->setAttributes([
                            'id' => 'inputId',
                        ])
        );
      
        $this->add(
                (new Csrf())
                        ->setName('inputCSRF')
        );


    }

}