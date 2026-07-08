<?php
$requirements = checkRequirements();
$permissions = checkPermissions();
$allRequirementsMet = true;
$allPermissionsMet = true;

foreach ($requirements as $req) {
    if (! $req['status']) {
        $allRequirementsMet = false;
    }
}
foreach ($permissions as $perm) {
    if (! $perm['status']) {
        $allPermissionsMet = false;
    }
}

$canProceed = $allRequirementsMet && $allPermissionsMet;
?>

<div class="bg-white rounded-2xl card-shadow p-8">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Server Requirements</h2>
        <p class="text-gray-600">Let's make sure your server meets all the requirements</p>
    </div>

    <!-- PHP Extensions -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
            </svg>
            PHP Requirements
        </h3>
        <div class="bg-gray-50 rounded-xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Requirement</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Required</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Current</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($requirements as $key => $req) { ?>
                    <tr class="<?php echo $req['status'] ? '' : 'bg-red-50'; ?>">
                        <td class="px-4 py-3 text-sm text-gray-800"><?php echo htmlspecialchars($req['name']); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo htmlspecialchars($req['required']); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo htmlspecialchars($req['current']); ?></td>
                        <td class="px-4 py-3 text-center">
                            <?php if ($req['status']) { ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Pass
                            </span>
                            <?php } else { ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Fail
                            </span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Folder Permissions -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
            </svg>
            Folder Permissions
        </h3>
        <div class="bg-gray-50 rounded-xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Folder</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Permission</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($permissions as $key => $perm) { ?>
                    <tr class="<?php echo $perm['status'] ? '' : 'bg-red-50'; ?>">
                        <td class="px-4 py-3 text-sm text-gray-800 font-mono"><?php echo htmlspecialchars($perm['name']); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo htmlspecialchars($perm['current']); ?></td>
                        <td class="px-4 py-3 text-center">
                            <?php if ($perm['status']) { ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Writable
                            </span>
                            <?php } else { ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Not Writable
                            </span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (! $canProceed) { ?>
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-semibold text-red-800 mb-1">Requirements Not Met</h4>
                <p class="text-sm text-red-700">Please fix the issues above before continuing. You may need to:</p>
                <ul class="text-sm text-red-700 mt-2 space-y-1">
                    <li>• Install missing PHP extensions</li>
                    <li>• Run <code class="bg-red-100 px-1 rounded">chmod -R 775 storage bootstrap/cache</code></li>
                    <li>• Contact your hosting provider for assistance</li>
                </ul>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="flex justify-between">
        <a href="?step=1" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Back
        </a>

        <?php if ($canProceed) { ?>
        <form method="POST" class="inline">
            <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all">
                Continue
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </form>
        <?php } else { ?>
        <button disabled class="inline-flex items-center px-8 py-3 bg-gray-300 text-gray-500 font-semibold rounded-xl cursor-not-allowed">
            Continue
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
        <?php } ?>
    </div>
</div>
