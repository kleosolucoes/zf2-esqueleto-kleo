<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use PHPMailer;
use Zend\Session\Container;
use Zend\Json\Json;
use Zend\File\Transfer\Adapter\Http;
use Application\Model\Entity\KleoEntity;
use Application\Form\KleoForm;

/**
 * Nome: KleoController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle com propriedade do ORM
 */
class KleoController extends AbstractActionController {

  private $_doctrineORMEntityManager;
  private $sessao;

  const nomeAplicacao = 'kleosolucoes';  
  const stringFormulario = 'formulario';
  const stringAction = 'action';
  const stringId = 'id';
  const stringToken = 'token';
  const controllerCadastro = 'Application\Controller\Cadastro';
  const rotaCadastro = 'cadastro';
  const url = 'http://ubiquitous-memory-falecomleonardopereira890682.codeanyapp.com/';
  const stringMensagem = 'mensagem';
  const diretorioDocumentos = '/../../../../public/assets';

  const emailTitulo = 'ToNoShop';
  const emailLeo = 'falecomleonardopereira@gmail.com';
  const emailKort = 'diegokort@gmail.com';


  /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
  public function __construct(EntityManager $doctrineORMEntityManager = null) {

    if (!is_null($doctrineORMEntityManager)) {
      $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
    }
  }

  /**
     * Função para enviar email
     * @param String $email
     * @param String $titulo
     * @param String $mensagem
     */
  public static function enviarEmail($emails, $titulo, $mensagem) {
    $mail = new PHPMailer;
    try {
      //            $mail->SMTPDebug = 1;
      $mail->isSMTP();
      $mail->Host = '200.147.36.31';
      $mail->SMTPAuth = true;
      $mail->Username = 'leonardo@circuitodavisao.com.br';
      $mail->Password = 'Leonardo142857';
      //      $mail->SMTPSecure = 'tls';                            
      $mail->Port = 587;
      $mail->setFrom('leonardo@circuitodavisao.com.br', 'ToNoShop');

      foreach($emails as $email){
        $mail->addAddress($email);  
      }

      $mail->isHTML(true);
      $mail->Subject = $titulo;
      $mail->Body = $mensagem;
      //      $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      $mail->send();
    } catch (Exception $exc) {
      echo $mail->ErrorInfo;
      echo $exc->getMessage();
    }
  }

  /**
  * Retorna a sessao inicializada
  */
  public function getSessao(){
    if (!$this->sessao) {
      $this->sessao = new Container(self::nomeAplicacao);
    }
    return $this->sessao;
  }

  /**
     * Funcao para por o id na sessao
     * @return Json
     */
  public function kleoAction() {
    $request = $this->getRequest();
    $response = $this->getResponse();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $action = $post_data[self::stringAction];
        $id = $post_data[self::stringId];
        $sessao = self::getSessao();
        $sessao->idSessao = $id;
        $response->setContent(Json::encode(
          array(
          'response' => 'true',
          'url' => '/' . $action,
        )));
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return $response;
  }

  /**
     * Funcao para escrever documentos com id da entidade
     */
  public function escreveDocumentos(KleoEntity $entidade) {
    $adaptadorHttp = new Http();  
    $destino = dirname(__DIR__) . self::diretorioDocumentos;
    $adaptadorHttp->setDestination($destino);

    $files = $adaptadorHttp->getFileInfo();         
    foreach ($files as $file => $info) {
      if ($adaptadorHttp->isUploaded($file) && $adaptadorHttp->isValid($file)) {
        $extension = substr($info['name'], strrpos($info['name'], '.') + 1);
        $filename = '';          
       
          if($file === KleoForm::inputUploadCPF){
            $filename = $entidade->getId() . '_cpf.' . $extension;
            $entidade->setUploadCPF($filename);
          }
          if($file === KleoForm::inputUploadContratoSocial){
            $filename = $entidade->getId() . '_contrato_social.' . $extension;
            $entidade->setUploadContratoSocial($filename);
          }
        
        $adaptadorHttp->addFilter('Rename',
                                  array(
          'target' => $destino . '/' . $filename, 
          'overwrite'=>true
        )                                       );
        $adaptadorHttp->receive($info['name']);
      }else{
        //var_dump($adaptadorHttp->getMessages());
      }
    }
    return $entidade;
  }




  /**
     * Recupera ORM
     * @return EntityManager
     */
  public function getDoctrineORMEntityManager() {
    return $this->_doctrineORMEntityManager;
  }

}
