<?php
require_once __DIR__.'/../includes/functions.php';
$timezones = getTimezones();

// Get the current URL for default app URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol.'://'.$host;
// Remove /installer from the URL
$baseUrl = str_replace('/installer', '', $baseUrl);
?>

<div class="bg-white rounded-2xl card-shadow p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-purple-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Application Configuration</h2>
        <p class="text-gray-600">Configure your school information</p>
    </div>

    <form method="POST" id="adminForm" class="space-y-8">
        <!-- Application Settings -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                School Information
            </h3>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">School Name</label>
                    <input type="text" id="app_name" name="app_name" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="My School Name">
                </div>

                <div>
                    <label for="app_url" class="block text-sm font-medium text-gray-700 mb-2">Application URL</label>
                    <input type="url" id="app_url" name="app_url" required value="<?php echo htmlspecialchars($baseUrl); ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="https://school.example.com">
                </div>
            </div>

            <div class="mt-4">
                <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                <select id="timezone" name="timezone" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <?php foreach ($timezones as $value => $label) { ?>
                    <option value="<?php echo htmlspecialchars($value); ?>" <?php echo $value === 'Asia/Kolkata' ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($label); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <!-- Admin Account Settings -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Admin Account
            </h3>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">Admin Email</label>
                    <input type="email" id="admin_email" name="admin_email" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="admin@yourschool.com">
                    <p class="mt-1 text-xs text-gray-500">This will be your login email</p>
                </div>

                <div>
                    <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">Admin Password</label>
                    <input type="password" id="admin_password" name="admin_password" required minlength="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="Minimum 8 characters">
                    <p class="mt-1 text-xs text-gray-500">Choose a strong password</p>
                </div>
            </div>

            <div class="mt-4">
                <label for="admin_password_confirm" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input type="password" id="admin_password_confirm" name="admin_password_confirm" required minlength="8"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                    placeholder="Re-enter your password">
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-800 mb-1">School Admin Account</h4>
                    <p class="text-sm text-blue-700">
                        The email and password you enter here will be used to create your school admin account. This will be your primary login to manage the school.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <label class="flex items-start cursor-pointer">
                <input type="checkbox" id="share_usage_data" name="share_usage_data" value="1"
                    class="mt-1 h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                <span class="ml-3 text-sm text-gray-700">
                    <span class="font-medium text-gray-800">Help improve Gego K12</span> — share anonymous
                    installation data (your domain, app version, PHP version). No school data, admin email,
                    or personal information is ever sent. Unchecked by default; you can leave this off.
                </span>
            </label>
        </div>

        <div class="flex justify-between">
            <a href="?step=3" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Back
            </a>

            <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all">
                Continue
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </form>
</div>
