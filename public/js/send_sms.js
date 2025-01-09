// CSRF Token for Axios
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Initialize an array to store the numbers
let numbers = [];

// Add number to the list
document.getElementById('addNumber').addEventListener('click', function () {
    const phoneNumber = document.getElementById('PhoneNumber').value.trim();
    const senderName = document.getElementById('SenderName').value;

    if (phoneNumber && senderName) {
        numbers.push({ phoneNumber,  senderName });
        updateNumbersList();
        clearInputs();
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
    document.getElementById('SenderName').value = 'Sender1';
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
        return;
    }

    // Reset error messages and success message
    document.getElementById("errorPhoneNumber").textContent = "";
    document.getElementById("errorSenderName").textContent = "";
    document.getElementById("errorMessage").textContent = "";
    document.getElementById("successMessage").classList.add("d-none");
    
    axios.post('/send-sms', { numbers, message: messageContent })
        .then(response => {
            // alert('Message sent successfully!');
            
            // Show success message
            const successMessage = document.getElementById("successMessage");
            successMessage.textContent = "SMS sent successfully!";
            successMessage.classList.remove("d-none");
            
            numbers = [];
            updateNumbersList();
            document.getElementById('Message').value = '';
        })
        .catch(error => {

            if (error.response && error.response.data.errors) {
                const errors = error.response.data.errors;
    
                // Display errors below relevant fields
                if (errors['numbers.0.phoneNumber']) {
                    document.getElementById("errorPhoneNumber").textContent = errors['numbers.0.phoneNumber'][0];
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
