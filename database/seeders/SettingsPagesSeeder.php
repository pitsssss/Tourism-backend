<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsPagesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings_pages')->insert([
            [
                'key' => 'privacy_policy',
                'content_en' => 'At Fatleh, your privacy is our priority. We collect minimal information to provide you with the best travel experience. Your personal data, including your name, email, and booking history, will never be shared with third parties without your consent.
                 By using Fatleh, you agree to this Privacy Policy. We may update it occasionally, and any changes will be notified within the app',

                'content_ar' => 'صوصيتك هي أولويتنا. نحن نجمع الحد الأدنى من المعلومات لتقديم أفضل تجربة سياحية لك. بياناتك الشخصية، بما في ذلك الاسم، البريد الإلكتروني، وسجل الحجوزات، لن تُشارك مع أي جهة ثالثة بدون إذنك.
                                 باستخدامك لتطبيق فتلة، فإنك توافق على هذه السياسة. قد نقوم بتحديثها من وقت لآخر، وسيتم إعلامك بأي تغييرات داخل التطبيق',

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'terms_conditions',
                'content_en' => 'By using Fatleh, you agree to comply with all applicable laws and regulations. Users must provide accurate information when registering or booking trips. Fatleh reserves the right to cancel bookings or suspend accounts in case of misuse or violation of terms.\n These terms may be updated periodically. Continued use of the app indicates your acceptance of the updated terms',

                'content_ar' => 'باستخدامك لتطبيق فتلة، فإنك توافق على الالتزام بجميع القوانين واللوائح المعمول بها. يجب على المستخدمين تقديم معلومات صحيحة عند التسجيل أو حجز الرحلات. يحق لتطبيق فتلة إلغاء الحجوزات أو تعليق الحسابات في حال سوء الاستخدام أو خرق الشروط.
                                 قد يتم تحديث هذه الشروط من حين لآخر. استمرارك في استخدام التطبيق يعني موافقتك على الشروط المحدثة.',


                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'support',
                'content_en' => 'Need help? Contact our support team:
                WhatsApp: +123 \n Email: support@fatleh.com',


                'content_ar' => 'هل تحتاج مساعدة؟ تواصل مع فريق الدعم:
                 واتساب:  +123،
                 البريد الإلكتروني : support@fatleh.com...',


                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'faq',
                'content_en' => '1) How do I book a trip?\n
                                    - Go to the Trips section, select a trip, and follow the booking steps\n
                                 2) How do I change my profile info?\n
                                    - Go to Settings → Profile and update your details\n
\n
                                 3) Can I cancel my booking?
                                    - According to our policy, unfortunelly No you cant

                                 4) How can I book a private trip?
                                    - Simply select your preferred destination and dates, then follow the booking process.

                                 5) Can I customize my private trip itinerary?
                                    - Yes, you can request custom itineraries through the booking form or contact support.

                                 6) Are private trips suitable for groups?
                                    - Yes, you can book private trips for groups of any size.

                                 7) How do I book a flight trip?
                                    - Search for your flight, select your seat, and complete the payment to confirm.

                                 8) Can I book flights for multiple passengers?
                                    - Yes, enter all passenger details during booking.

                                 9) How do I contact support if I have a flight issue?
                                    - Use WhatsApp, email, or the contact form inside the app.  ',


                'content_ar' =>'null',



                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
