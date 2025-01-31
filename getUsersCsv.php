<?php


// Class to represent individual User
class User {
    public $name;
    public $email;
    public $phone;
    public $city;

    public function __construct($name, $email, $phone, $city) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->city = $city;
    }

    // Normalize phone numbers to digits only
    public function normalizePhoneNumber() {
        $this->phone = preg_replace('/\D/', '', $this->phone);  // Remove all non-digit characters
    }

    // Validate email using a basic regex (simple validation)
    public function validateEmail() {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->email = null;  // Set to null if invalid
        } else {
            $this->email = strtolower($this->email);  // Make the email lowercase
        }
    }
}

// Class to hold a collection of Users
class UserCollection {
    private $users = [];

    // Method to add a User to the collection
    public function addUser(User $user) {
        $this->users[] = $user;
    }

    // Method to get all Users
    public function getUsers() {
        return $this->users;
    }

    // Method to export the collection to a CSV file
    public function toCsv() {
        // Open the output stream for CSV file download
        $output = fopen('php://output', 'w');

        // Add CSV headers
        fputcsv($output, ['Name', 'Email', 'Phone', 'City']);

        // Loop through each user and write their data as a CSV row
        foreach ($this->users as $user) {
            fputcsv($output, [$user->name, $user->email, $user->phone, $user->city]);
        }

        fclose($output);  // Close the output stream
    }
}


function getApiData(string $url): array {
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // Execute cURL request and get the response
    $response = curl_exec($ch);

    if(curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
        curl_close($ch);
        return [];
    }

    curl_close($ch);

    return json_decode($response, true);
}

// Fetch data from the API using cURL
$apiUrl = 'https://jsonplaceholder.typicode.com/users';  // Example API endpoint
$usersData = getApiData($apiUrl);

// Create a new UserCollection instance
$userCollection = new UserCollection();

// Loop through the API data and add each user to the collection
foreach ($usersData as $userData) {
    // Assuming the 'address' key holds the 'city'
    $user = new User(
        $userData['name'],
        $userData['email'],
        $userData['phone'],
        $userData['address']['city']  // Access city from 'address' object
    );

    // Normalize phone number and validate email
    $user->normalizePhoneNumber();
    $user->validateEmail();

    // Add the processed user to the collection
    $userCollection->addUser($user);
}

// Set headers to force file download as CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="users.csv"');

// Output the CSV data
$userCollection->toCsv();


