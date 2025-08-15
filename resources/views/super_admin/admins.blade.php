<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-900 leading-tight">
            عرض جميع الأدمنز حسب الدور
        </h2>
   
@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

    <div class="py-10 bg-blue-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-xl p-6">
                <table class="w-full border border-gray-200">
                    <thead class="bg-blue-100 text-blue-900">
                        <tr>
                            <th class="py-2 px-4 border-b">الاسم</th>
                            <th class="py-2 px-4 border-b">البريد الإلكتروني</th>
                            <th class="py-2 px-4 border-b">الدور</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr class="hover:bg-blue-50">
                                <td class="py-2 px-4 border-b">{{ $admin->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $admin->email }}</td>
                                <td class="py-2 px-4 border-b">{{ $admin->role }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6 text-right">
                    <a href="{{ route('super_admin.dashboard') }}" class="text-blue-700 hover:underline">
                        رجوع إلى لوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    </div>
     </x-slot>
</x-app-layout>
