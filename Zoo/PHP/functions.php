<?php

function get_booking_slots($conn) {
    $sql = "SELECT * FROM booking_slots WHERE is_available = 1 AND availability_spots > 0 ORDER BY start_time ASC";
    $result = $conn->query($sql);

    $slots = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $slots[] = $row;
        }
    }
    return $slots;
}

function get_tickets($conn) {
    $sql = "SELECT * FROM tickets WHERE availability > 0 ORDER BY ticket_name ASC";
    $result = $conn->query($sql);

    $tickets = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
    }
    return $tickets;
}

function calculate_total_price($ticket_quantities, $tickets) {
    $total_price = 0;
    foreach ($ticket_quantities as $ticket_id => $quantity) {
        if ($quantity > 0) {
            foreach ($tickets as $ticket) {
                if ($ticket['ticket_id'] == $ticket_id) {
                    $total_price += $ticket['price'] * $quantity;
                    break;
                }
            }
        }
    }
    return $total_price;
}

function generate_confirmation_number() {
    return uniqid('BOOK-', true); // Generate a unique booking ID
}

function process_booking($conn, $user_id, $slot_id, $ticket_quantities, $total_price, $confirmation_number) {
    try {
        // Start transaction (mysqli doesn't have direct transaction methods like PDO)
        $conn->begin_transaction();

        // 1. Insert into bookings table
        $sql = "INSERT INTO bookings (user_id, slot_id, total_price, confirmation_number, payment_status) VALUES (?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iids", $user_id, $slot_id, $total_price, $confirmation_number);
        $stmt->execute();
        $booking_id = $conn->insert_id;

        // 2. Insert into booked_tickets table
        foreach ($ticket_quantities as $ticket_id => $quantity) {
            if ($quantity > 0) {
                // Get the ticket price
                $sql = "SELECT price FROM tickets WHERE ticket_id = ?";
                $stmt_price = $conn->prepare($sql);
                $stmt_price->bind_param("i", $ticket_id);
                $stmt_price->execute();
                $result = $stmt_price->get_result();
                 if ($result->num_rows === 0) {
                    throw new Exception("Ticket with ID $ticket_id not found.");
                 }

                $ticket_price = $result->fetch_assoc()['price'];

                $sql = "INSERT INTO booked_tickets (booking_id, ticket_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt_booked = $conn->prepare($sql);
                $stmt_booked->bind_param("iiid", $booking_id, $ticket_id, $quantity, $ticket_price);
                $stmt_booked->execute();

                // 3. Update tickets table (reduce availability)
                $sql = "UPDATE tickets SET availability = availability - ? WHERE ticket_id = ?";
                $stmt_update_ticket = $conn->prepare($sql);
                $stmt_update_ticket->bind_param("ii", $quantity, $ticket_id);
                $stmt_update_ticket->execute();

                if ($stmt_update_ticket->affected_rows === 0) {
                    throw new Exception("Failed to update availability for ticket ID $ticket_id.");
                }
            }
        }

        // 4. Update booking_slots table (reduce availability_spots)
        $sql = "UPDATE booking_slots SET availability_spots = availability_spots - 1 WHERE slot_id = ?";
        $stmt_update_slot = $conn->prepare($sql);
        $stmt_update_slot->bind_param("i", $slot_id);
        $stmt_update_slot->execute();

        if ($stmt_update_slot->affected_rows === 0) {
            throw new Exception("Failed to update availability_spots for slot ID $slot_id.");
        }

        // Commit transaction
        $conn->commit();

        return $booking_id;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Booking transaction failed: " . $e->getMessage());
        return false;
    }
}

?>