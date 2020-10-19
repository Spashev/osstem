<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Smsc\SmsService;

class SmsNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command start notification service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $to = Carbon::now()->addDays('3')->format('Y-m-d');
        $payments = Payment::with('contract')->where('payment_date', $to)->where('sms_status', 'on')->where('paid', '<', 'amount')->where('remain', '>', 0)->get();
        $result = [];
        if (count($payments) > 0) {
            dump('За 3 дня оплаты смс');
            foreach ($payments as $payment) {
                if ($payment->contract->customer->sms_status == 'on') {
                    $message = "Unionp\nУважаемый %s!, Уведомляем вас, что ежемесячный платеж %sтг до %s.";
                    $result = [
                        'customer_name' => $payment->contract->customer->name,
                        'customer_phone' => $payment->contract->customer->phone,
                        'amount' => $payment->amount,
                        'deadline' => Str::substr($payment->deadline, 0, 10)
                    ];
                    $text = sprintf($message, $result['customer_name'], $result['amount'], $result['deadline']);
                    // $sms = new SmsService();
                    // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = 'UnionP');
                    // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                    dump($text);
                    info($text);
                    $status = true;
                    if ($status) {
                        $payment->notifications()->create([
                            'payment_id' => $payment->id,
                            'customer_name' => $result['customer_name'],
                            'contract_no' => $payment->contract->contract_no,
                            'phone_number' => $result['customer_phone'],
                            'amount' => $result['amount'],
                            'status' => 0
                        ]);
                    }
                }
            }
        }

        $now = Carbon::now()->format('Y-m-d');
        $payments = Payment::with('contract', 'notifications')->where('deadline', $now)->where('sms_status', 'on')->where('remain', '>', 0)->get();
        if (count($payments) > 0) {
            dump('Просрочка');
            foreach ($payments as $payment) {
                dump($payment->notifications);
                if ($payment->contract->customer->sms_status == 'on' && count($payment->notifications) == 0) {
                    $contract_payments = Payment::where('contract_id', $payment->contract_id)->where('deadline', '<', $now)->where('sms_status', 'on')->where('remain', '>', 0)->get();
                    $first_paymant_day = $contract_payments->first()->deadline;
                    $delay = Carbon::createFromDate($first_paymant_day)->diffInDays($now);
                    $sum = 0;
                    foreach ($contract_payments as $payment_contract) {
                        $delayInDays = Carbon::createFromDate($payment_contract->deadline)->addMonth()->diffInDays($payment_contract->deadline);
                        if ($contract_payments->first()->percent == 0) {
                            $sum += (Carbon::now()->month - Carbon::createFromDate($payment_contract->deadline)->month) * $payment_contract->remain;
                        } else {
                            $sum += (($payment_contract->percent * $payment_contract->remain) / 100) * $delayInDays + $payment_contract->remain;
                        }
                    }
                    $message = "Unionp\nУважаемый %s!, Уведомляем вас, что ежемесячный платеж %sтг просрочен, сумма с процентом %s  дата %s.";
                    $result = [
                        'customer_name' => $contract_payments->first()->contract->customer->name,
                        'customer_phone' => $contract_payments->first()->contract->customer->phone,
                        'amount' => $contract_payments->first()->amount,
                        'deadline' => Str::substr($payment->deadline, 0, 10),
                    ];
                    $text = sprintf($message, $result['customer_name'], $result['amount'], $sum, $now);
                    dump($text, $delay, $first_paymant_day, $sum);
                    info($text);
                    // $sms = new SmsService();
                    // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = ' Union Partners LLP');
                    // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                    $status = true;
                    if ($status) {
                        $payment->notifications()->create([
                            'payment_id' => $payment->id,
                            'customer_name' => $result['customer_name'],
                            'contract_no' => $contract_payments->first()->contract->contract_no,
                            'phone_number' => $result['customer_phone'],
                            'amount' => $result['amount'],
                            'status' => 1
                        ]);
                        $payment->save();
                    }
                }
            }
        }
        return 0;
    }
}