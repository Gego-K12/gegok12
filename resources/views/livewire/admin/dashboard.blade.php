<div>
    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- This Month Income -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">This Month Income</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">₹{{ number_format($thisMonthIncome, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $incomeChangePct >= 0 ? '+' : '' }}{{ $incomeChangePct }}% from last month</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-arrow-down text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- This Month Expense -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">This Month Expense</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">₹{{ number_format($thisMonthExpense, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $expenseChangePct >= 0 ? '+' : '' }}{{ $expenseChangePct }}% from last month</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-arrow-up text-red-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- This Year Income -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">This Year Income</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">₹{{ number_format($thisYearIncome, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">All transactions</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- This Year Expense -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">This Year Expense</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">₹{{ number_format($thisYearExpense, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">All transactions</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-orange-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Net Summary -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Net Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600">Total Income</span>
                    <span class="font-semibold text-green-600">₹{{ number_format($totalIncome, 2) }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600">Total Expense</span>
                    <span class="font-semibold text-red-600">₹{{ number_format($totalExpense, 2) }}</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-gray-900 font-medium">Net</span>
                    <span class="text-lg font-bold {{ $net >= 0 ? 'text-blue-600' : 'text-red-600' }}">₹{{ number_format($net, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Account Balances -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Balances</h3>
            <div class="space-y-3">
                @forelse($accounts as $account)
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200 last:border-b-0 last:pb-0">
                        <span class="text-gray-600">{{ $account->name }}</span>
                        <span class="font-semibold text-gray-900">₹{{ number_format($account->currentBalance(), 2) }}</span>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-inbox text-2xl mb-2"></i>
                        <p class="text-sm">No accounts configured</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ url('/admin/income-expense/incomes') }}" class="block px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition text-sm font-medium text-center">
                    <i class="fas fa-plus mr-2"></i>Record Income
                </a>
                <a href="{{ url('/admin/income-expense/expenses') }}" class="block px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition text-sm font-medium text-center">
                    <i class="fas fa-plus mr-2"></i>Record Expense
                </a>
                <a href="{{ url('/admin/income-expense/reports') }}" class="block px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium text-center">
                    <i class="fas fa-file-chart-line mr-2"></i>View Reports
                </a>
            </div>
        </div>
    </div>
</div>
