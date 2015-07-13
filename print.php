<?php

/* TODO: FILTER INPUT */

$customer = ((strlen($_GET['customer']) > 23) ? substr($_GET['customer'],0,22).'..' : $_GET['customer']);
$department = $_GET['department'];
$description = ((strlen($_GET['description']) > 23) ? substr($_GET['description'],0,22).'..' : $_GET['description']);
$order = ((strlen($_GET['order']) > 23) ? substr($_GET['order'],0,22).'..' : $_GET['order']);
$labels = $_GET['l']; //Amount of labels

$department_exp = explode("\n", $department);
$dept = ((strlen($department_exp[0]) > 23) ? substr($department_exp[0],0,22).'..' : $department_exp[0]);
$extra = ((strlen($department_exp[1]) > 23) ? substr($department_exp[1],0,22).'..' : $department_exp[1]);
file_put_contents('test.txt', json_encode($_GET));

/* Change this variable to your company's name. */
$companyName = 'Company Co.' 

/* Get the port for the service. */
$port = "9100";

/* Get the IP address for the target host. */
$host = "192.168.2.100";

/* construct the label */

$label = <<<'EOT'
I8,A,001


Q1215,024
q863
rN
S3
D9
ZT
JF
OD
R24,0
f100
N
A248,829,3,2,2,2,N,"$companyName"
EOT;


$label .= <<<EOT

A329,1152,3,2,3,3,N,"Cust.: $customer"
A425,1152,3,2,3,3,N,"Ref. : $order"
A521,1152,3,2,3,3,N,"Descr: $description"
A617,1152,3,2,3,3,N,"Notes: $dept"
A713,1152,3,2,3,3,N,"       $extra"
P$labels,1

EOT;

} 
	else 
{ 
	echo json_encode(array('result' => 'error', 'message' => 'No such label for administration')); 
//die(); 
}


$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) 
{
	echo json_encode(array('result' => 'error', 'message' => 'create: '.socket_strerror(socket_last_error    ()))); 
	die();
}


$result = socket_connect($socket, $host, $port);
if ($result === false) 
{
	echo "Attempting to connect to '$host' on port '$port'...";
	echo json_encode(array('result' => 'error', 'message' => 'connect: '.socket_strerror(socket_last_error    ()))); 
	die();
} 

socket_write($socket, $label, strlen($label));

socket_close($socket);

echo json_encode(array('result' => 'success', 'message' => 'Label sent')); 

?>