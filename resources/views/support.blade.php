<x-layout>
    <x-slot:heading>
        IT Queue
    </x-slot:heading>

    <!-- Button to Open Modal, centered -->
    <div class="flex justify-center items-center py-5">
        <button type="button" class="px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition ease-in-out duration-150" data-bs-toggle="modal" data-bs-target="#agentModal">
            New Request
        </button>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="agentModal" tabindex="-1" aria-labelledby="agentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mx-auto" style="max-width: 400px;"> <!-- Centered with mx-auto -->
            <div class="modal-content bg-gray-100 rounded-lg shadow-lg border border-gray-300">

                <!-- Modal Header -->
                <div class="modal-header flex justify-between items-center bg-blue-600 rounded-t-lg p-4">
                    <h5 class="text-lg font-semibold text-white" id="agentModalLabel">Agent's Turn</h5>
                    <button type="button" class="text-white hover:text-gray-300 focus:outline-none" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body with narrower Agent's Turn Box -->
                <div class="modal-body p-6">
                    <div class="agent-turn mx-auto text-center space-y-4 w-full">
                        <h2 class="text-2xl font-bold text-gray-700">Agent's Turn</h2>
                        <p class="text-lg text-gray-600">José</p>
                        <button class="px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition ease-in-out duration-150">Accept</button>
                        <div class="mt-2">
                            <select class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700">
                                <option>Decline Reason</option>
                                <option>Busy</option>
                                <option>Out of Office</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <button class="px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition ease-in-out duration-150">Decline</button>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer flex justify-end bg-gray-100 rounded-b-lg p-4">
                    <button type="button" class="px-4 py-2 font-medium text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 transition ease-in-out duration-150" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-layout>
