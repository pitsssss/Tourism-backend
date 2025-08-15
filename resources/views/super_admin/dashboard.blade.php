<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-900 leading-tight">
            لوحة تحكم ال أدمن – إضافة أدمن جديد
        </h2>
         <div class="mb-6 text-right">
                <a href="{{ route('super_admin.admins') }}" class="inline-block bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 transition">
                    عرض الأدمنز
                </a>
            </div>
    

    <div class="py-10 bg-blue-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- زر الانتقال لعرض الأدمنز -->
           

            <!-- نموذج الإضافة -->
            <div class="bg-white shadow-xl rounded-xl p-6">
                <h3 class="text-lg font-bold text-blue-800 mb-4">إضافة أدمن جديد:</h3>
                <form action="{{ route('super_admin.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700">الاسم</label>
                            <input type="text" name="name" class="w-full border rounded px-3 py-2 mt-1" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">البريد الإلكتروني</label>
                            <input type="email" name="email" class="w-full border rounded px-3 py-2 mt-1" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">كلمة المرور</label>
                            <input type="password" name="password" class="w-full border rounded px-3 py-2 mt-1" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">الدور</label>
                            <select name="role" class="w-full border rounded px-3 py-2 mt-1" required>
                                <option value="">اختر الدور</option>
                                <option value="admin_users">Admin Users</option>
                                <option value="admin_trips">Admin Trips</option>
                                <option value="admin_hotels">Admin Hotels</option>
                                <option value="admin_restaurants">Admin Restaurants</option>
                                <option value="admin_places">Admin Places</option>
                                <option value="admin_tour_guides">Admin Tour Guides</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        إضافة
                    </button>
                </form>
                
            </div>
        </div>
    </div>
    </x-slot>
</x-app-layout>
