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

// // Get operator name by ID
// function getOperatorName(operatorID) {
//     switch (operatorID) {
//         case '1': return 'Vodafone';
//         case '2': return 'Orange';
//         case '3': return 'Etisalat';
//         case '7': return 'WE';
//         default: return 'Unknown';
//     }
// }

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

    axios.post('/send-sms', { numbers, message: messageContent })
        .then(response => {
            alert('Message sent successfully!');
            numbers = [];
            updateNumbersList();
            document.getElementById('Message').value = '';
        })
        .catch(error => {
            alert('Error sending the message.');
            console.error(error);
        });
});
