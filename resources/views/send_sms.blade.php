<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send SMS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .numbers-box {

            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;

        }
        .number-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
        }
        .number-item button {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 5px 10px;
        }
        .row.number-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .col-number {
            width: calc(25% - 10px);
            box-sizing: border-box;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div id="errorMessages" class="alert alert-danger d-none" role="alert"></div>

    <div class="container mt-5">

        {{-- <form action="{{ route('send.sms') }}" method="POST"> --}}
            {{-- @csrf --}}

        <h1 class="mb-4">Send SMS</h1>
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="PhoneNumber" class="form-label">Receiver Number:</label>
                <input type="text" id="PhoneNumber" class="form-control" placeholder="201234567890">
                <span id="errorPhoneNumber" class="text-danger small"></span>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="SenderName" class="form-label">Sender Name:</label>
                    <select id="SenderName" name="aa" class="form-control">
                        <option value="UESystems">UESystems</option>
                        {{-- <option value="Sender2">Sender 2</option> --}}
                        {{-- <option value="Sender3">Sender 3</option> --}}
                        {{-- <option value="Sender4">Sender 4</option> --}}
                    </select>
                </div>
                <span id="errorSenderName" class="text-danger small"></span>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="button" id="addNumber" class="mt-3 btn btn-primary w-100">Add to Number list</button>
            </div>

        </div>


        {{-- <h4 class="mt-4">Numbers list</h4> --}}
        <label class="form-label">Numbers list:</label>

        <div id="numbersList" name="list" class="numbers-box"></div>

        <div class="mb-3 mt-3">
            <label for="Message" class="form-label">Message Content:</label>
            <textarea id="Message"  name="message" class="form-control" rows="4" placeholder="Enter your message here"></textarea>
            <span id="errorMessage" class="text-danger small"></span>
        </div>

        <!-- Success Message -->
        <div id="successMessage" class="alert alert-success d-none" role="alert"></div>


        <button id="sendNumbers" class="btn btn-success mt-4">Send SMS</button>



            {{-- <button type="submit">Send SMS</button> --}}
        {{-- </form> --}}
        
    </div>
    <script src="{{ asset('js/send_sms.js') }}"></script>
</body>
</html>
