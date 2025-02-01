# get-users-by-csv-format

- To clone the repository, run the following command from your directory - 'git clone https://github.com/richard-ui/get-users-by-csv-format.git'

- Execute the command 'php getUsersCsv.php' from your terminal to run the necessary file. This should return the data in CSV Format.

### Notes:

- We create a User class. This is used to store name, email, phone & city from the API request.

- In this class we provide a function that normalises the phone number by using the preg_replace method to remove non digit characters from the current users phone number.

- We also provide a method to validate the email and assure it IS set to lowercase, otherwise set to NULL.

- From the User collection class we declare a users array in the the constructor method which will be used to build up the list of API users.

- Also in this class we loop through each user and write their data as a CSV row through the 'toCsv()' method.

- We use the getApiData to pass in our API end point url parameter which is then used to create a curl request and then return a response accordingly, if successful it will return the users from the API.

- After we call this api data function, we then loop through the users data from the endpoint and use our user entity class to create a new user for user in the API list.

- Also in the loop we call the validation methods ok the user class aswell

- Before the end of the current user in the loop we add this newly created user into our user collection entity class.


- After breaking out the loop we then use headers to set the content type to csv and print out the current user collection class in csv format.