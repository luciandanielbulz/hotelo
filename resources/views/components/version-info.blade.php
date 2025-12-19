@if($showDetailed)
    <!-- Detaillierte Versionsinformationen -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">quickBill Version</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $version }}</dd>
                    </dl>
                </div>
            </div>
            
            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Build-Datum</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $buildInfo['build_date'] }}</dd>
                    </div>
                </div>
                
                <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Umgebung</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($buildInfo['environment'] === 'production') bg-green-100 text-green-800
                                @elseif($buildInfo['environment'] === 'local') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($buildInfo['environment']) }}
                            </span>
                        </dd>
                    </div>
                </div>
                
                @if($buildInfo['git_commit'])
                <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Git-Commit</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 font-mono">{{ substr($buildInfo['git_commit'], 0, 7) }}</dd>
                    </div>
                </div>
                
                <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Git-Branch</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $buildInfo['git_branch'] }}</dd>
                    </div>
                </div>
                @endif
                
                <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">PHP-Version</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $buildInfo['php_version'] }}</dd>
                    </div>
                </div>
                
                <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Laravel-Version</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $buildInfo['laravel_version'] }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Kompakte Versionsinformationen -->
    <div class="flex items-center text-sm text-gray-500">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Version {{ $version }}</span>
        @if($buildInfo['environment'] !== 'production')
            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                @if($buildInfo['environment'] === 'local') bg-blue-100 text-blue-800
                @else bg-yellow-100 text-yellow-800
                @endif">
                {{ ucfirst($buildInfo['environment']) }}
            </span>
        @endif
    </div>
@endif 