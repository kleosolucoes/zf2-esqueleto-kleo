﻿<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\View\Model\ViewModel;
use Application\Model\Entity\Responsavel;
use Application\Model\Entity\ResponsavelSituacao;
use Application\Model\Entity\Empresa;
use Application\Model\Entity\EmpresaSituacao;
use Application\Model\Entity\Shopping;
use Application\Model\ORM\RepositorioORM;
use Application\Form\CadastroResponsavelForm;
use Application\Form\CadastroEmpresaForm;
use Application\Form\CadastroShoppingForm;
use Application\Form\KleoForm;

/**
 * Nome: CadastroController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de crud
 */
class CadastroController extends KleoController {

  /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
  public function __construct(EntityManager $doctrineORMEntityManager = null) {

    if (!is_null($doctrineORMEntityManager)) {
      parent::__construct($doctrineORMEntityManager);
    }
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastro
     */
  public function indexAction() {
    return new ViewModel();
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavel
     */
  public function responsavelAction() {

    $formulario = $this->params()->fromRoute(self::stringFormulario);
    $mensagens = '';
    if($formulario){
      $cadastroResponsavelForm = $formulario;
    }else{
      $cadastroResponsavelForm = new CadastroResponsavelForm('cadastroResposavel');
    }

    return new ViewModel(
      array(
      self::stringFormulario => $cadastroResponsavelForm,
    )
    );
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavelFinalizar
     */
  public function responsavelFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $cadastrarEmpresaForm = new Responsavel();
        $cadastrarResponsavelForm = new CadastroResponsavelForm();
        $cadastrarResponsavelForm->setInputFilter($responsavel->getInputFilterCadastrarResponsavel());
        $cadastrarResponsavelForm->setData($post_data);

        /* validação */
        if ($cadastrarResponsavelForm->isValid()) {
          $validatedData = $cadastrarResponsavelForm->getData();
          $responsavel->exchangeArray($cadastrarResponsavelForm->getData());
          $responsavel->setTelefone($validatedData[KleoForm::inputDDD]
                                    . $validatedData[KleoForm::inputTelefone]);
          $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
          $repositorioORM->getResponsavelORM()->persistir($responsavel);

          $situacao = $repositorioORM->getSituacaoORM()->encontrarPorId(1);
          $responsavelSituacao = new ResponsavelSituacao();
          $responsavelSituacao->setResponsavel($responsavel);
          $responsavelSituacao->setSituacao($situacao);

          $repositorioORM->getResponsavelSituacaoORM()->persistir($responsavelSituacao);

          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'responsavelFinalizado',
          ));
        } else {

          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'responsavel',
            self::stringFormulario => $cadastrarResponsavelForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavelFinalizado
     */
  public function responsavelFinalizadoAction() {
    return new ViewModel();
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsaveis
     */
  public function responsaveisAction() {
    $this->setLayoutAdm();
    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $responsaveis = $repositorioORM->getResponsavelORM()->encontrarTodos();
    $situacoes = $repositorioORM->getSituacaoORM()->encontrarTodas();
    return new ViewModel(
      array(
      'responsaveis' => $responsaveis,
      'situacoes' => $situacoes,
    )
    );
  }

  /**
     * Seta o layout da administracao
     */
  private function setLayoutAdm() {
    $this->layout('layout/adm');
  }
  
}
