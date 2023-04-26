<?php

$sql = "SELECT id FROM tickets ";

if($_GET['ticket']) {
    $ticket = $_GET['ticket'];
    $sql .= " WHERE ticket_id=?";

}

$stmt1 = $conn->prepare($sql); 

if($_GET['ticket']) {
    $stmt1->bind_param("s", $ticket);
}
$stmt1->execute();
$result1 = $stmt1->get_result(); // get the mysqli result
$select_data = $result1->fetch_assoc(); // fetch data


if($_GET['ticket']) {

    $ticket_id = $select_data['id'];
    $ticket_input_html = '<input type="text" class="form-control" id="ticket" value="'.$ticket.'" disabled>';
    $ticket_input_html .= '<input type="hidden" id="ticket_id" name="ticket_id" value="'.$ticket_id.'">';
} else {
    //TODO - display dropdown to select ticket to add log for it.

}