// CSRF Token for Axios
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Initialize an array to store the numbers
let numbers = [];

// Add number to the list
document.getElementById('addNumber').addEventListener('click', function () {
    const phoneNumber = document.getElementById('PhoneNumber').value.trim();
    const senderName = document.getElementById('SenderName').value;

    // Clear previous error messages
    document.getElementById('errorPhoneNumber').textContent = '';
    document.getElementById('errorSenderName').textContent = '';

    let isValid = true;

    // Validate phone number
    const phoneNumberRegex = /^201\d{9}$/; // Must start with 201 followed by 9 digits
    if (!phoneNumberRegex.test(phoneNumber)) {
        document.getElementById('errorPhoneNumber').textContent = 'Phone number must be 12 digits and in the format 201*********.';
        isValid = false;
    }

    if (phoneNumber && senderName) {

        if (isValid) {
            // Add to the list if validation passes
            numbers.push({ phoneNumber, senderName });
            updateNumbersList();
            clearInputs();
        }

        // numbers.push({ phoneNumber,  senderName });
        // updateNumbersList();
        // clearInputs();
    } else {
        alert('Please fill out all fields.');
    }
});

// Update the displayed list of numbers
function updateNumbersList() {
    const numbersList = document.getElementById('numbersList');
    numbersList.innerHTML = ''; // Clear the list
    numbers.forEach(number => {
        const listItem = document.createElement('div');
        listItem.classList.add('number-item');
        listItem.innerHTML = `
            <span>Phone: ${number.phoneNumber}, Sender: ${number.senderName}</span>
            <button class="btn btn-danger btn-sm" onclick="removeNumber('${number.phoneNumber}')">X</button>
        `;
        numbersList.appendChild(listItem);
    });
}

// Remove number from the list
function removeNumber(phoneNumber) {
    numbers = numbers.filter(item => item.phoneNumber !== phoneNumber);
    updateNumbersList();
}



// Clear input fields
function clearInputs() {
    document.getElementById('PhoneNumber').value = '';
    document.getElementById('SenderName').value = 'UESystems';
}

// Reset error messages and success message
function ResetErrorMessages() {

    document.getElementById("errorPhoneNumber").textContent = "";
    document.getElementById("errorSenderName").textContent = "";
    document.getElementById("errorMessage").textContent = "";
    document.getElementById("errorNumbersList").textContent = "";

} 

// Handle sending all numbers to the backend
document.getElementById('sendNumbers').addEventListener('click', function () {
    const messageContent = document.getElementById('Message').value.trim();

    if (numbers.length === 0) {
        alert('No numbers added!');
        return;
    }

    if (!messageContent) {
        alert('Please enter the message content.');
        document.getElementById("errorMessage").textContent = "The message must be at least 2 characters.";
        return;
    }

    // Reset error messages and success message
    ResetErrorMessages();

    //Reset success Message 
    document.getElementById("successMessage").classList.add("d-none");


    axios.post('/send-sms', { numbers, message: messageContent })
        .then(response => {
            // alert('Message sent successfully!');
            console.log(response.data);
            // Show success message
            if (response.data == "1") {

                const successMessage = document.getElementById("successMessage");
                successMessage.textContent = "SMS sent successfully!";
                successMessage.classList.remove("d-none");

                // Remove the message after 3 seconds (3000 milliseconds)
                setTimeout(() => {
                    successMessage.classList.add("d-none");
                }, 3000);
                
                numbers = [];
                updateNumbersList();
                document.getElementById('Message').value = '';
            }
            else {

                const failedMessage = document.getElementById("failedMessage");
                failedMessage.textContent = "Failed to send SMS";
                failedMessage.classList.remove("d-none");
                
                // Remove the message after 3 seconds (3000 milliseconds)
                setTimeout(() => {
                    failedMessage.classList.add("d-none");
                }, 3000);
            }
        })
        .catch(error => {

            if (error.response && error.response.data.errors) {
                const errors = error.response.data.errors;
    
                // Display errors below relevant fields
                if (errors['numbers.0.phoneNumber']) {
                    document.getElementById("errorPhoneNumber").textContent = errors['numbers.0.phoneNumber'][0];
                    document.getElementById("errorNumbersList").textContent = 'You should remove the wrong phone number from list.';
                }
                if (errors['numbers.0.senderName']) {
                    document.getElementById("errorSenderName").textContent = errors['numbers.0.senderName'][0];
                }
                if (errors.message) {
                    document.getElementById("errorMessage").textContent = errors.message[0];
                }
            }
            
            
        });
});
