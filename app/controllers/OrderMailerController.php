<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Core\Controller;

class OrderMailerController extends Controller
{
    public function sendEmailCreateOrder($clientEmail, $clientName, $orderId, $orderItems, $total)
    {
        try {
            $mail = new PHPMailer(true);

            //$mail->SMTPDebug = 2;

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'teste.vaga2001@gmail.com';
            $mail->Password = 'rfmd acha gwbd aijd';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('teste.vaga2001@gmail.com', 'RSP');
            $mail->addAddress($clientEmail, $clientName);


            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = "Detalhes do seu Pedido #{$orderId}";

            $body = "<h2>Obrigado pela sua compra, {$clientName}!</h2>";
            $body .= "<p>Seu pedido #{$orderId} foi confirmado. Aqui estão os detalhes:</p>";
            $body .= "<table border='1' cellpadding='5'>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço</th>
                    </tr>";

            foreach ($orderItems as $item) {
                $productName = isset($item['productName']) ? $item['productName'] : 'Produto desconhecido';
                $body .= "<tr>
                        <td>{$productName}</td>
                        <td>{$item['quantity']}</td>
                        <td>R$ {$item['price']}</td>
                    </tr>";
            }

            $body .= "</table>";
            $body .= "<p><strong>Total: R$ {$total}</strong></p>";
            $body .= "<p>Em breve, você receberá seu pedido!</p>";

            $mail->Body = $body;

            if ($mail->send()) {
                return true;
            } else {
                error_log("Erro ao enviar o e-mail: " . $mail->ErrorInfo);
                return false;
            }
        } catch (Exception $e) {
            error_log("Erro ao enviar o e-mail: " . $e->getMessage());
            return false;
        }
    }

    public function sendEmailDeliveredOrder($clientEmail, $clientName, $orderId)
    {
        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'teste.vaga2001@gmail.com';
            $mail->Password = 'rfmd acha gwbd aijd';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('teste.vaga2001@gmail.com', 'RSP');
            $mail->addAddress($clientEmail, $clientName);

            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = "Seu Pedido #{$orderId} foi Entregue";

            $body = "<h2>Olá, {$clientName}</h2>";
            $body .= "<p>Informamos que o seu pedido <strong>#{$orderId}</strong> foi <strong>entregue</strong>. Por favor, avalie sua compra!</p>";

            $mail->Body = $body;

            return $mail->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail de recebimento: " . $e->getMessage());
            return false;
        }
    }

    public function sendEmailCancelOrder($clientEmail, $clientName, $orderId)
    {
        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'teste.vaga2001@gmail.com';
            $mail->Password = 'rfmd acha gwbd aijd';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('teste.vaga2001@gmail.com', 'RSP');
            $mail->addAddress($clientEmail, $clientName);

            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = "Seu Pedido #{$orderId} foi Cancelado";

            $body = "<h2>Olá, {$clientName}</h2>";
            $body .= "<p>Informamos que o seu pedido <strong>#{$orderId}</strong> foi <strong>cancelado</strong>.</p>";

            $mail->Body = $body;

            return $mail->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail de cancelamento: " . $e->getMessage());
            return false;
        }
    }
}
