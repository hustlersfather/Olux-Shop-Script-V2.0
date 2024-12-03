<?php

/**
 * Start the output buffering and session management.
 *
 * Output buffering ensures that no output is sent to the browser before the headers.
 * This allows us to handle redirects or set headers dynamically during execution.
 * 
 * Session management is initiated to authenticate the user and track session data 
 * like the username and session-specific credentials.
 */
ob_start();
session_start();

/**
 * Set the default timezone to UTC.
 *
 * This ensures that all date and time values logged in the system are consistent 
 * and based on a standard timezone, regardless of the server's location.
 */
date_default_timezone_set('UTC');

/**
 * Include the application configuration file.
 *
 * This file is expected to contain database connection settings and other global
 * configurations needed by this script. It must be properly secured to avoid 
 * exposing sensitive information.
 */
include "includes/config.php";

/**
 * Suppress all error reporting.
 *
 * While this prevents users from seeing sensitive error messages, it can hinder debugging.
 * It's recommended to use error logging in production instead of suppressing errors.
 */
error_reporting(0);

/**
 * Verify user authentication.
 *
 * If the user is not logged in (i.e., `sname` and `spass` are not set in the session), 
 * redirect them to the login page and exit the script. This ensures that only authorized 
 * users can access this functionality.
 */
if (!isset($_SESSION['sname']) || !isset($_SESSION['spass'])) {
    header("location: login.html");
    exit();
}

/**
 * Sanitize and decode a base64-encoded string.
 *
 * @param string $item The base64-encoded string to be sanitized.
 * @return string The sanitized and decoded string.
 *
 * This function strips HTML tags from the decoded string to prevent XSS (Cross-Site Scripting) attacks.
 */
function sanitizeInput($item) {
    return strip_tags(base64_decode($item));
}

/**
 * Retrieve and sanitize query parameters.
 *
 * These parameters are expected to be passed via the URL as GET requests.
 * The `sanitizeInput` function is used to clean the inputs, and `mysqli_real_escape_string` 
 * is used to escape any special characters to prevent SQL injection attacks.
 */
$subject = isset($_GET['s']) ? mysqli_real_escape_string($dbcon, sanitizeInput($_GET['s'])) : null;
$message = isset($_GET['m']) ? base64_decode(mysqli_real_escape_string($dbcon, $_GET['m'])) : null;
$ticketId = isset($_GET['id']) ? mysqli_real_escape_string($dbcon, $_GET['id']) : null;

/**
 * Validate the required fields.
 *
 * If either the subject or message is empty, prompt the user to fill in all fields
 * and terminate the script. This validation ensures that all necessary data is provided.
 */
if (empty($subject) || empty($message)) {
    echo '<script>alert("Please complete all fields.")</script>';
    exit();
}

/**
 * Retrieve the current user's information from the session.
 *
 * The `sname` session variable stores the username of the logged-in user.
 * This information is used to fetch the user's details from the database.
 */
$userId = mysqli_real_escape_string($dbcon, $_SESSION['sname']);

/**
 * Fetch user details from the database.
 *
 * This query retrieves the user's ID (`s_id`) and reseller status (`resseller`) from the `users` table.
 * These details are required for processing the ticket.
 *
 * @throws Exception if the query fails or the user is not found.
 */
$userQuery = mysqli_query($dbcon, "SELECT * FROM users WHERE username='$userId'") or die(mysqli_error($dbcon));

if ($row = mysqli_fetch_assoc($userQuery)) {
    // Extract user details for ticket creation.
    $sellerId = mysqli_real_escape_string($dbcon, $row['s_id']);
    $reseller = mysqli_real_escape_string($dbcon, $row['resseller']);
    $currentDate = date("Y/m/d h:i:s");

    /**
     * Format the ticket content.
     *
     * The message is wrapped in an HTML structure to make it visually presentable.
     * Special characters in the message are escaped using `htmlspecialchars` to prevent XSS attacks.
     */
    $formattedMessage = htmlspecialchars($message);
    $ticketContent = '
        <div class="panel panel-default">
            <div class="panel-body">' . $formattedMessage . '</div>
            <div class="panel-footer">
                <div class="label label-info">' . $userId . '</div> - ' . date("d/m/Y h:i:s a") . '
            </div>
        </div>';

    /**
     * Insert the ticket into the database.
     *
     * The `ticket` table stores all relevant details about the ticket, including:
     * - `uid`: User ID of the ticket creator.
     * - `status`: Status of the ticket (1 = open).
     * - `s_id`: Seller ID associated with the ticket.
     * - `memo`: The formatted message content.
     * - Other metadata fields like type, reseller status, and timestamps.
     *
     * @throws Exception if the insert query fails.
     */
    $insertQuery = "
        INSERT INTO `ticket` 
        (`uid`, `status`, `s_id`, `s_url`, `memo`, `acctype`, `admin_r`, `date`, `subject`, `type`, `resseller`, `price`, `refunded`, `fmemo`, `seen`, `lastreply`, `lastup`) 
        VALUES 
        ('$userId', '1', '$sellerId', '$surl', '$ticketContent', '$type', '0', '$currentDate', '$subject', 'refunding', '$reseller', '1', 'Not Yet !', '$message', '0', '$userId', '$currentDate')
    ";
    
    $queryResult = mysqli_query($dbcon, $insertQuery) or die(mysqli_error($dbcon));

    /**
     * Handle the result of the insert operation.
     *
     * If the query was successful, redirect the user to the tickets page.
     * Otherwise, display an error message indicating that the ticket was not created.
     */
    if ($queryResult) {
        echo '<script>window.location.replace("./tickets.html");</script>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Your ticket was not sent due to an error!</div>';
    }
} else {
    /**
     * Handle the case where the user is not found in the database.
     *
     * Display an error message indicating that the user does not exist.
     */
    echo '<div class="alert alert-danger" role="alert">User not found!</div>';
}
?>