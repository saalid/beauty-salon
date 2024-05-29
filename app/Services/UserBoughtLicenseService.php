<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserBoughtLicense;
use App\Services\SpotPlayerService;
use App\Services\Kavenegar;
use Illuminate\Support\Facades\Crypt;

class UserBoughtLicenseService
{

    public function handle(User $user)
    {
        if($this->checkNumberExist($user->phone)){

            $infoLicense = SpotPlayerService::getLicense($user->phone, 1);

            UserBoughtLicense::create([
                'user_id' => $user->id,
                'product_id' => 1,
                'license_id' => $infoLicense['id'],
                'license_key' => $infoLicense['key'],
                'url_download' => $infoLicense['url']
            ]);
            if(config('app.mode') === "production") {
                (new Kavenegar)->sendOtp($user->phone, "https://neginzare.com/login", 'welcome');
            }

            return true;
        }
        return false;

    }





    private  function checkNumberExist($phoneNumber): bool
    {
        $listNumbers = $this->listUsersBoughtLicense();

        if (array_key_exists($phoneNumber,$listNumbers))
        {
            return true;
        }
        return false;

    }
    private function listUsersBoughtLicense(): array
    {
        return [
            '09143605044' => 'ساناز مصطفى زادگان',
            '09121094313' => 'بهاره نصابي',
            '09119999375' => 'مینا شکری ',
            '09107572373' => 'فاطمه شمس',
            '09357882830' => 'فاطمه بابانژاد',
            '09139018467' => 'رویا احمدی',
            '09371243411' => 'سارینا بیگی ',
            '09339161209' => 'شیوا ماندگانی',
            '09339418644' => 'یاسمن نیک ',
            '09357181343' => 'نرگس پناهنده',
            '09174007154' => 'مریم حسنی',
            '09038007032' => 'هانیه سلیمانی ',
            '09122448626' => 'شادی محصصی',
            '09114349075' => 'آیدا معظمی فراهانی',
            '09336981144' => 'مژگان عبدالهی ',
            '09192554625' => 'مریم نادری',
            '09981832002' => 'مرضیه رضایی',
            '09187248292' => 'منا محمدی ',
            '09103243301' => 'مریم مزروعی',
            '09331311037' => 'موگه خرازی ',
            '+4740601345' => 'هانیه هاشمی ',
            '09195950117' => 'نگار ناهیدیان',
            '09334388233' => 'افسون اکبری ',
            '09306336813' => 'مریم انصاری ',
            '09210204389' => 'سمیرا اسماعیلی ',
            '09124652876' => 'شیوا فروغی',
            '09024321809' => 'سحر فاضلی',
            '09303047676' => 'فاطمه پناهیان',
            '09025941877' => 'هدیه رمضانی',
            '09029335722' => 'روناک علیزاده',
            '09304451378' => 'مریم محسنی فر',
            '09108353061' => 'سروین محمدی ',
            '09124143386' => 'شمیم ماوندادی ',
            '09198797672' => 'مریم عبداللهی ',
            '09399295973' => 'امل پدرام منش',
            '09198812539' => 'پریا علیدادی',
            '+0031517009007' => 'نیکی نیک آیین ',
            '09175117031' => 'نگار آئين مهر ',
            '09030638950' => 'سوگل جانبزرگی ',
            '09158763944' => 'سارا سلطانی نژاد',
            '09127676152' => 'محدثه صفری',
            '09112590452' => 'حانیه جلیل نژاد',
            '09124365392' => 'الهه عزیزی',
            '09147153817' => 'زهرا خدائی',
            '09127500423' => 'نفیسه قنبرزاده اشعری',
            '09337141904' => 'سحر عبادی',
            '09045076752' => 'اسما نارویی ',
            '09104603645' => 'هانیه لطیف نژاد ',
            '09373104404' => 'شعله رشیدی',
            '09181621721' => 'رویا نادی',
            '09145337090' => 'شبنم اکبری',
            '09333243383' => 'بیتا بابانژاد',
            '09131176424' => 'شیوا انصاری',
            '+14168877225' => 'فرانه پيماندار',
            '09120984924' => 'شقایق پورفرج ',
            '09124117516' => 'آیدا رسول زاده',
            '09015313269' => 'سمیرا خلج',
            '09144444661' => 'اوین فروزانفر',
            '09189744267' => 'اسرین خدری',
            '09229770270' => 'زهرا سلطانی',
            '09147973580' => 'کژال جلیلی',
            '09112113316' => 'مهناز بزرگپور نیازی ',
            '+4792276871' => 'سیما قلیزاده',
            '09133298190' => 'فهيمه فهرستيان ',
            '09925056736' => 'صباکاملی',
            '09353551733' => 'مهتاب محمدپور',
            '09215473400' => 'هانیه عطالو',
            '09921717935' => 'فرشته میرزاپور',
            '09372798035' => 'طاهره زارع',
            '09172848147' => 'پرستو حدادی',
            '09123084629' => 'مارال زارنجی',
            '09152809696' => 'الهه احمدی',
            '+9495100172' => 'مینا محمدی',
            '09399764214' => 'گلسا مظلومی',
            '09120960321' => 'زینب فلاح',
            '09120574014' => 'زهرا ملائی',
            '09333853916' => 'مانیا پیروزمند ',
            '09025341259' => 'فریبا صادقزادگان',
            '09128596979' => 'مباركه يعقوبي',
            '09220901887' => 'نشمیل استوار',
            '09116651943' => 'سکینه میثاقی ',
            '09396260609' => 'فاطمه کاویان',
            '09145563343' => 'مهسا بدوی',
            '09339119925' => 'زهرا آقا میرتبار',
            '09123940756' => 'راحله اسکندری',
            '09141800512' => 'پرستو نبی پور',
            '09364446519' => 'بیتا غلام دخت ',
            '09331521892' => 'سحر ضرابی',
            '09399143828' => 'بهاره كاوسى',
            '09388873570' => 'هلیا مهرپور ',
            '09382433024' => 'زهرا خدایی',
            '09120460759' => 'آنا عليجان زاده',
            '09125000009' => 'ازاده اچرشاوي ',
            '09368163061' => 'بهنوش شاکری ',
            '09190484370' => 'پریا جعفری',
            '09198270038' => 'ساراحسینی کیا',
            '09129747491' => 'زهراجهانی ',
            '09120616460' => 'كيميا غلام نژاد ',
            '09028545302' => 'اوین ملکی ',
            '09393067529' => 'بهار عزیزمنشیان',
            '09357540004' => 'فهیمه کاظم زاده',
            '09903730223' => 'مهسا رسولی',
            '09189242170' => 'نیلوفرشاهکرمی',
            '09333737688' => 'مریم طاهری',
            '09120460108' => 'شادى خلدى ',
            '09151140345' => 'هانیه هاشمی',
            '09115410278' => 'سهیلا بسطامی',
            '09118908801' => 'فاطمه ناییجی',
            '09354419321' => 'مریم پیری ',
            '09146721061' => 'فرزانه ملامحمودی',
            '09111566519' => 'مونا پور رستگار',
            '09190285998' => 'سارا رادمنش',
            '09134923065' => 'مرضیه بابایی',
            '09309742603' => 'ندا احمدی',
            '09056619083' => 'امیررضا سالاری',
            '09054476441' =>'مهدی علیخانی',
            '09123193031' =>'کتایون ربانی',
            '09122974897' =>'افسانه مطیع',
            '09331748600' =>'یاس خبری',
	        '09373887566' =>'ساحل احمدی',
	        '09192636391' => 'کیمیا امان اللهی',
            '09120279913' => 'آتوسا وجدانی',
            '09198359703' => 'مریم امیدی ',
            '09135385331' => 'زهرا عبداللهی',
            '09188898967' => 'دنیا صابریان',
            '09303041759' => 'شمیم کردزنگنه ',
            '09909047127' => 'زینب محمد پور ',
            '09111526713' => 'مریم اهنگر ',
            '09017179271' => 'مژگان ولیان زاده ',
            '09198003818' => 'مهدیس یوسفی ',
            '09129646481' => 'شهرزاد سلطان ابادی',
            '09120448549' => 'مبینا افشار ',
            '09184600191' => 'الهام فتوحی ',
            '09332355650' => 'شبنم یزدانپرست ',
            '09039647020' => 'سحر احمدی ',
            '09308214385' => 'بیتا زنده روحان ',
            '09134408991' => 'فاطمه مغفوری ',
            '09386606313' => 'حوریه خسروآباد ',
            '09362849887' => 'ترنم توانا ',
            '09915280464' => 'آرزو پیدایش ',
            '09359579046' => 'آیلار محمودی ',
            '09183838508' => 'محبوبه رضایی ',
            '09122759417' => 'فائزه عبدالرضا ',
            '09120180709' => 'عسل بدریفر',
            '09039743370' => 'صبا اسماعیلی ',
            '09126061851' => 'صفورا عبدالهی ',
            '09925060769' => 'محدثه خدر ',
            '09332284947' => 'آنیل آلبوغبیش ',
            '09156209442' => 'سمیه خزایی ',
            '09106211826' => 'آیلین احمدی ',
            '09122475343' => 'نفیسه محمدی ',
            '09360026135' => 'شبنم کفیلی ',
            '09197910225' => 'مینا قادری',
            '09014937595' => 'مدینه خلفی ',
            '09174343353' => 'زهرا کمالی',
            '09114343659' => 'ندا مبشره',
            '09126806792' => 'مریم مستوفی فرد',
            '09055452554' => 'رومینا ذولفی زاده',
            '09379876271' => 'مائده گیاهی',
            '09359252729' => 'فاطمه هاشمی ',
            '09331346823' => 'فاطمه اسماعیل پور',
            '09134060209' => 'آناهیتا زنگنه ',
            '09172220034' => 'شیما سعادت ',
            '09384623260' => 'فرحناز قاصدزاده ',
            '09134266956' => 'محسن یوسفی ',
            '09177701405' => 'غزاله رهبر ',
            '09173839766' => 'الناز قاسمی ',
            '09123827440' => 'مریم قنبری',
            '09210729904' => 'شیدا خدابنده لو',
            '09120721711' => 'لوریتا براری ',
            '09194456742' => 'هلاله میراحمدی ',
            '09381238651' => 'کوثر فلاح',
            '09118544505' => 'فاطمه پولادی ',
            '09114735448' => 'فاطمه سلمانی ',
            '09354150680' => 'شهرزاد رضاپور',
            '09113412545' => 'شکوه فلاح ابراهیمی',
            '09173982515' => 'هانیه شمس ',
            '09391935016' => 'راحله اسماعیلی ',
            '09184846166' => 'مریم ملکی ',
            '09129468199' => 'ساناز نوری ',
            '09045991601' => 'نگین محمدپور ',
            '09126713892' => 'اکرم مظلومی ',
            '09142343615' => 'مینا دامنگیر ',
            '09391023739' => 'طیبه جهانیان ',
            '09198964010' => 'دنیا حیدری ',
            '09120731786' => 'هانیه امیری ',
            '09104318387' => 'زینب موسوی ',
            '09023730033' => 'ساره درویشی ',
            '09116904361' => 'مصی رجبی ',
            '09129098961' => 'سمانه ایزدی',
            '09010846347' => 'پرستو نصیرزاده ',
            '09058742078' => 'آرزو عالی پور ',
            '09024269880' => 'طوبی کرایی ',
            '09134558521' => 'خاطره زارع ',
            '09905166990' => 'مهتاب میرزایی کیا ',
            '09128027021' => 'فاطمه مطلبی ',
            '09118451561' => 'مینا پارسایی ',
            '09188545900' => 'کوثر کرمی ',
            '09025612825' => 'مژده روستایی ',
        ];
    }
}
