<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gego K12 - Installation Wizard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .step-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .step-completed {
            background: #10b981;
            color: white;
        }
        .step-pending {
            background: #e5e7eb;
            color: #6b7280;
        }
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="gradient-bg py-6">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Gego K12</h1>
                            <p class="text-purple-200 text-sm">School Management System</p>
                        </div>
                    </div>
                    <div class="text-white text-sm">
                        <span class="bg-white/20 px-3 py-1 rounded-full">Version 1.1</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Progress Steps -->
        <div class="bg-white border-b">
            <div class="container mx-auto px-4 py-6">
                <div class="flex items-center justify-center space-x-4 md:space-x-8">
                    <?php
                    $steps = [
                        1 => 'Welcome',
                        2 => 'Requirements',
                        3 => 'Database',
                        4 => 'Configuration',
                        5 => 'Install',
                        6 => 'Complete',
                    ];
                    foreach ($steps as $num => $label) {
                        $class = 'step-pending';
                        if ($num < $step) {
                            $class = 'step-completed';
                        }
                        if ($num == $step) {
                            $class = 'step-active';
                        }
                        ?>
                    <div class="flex items-center">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold <?php echo $class; ?>">
                                <?php if ($num < $step) { ?>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                <?php } else { ?>
                                    <?php echo $num; ?>
                                <?php } ?>
                            </div>
                            <span class="text-xs mt-2 hidden md:block <?php echo $num == $step ? 'text-purple-600 font-semibold' : 'text-gray-500'; ?>">
                                <?php echo $label; ?>
                            </span>
                        </div>
                        <?php if ($num < 6) { ?>
                        <div class="w-8 md:w-16 h-0.5 mx-2 <?php echo $num < $step ? 'bg-green-500' : 'bg-gray-200'; ?>"></div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 container mx-auto px-4 py-8">
            <div class="max-w-3xl mx-auto">
                <?php if ($error) { ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php } ?>

                <?php if ($success) { ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <?php echo htmlspecialchars($success); ?>
                </div>
                <?php } ?>
