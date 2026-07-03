<?php
require_once __DIR__.'/../includes/functions.php';
$tools = checkInstallationTools();
$steps = getInstallationSteps();
$allToolsAvailable = $tools['composer']['available'] && $tools['php']['available'];
?>

<div class="bg-white rounded-2xl card-shadow p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-indigo-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Ready to Install</h2>
        <p class="text-gray-600">We'll now install and configure your Gego K12 system</p>
    </div>

    <!-- Installation Summary -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Configuration Summary</h3>
        <div class="bg-gray-50 rounded-xl p-6 space-y-3">
            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                <span class="text-gray-600">Application</span>
                <span class="font-medium text-gray-800"><?php echo isset($_SESSION['admin_config']['app_name']) ? htmlspecialchars($_SESSION['admin_config']['app_name']) : 'Not Set'; ?></span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                <span class="text-gray-600">URL</span>
                <span class="font-medium text-gray-800"><?php echo isset($_SESSION['admin_config']['app_url']) ? htmlspecialchars($_SESSION['admin_config']['app_url']) : 'Not Set'; ?></span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                <span class="text-gray-600">Database</span>
                <span class="font-medium text-gray-800"><?php echo isset($_SESSION['db_config']['database']) ? htmlspecialchars($_SESSION['db_config']['database']) : 'Not Set'; ?></span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-gray-600">Timezone</span>
                <span class="font-medium text-gray-800"><?php echo isset($_SESSION['admin_config']['timezone']) ? htmlspecialchars($_SESSION['admin_config']['timezone']) : 'UTC'; ?></span>
            </div>
        </div>
    </div>

    <!-- Tools Check -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Installation Tools</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($tools as $key => $tool) { ?>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <div class="w-10 h-10 mx-auto mb-2 rounded-full flex items-center justify-center <?php echo $tool['available'] ? 'bg-green-100' : 'bg-red-100'; ?>">
                    <?php if ($tool['available']) { ?>
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <?php } else { ?>
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <?php } ?>
                </div>
                <span class="text-sm font-medium text-gray-700"><?php echo $tool['name']; ?></span>
                <?php if ($tool['required'] && ! $tool['available']) { ?>
                <span class="text-xs text-red-500 block">Required</span>
                <?php } elseif (! $tool['required'] && ! $tool['available']) { ?>
                <span class="text-xs text-gray-400 block">Optional</span>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Installation Progress (Hidden initially) -->
    <div id="installationProgress" class="hidden mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Installation Progress</h3>
        <div class="space-y-3" id="stepsContainer">
            <?php foreach ($steps as $key => $step) { ?>
            <div id="step-<?php echo $key; ?>" class="flex items-center p-4 bg-gray-50 rounded-xl">
                <div class="step-icon w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                    <svg class="w-4 h-4 text-gray-400 pending-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <svg class="w-4 h-4 text-white running-icon hidden animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg class="w-4 h-4 text-white success-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <svg class="w-4 h-4 text-white error-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-medium text-gray-800"><?php echo $step['name']; ?></div>
                    <div class="text-sm text-gray-500 step-description"><?php echo $step['description']; ?></div>
                </div>
                <div class="step-status text-sm text-gray-400">Pending</div>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Error Display -->
    <div id="errorDisplay" class="hidden bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1 min-w-0">
                <h4 class="font-semibold text-red-800 mb-1">Installation Error</h4>
                <p id="errorMessage" class="text-sm text-red-700 break-words"></p>
                <pre id="errorOutput" class="text-xs text-red-600 mt-2 bg-red-100 p-3 rounded overflow-auto max-h-64 whitespace-pre-wrap break-words hidden"></pre>
            </div>
        </div>
    </div>

    <?php if (! $allToolsAvailable) { ?>
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-semibold text-red-800 mb-1">Missing Required Tools</h4>
                <p class="text-sm text-red-700">
                    Composer and PHP CLI are required to run the automated installation. Please install them and try again.
                </p>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="flex justify-between" id="buttonContainer">
        <a href="?step=4" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors" id="backButton">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Back
        </a>

        <?php if ($allToolsAvailable) { ?>
        <button type="button" id="installButton" onclick="startInstallation()" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            <span id="installButtonText">Start Installation</span>
        </button>
        <?php } else { ?>
        <button disabled class="inline-flex items-center px-8 py-3 bg-gray-300 text-gray-500 font-semibold rounded-xl cursor-not-allowed">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Cannot Install
        </button>
        <?php } ?>
    </div>
</div>

<script>
const installationSteps = <?php echo json_encode(array_keys($steps)); ?>;
let currentStepIndex = 0;
let isInstalling = false;

function startInstallation() {
    if (isInstalling) return;
    isInstalling = true;

    // Show progress section
    document.getElementById('installationProgress').classList.remove('hidden');
    document.getElementById('installButton').disabled = true;
    document.getElementById('installButtonText').textContent = 'Installing...';
    document.getElementById('backButton').classList.add('pointer-events-none', 'opacity-50');
    document.getElementById('errorDisplay').classList.add('hidden');

    // Start first step
    runNextStep();
}

function runNextStep() {
    if (currentStepIndex >= installationSteps.length) {
        // All steps completed
        installationComplete();
        return;
    }

    const stepKey = installationSteps[currentStepIndex];
    const stepElement = document.getElementById('step-' + stepKey);

    // Update UI to show running
    updateStepStatus(stepElement, 'running');

    // Run the step via AJAX
    const formData = new FormData();
    formData.append('step', stepKey);

    fetch('run-step.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateStepStatus(stepElement, 'success', data.message);
            currentStepIndex++;
            // Small delay before next step
            setTimeout(runNextStep, 500);
        } else {
            updateStepStatus(stepElement, 'error', data.message);
            showError(data.message, data.output || data.error || '');
        }
    })
    .catch(error => {
        updateStepStatus(stepElement, 'error', 'Request failed');
        showError('Failed to communicate with server', error.message);
    });
}

function updateStepStatus(element, status, message = '') {
    const iconContainer = element.querySelector('.step-icon');
    const statusElement = element.querySelector('.step-status');
    const descElement = element.querySelector('.step-description');

    // Hide all icons
    element.querySelectorAll('.pending-icon, .running-icon, .success-icon, .error-icon').forEach(el => {
        el.classList.add('hidden');
    });

    switch (status) {
        case 'running':
            iconContainer.className = 'step-icon w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center mr-4';
            element.querySelector('.running-icon').classList.remove('hidden');
            statusElement.textContent = 'Running...';
            statusElement.className = 'step-status text-sm text-blue-600 font-medium';
            break;
        case 'success':
            iconContainer.className = 'step-icon w-8 h-8 rounded-full bg-green-500 flex items-center justify-center mr-4';
            element.querySelector('.success-icon').classList.remove('hidden');
            statusElement.textContent = 'Complete';
            statusElement.className = 'step-status text-sm text-green-600 font-medium';
            if (message) descElement.textContent = message;
            break;
        case 'error':
            iconContainer.className = 'step-icon w-8 h-8 rounded-full bg-red-500 flex items-center justify-center mr-4';
            element.querySelector('.error-icon').classList.remove('hidden');
            statusElement.textContent = 'Failed';
            statusElement.className = 'step-status text-sm text-red-600 font-medium';
            if (message) descElement.textContent = message;
            break;
    }
}

function showError(message, output) {
    const errorDisplay = document.getElementById('errorDisplay');
    const errorMessage = document.getElementById('errorMessage');
    const errorOutput = document.getElementById('errorOutput');

    errorDisplay.classList.remove('hidden');
    errorMessage.textContent = message;

    if (output && output.trim()) {
        errorOutput.textContent = output;
        errorOutput.classList.remove('hidden');
    }

    // Re-enable buttons
    document.getElementById('installButton').disabled = false;
    document.getElementById('installButtonText').textContent = 'Retry Installation';
    document.getElementById('backButton').classList.remove('pointer-events-none', 'opacity-50');
    isInstalling = false;
}

function installationComplete() {
    // Redirect to completion page
    window.location.href = '?step=6';
}
</script>
