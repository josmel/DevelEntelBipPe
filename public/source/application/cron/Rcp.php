2<?php

define("CONSOLE", true);
require_once realpath(dirname(__FILE__) . '/../../public') . '/index.php';
sleep(2);
$servicio = new App_Controller_Action_Helper_GetResultSoap();
$cuerpoMensaje = App_Controller_Action_Helper_GetResultXml();
$Ecpc = new Admin_Model_Ecpc();
$datEcpc = $Ecpc->listaStateTrue($argv[1]);
if ($datEcpc) {
    $Customer = new Admin_Model_Customer();
    $datCustomer = $Customer->getCustomerNumber($datEcpc);
    if ($datCustomer == 'ack') {
        $xmlMsgSP = $cuerpoMensaje->_ConsultaPreviaAceptadaCedente($datEcpc['Numeracion'], '01', $argv[1]);
    } else {
        $datEcpc['ERROR'] = $datCustomer;
        $xmlMsgSP = $cuerpoMensaje->_ConsultaPreviaObjecion($datEcpc, '01', $argv[1]);
    }
    $enviarMensaje = $servicio->_receiveMessage($xmlMsgSP);
} else {
    $enviarMensaje = 'no hay registro en el ecpc';
}
echo $enviarMensaje;
exit;