<x-layout :isLoginPage="false">
    <x-slot:heading class="text-center bg-blue-700">
        Agent Listing
    </x-slot:heading>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        /* Drawer styles */
        .drawer {
            position: fixed;
            top: 0;
            right: -100%; /* Hidden by default */
            width: 30%;
            max-width: 400px;
            height: 100%;
            background: #FFFFFF; /* White background for the drawer */
            overflow-y: auto;
            transition: right 0.3s ease;
            z-index: 1050;
            color: black; /* Black text inside the drawer */
        }

        .drawer.open {
            right: 0; /* Show the drawer */
        }

        .drawer-header {
            padding: 17px;
            background-color: #1F2937; /* Tailwind bg-gray-600 color */
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }

        .drawer-body {
            padding: 20px;
            background-color: #FFFFFF; /* White background for the body */
            color: black; /* Black text for the body */
        }

        .drawer-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .drawer-buttons button {
            flex: 1;
            max-width: 150px;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .btn-cancel {
            background-color: #DC2626; /* Tailwind's red-600 */
            color: white;
        }

        .btn-cancel:hover {
            background-color: #B91C1C; /* Darker red for hover */
        }

        .btn-submit {
            background-color: #2563EB; /* Tailwind's indigo-600 */
            color: white;
        }

        .btn-submit:hover {
            background-color: #1E40AF; /* Darker indigo for hover */
        }
    </style>

    <body class="h-full bg-gray-100">
    <!-- Alert for Success Messages -->
    @if(session('success'))
        <div class="alert alert-success bg-green-500 text-white p-4 rounded-md text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- Button to trigger the Create Drawer -->
    <div class="flex justify-end mb-4">
        <button class="bg-blue-700 hover:bg-blue-500 text-white py-2 px-4 rounded-lg" onclick="toggleDrawer('createDrawer')">
            <svg width="34px" height="34px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#0d29fd"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 20V19C4 16.2386 6.23858 14 9 14H12.75M17.5355 13.9645V17.5M17.5355 17.5V21.0355M17.5355 17.5H21.0711M17.5355 17.5H14M15 7C15 9.20914 13.2091 11 11 11C8.79086 11 7 9.20914 7 7C7 4.79086 8.79086 3 11 3C13.2091 3 15 4.79086 15 7Z" stroke="#ffffff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
        </button>
    </div>

    <!-- Table -->
    <!-- Table -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
            <tr class="bg-gray-800 text-white">
                <th class="border border-gray-300 p-2">ID</th>
                <th class="border border-gray-300 p-2">Name</th>
                <th class="border border-gray-300 p-2">Last Name</th>
                <th class="border border-gray-300 p-2">Email</th>
                <th class="border border-gray-300 p-2">Password</th>
                <th class="border border-gray-300 p-2">Creation Date</th>
                <th class="border border-gray-300 p-2">Modified Date</th>
                <th class="border border-gray-300 p-2">Agent Order</th> <!-- New Column -->
                <th class="border border-gray-300 p-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($agents as $agent)
                <tr class="hover:bg-gray-100">
                    <td class="border border-gray-300 p-2">{{ $agent->id }}</td>
                    <td class="border border-gray-300 p-2">{{ $agent->first_name }}</td>
                    <td class="border border-gray-300 p-2">{{ $agent->last_name }}</td>
                    <td class="border border-gray-300 p-2">{{ $agent->email }}</td>
                    <td class="border border-gray-300 p-2">{{ $agent->password }}</td>
                    <td class="border border-gray-300 p-2">{{ $agent->created_at->format('d/m/Y H:i') }}</td>
                    <td class="border border-gray-300 p-2">{{ $agent->updated_at->format('d/m/Y H:i') }}</td>
                    <td class="border border-gray-300 p-2">{{ $agent->AgentOrder }}</td> <!-- Display AgentOrder -->
                    <td class="border border-gray-300 p-2 flex space-x-2">
                        <!-- Edit Button with Pencil Icon -->
                        <button onclick="toggleDrawer('editDrawer', {{ $agent->id }})">
                            <svg width="34px" height="34px" viewBox="-4.8 -4.8 33.60 33.60" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z" stroke="#4d72e0" stroke-width="0.9600000000000002" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13" stroke="#4d72e0" stroke-width="0.9600000000000002" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                        </button>
                        <!-- Delete Button with Trash Icon -->
                        <form action="{{ route('agent.destroy', $agent->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">
                                <svg width="34px" height="34px" viewBox="-204.8 -204.8 1433.60 1433.60" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000" stroke="#000000" stroke-width="0.01024"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M905.92 237.76a32 32 0 0 0-52.48 36.48A416 416 0 1 1 96 512a418.56 418.56 0 0 1 297.28-398.72 32 32 0 1 0-18.24-61.44A480 480 0 1 0 992 512a477.12 477.12 0 0 0-86.08-274.24z" fill="#ff0000"></path><path d="M630.72 113.28A413.76 413.76 0 0 1 768 185.28a32 32 0 0 0 39.68-50.24 476.8 476.8 0 0 0-160-83.2 32 32 0 0 0-18.24 61.44zM489.28 86.72a36.8 36.8 0 0 0 10.56 6.72 30.08 30.08 0 0 0 24.32 0 37.12 37.12 0 0 0 10.56-6.72A32 32 0 0 0 544 64a33.6 33.6 0 0 0-9.28-22.72A32 32 0 0 0 505.6 32a20.8 20.8 0 0 0-5.76 1.92 23.68 23.68 0 0 0-5.76 2.88l-4.8 3.84a32 32 0 0 0-6.72 10.56A32 32 0 0 0 480 64a32 32 0 0 0 2.56 12.16 37.12 37.12 0 0 0 6.72 10.56zM726.72 297.28a32 32 0 0 0-45.12 0l-169.6 169.6-169.28-169.6A32 32 0 0 0 297.6 342.4l169.28 169.6-169.6 169.28a32 32 0 1 0 45.12 45.12l169.6-169.28 169.28 169.28a32 32 0 0 0 45.12-45.12L557.12 512l169.28-169.28a32 32 0 0 0 0.32-45.44z" fill="#ff0000"></path></g></svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <!-- Create Drawer -->
    <div id="createDrawer" class="drawer">
        <div class="drawer-header">
            Register Agent
        </div>
        <div class="drawer-body">
            <div id="createFormContainer">
                <!-- Create form will be loaded dynamically here -->
            </div>
        </div>
    </div>

    <!-- Edit Drawer -->
    <div id="editDrawer" class="drawer">
        <div class="drawer-header">
            Edit Agent
        </div>
        <div class="drawer-body">
            <div id="editFormContainer">
                <!-- Edit form will be loaded dynamically here -->
            </div>
        </div>
    </div>

    <script>
        function toggleDrawer(drawerId, agentId = null) {
            const drawer = document.getElementById(drawerId);

            if (drawer.classList.contains('open')) {
                drawer.classList.remove('open');
            } else {
                drawer.classList.add('open');

                if (drawerId === 'editDrawer' && agentId) {
                    // Load edit form dynamically
                    fetch(`/agent/${agentId}/edit`)
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById('editFormContainer').innerHTML = data;
                        });
                } else if (drawerId === 'createDrawer') {
                    // Load create form dynamically
                    fetch('/agent/create')
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById('createFormContainer').innerHTML = data;
                        });
                }
            }
        }
    </script>
    </body>
</x-layout>
