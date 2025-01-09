<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add Numbers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <h1 class="m-4">Send SMS</h1>
    <div class="container mt-5">

        <div class="row mb-3">

            <div class="col-md-3">
                <label for="SenderName" class="form-label">Sender Name:</label>
                <select id="SenderName" class="form-control">
                    <option value="Sender1">Sender 1</option>
                    <option value="Sender2">Sender 2</option>
                    <option value="Sender3">Sender 3</option>
                    <option value="Sender4">Sender 4</option>
                </select>
            </div>

        </div>


        <!-- Input Fields for Phone Number, Operator ID, Sender Name, and Message Content -->
        <h4 class="mb-4">Add Phone Numbers</h4>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="PhoneNumber" class="form-label">Phone Number:</label>
                <input type="text" id="PhoneNumber" class="form-control" placeholder="201234567890">
            </div>
            <!-- <div class="col-md-3">
                <label for="OperatorID" class="form-label">Operator:</label>
                <select id="OperatorID" class="form-control">
                    <option value="1">Vodafone</option>
                    <option value="2">Orange</option>
                    <option value="3">Etisalat</option>
                    <option value="7">WE</option>
                </select>
            </div> -->

            <div class="col-md-2 d-flex align-items-end">
                <button id="addNumber" class="btn btn-primary w-100">Add Number</button>
            </div>
        </div>
        
        <!-- Display the List of Added Numbers -->
        <h6 class="mt-4">Added Numbers</h6>
        <ul id="numbersList" class="list-group">
            <!-- Dynamically added numbers will appear here -->
        </ul>

        <!-- Message Content -->
        <div class="mb-3">
            <label for="Message" class="form-label">Message Content:</label>
            <textarea id="Message" class="form-control" rows="4" placeholder="Enter your message here"></textarea>
        </div>


        <!-- Send Button -->
        <button id="sendNumbers" class="btn btn-success mt-4">Send SMS</button>
    </div>

    <script>
        // Initialize an array to store the numbers
        let numbers = [];

        // Handle adding numbers to the list
        document.getElementById('addNumber').addEventListener('click', function () {
            const phoneNumber = document.getElementById('PhoneNumber').value.trim();
            // const operatorID = document.getElementById('OperatorID').value;
            // const senderName = document.getElementById('SenderName').value;

            if (phoneNumber ) {
                // Add the number and operator ID to the list
                numbers.push({ phoneNumber});

                // // Get operator name based on ID
                // const operatorName = getOperatorName(operatorID);

                // Update the displayed list
                const numbersList = document.getElementById('numbersList');
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.textContent = `Phone: ${phoneNumber}`;

                // Add a delete button to each list item
                const deleteButton = document.createElement('button');
                deleteButton.className = 'btn btn-danger btn-sm';
                deleteButton.textContent = 'X';
                deleteButton.onclick = function () {
                    // Remove number from the array and the displayed list
                    numbers = numbers.filter(item => item.phoneNumber !== phoneNumber);
                    listItem.remove();
                };

                // Append delete button to list item
                listItem.appendChild(deleteButton);

                // Append the list item to the numbers list
                numbersList.appendChild(listItem);

                // Clear the input fields
                document.getElementById('PhoneNumber').value = '';
                // document.getElementById('OperatorID').value = '1';
                // document.getElementById('SenderName').value = 'Sender1';
            } else {
                alert('Please fill out all fields.');
            }
        });

        // Function to get the operator name from operator ID
        function getOperatorName(operatorID) {
            switch (operatorID) {
                case '1': return 'Vodafone';
                case '2': return 'Orange';
                case '3': return 'Etisalat';
                case '7': return 'WE';
                default: return 'Unknown';
            }
        }

        // Handle sending all the numbers to the backend
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

            // Send the data to the backend using Axios
            axios.post('{{ route("send.sms") }}', {
                numbers: numbers, // Send the array of numbers
                message: messageContent, // Send the message content
            })
            .then(response => {
                alert('Message sent successfully!');
                // Clear the list and message content after sending
                numbers = [];
                document.getElementById('numbersList').innerHTML = '';
                document.getElementById('Message').value = '';
            })
            .catch(error => {
                alert('There was an error sending the message.');
                console.log(error);
            });
        });
    </script>
</body>
</html>
